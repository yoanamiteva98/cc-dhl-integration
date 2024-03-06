# Use an official PHP runtime as the base image for Laravel
FROM php:8.3-fpm

# Set the working directory in the container
WORKDIR /var/www/html

# Install PHP extensions and other dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and npm
RUN apt-get update && apt-get install -y \
    nodejs \
    npm

# Copy composer.json and composer.lock (if available)
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the Laravel application code
COPY . .

# Generate autoload files
RUN composer dump-autoload

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Set the working directory in the container to the Laravel root directory
WORKDIR /var/www/html

# Copy package.json and package-lock.json (if available) for frontend
COPY package*.json ./

# Install frontend dependencies
RUN npm install

# Copy compiled frontend assets from local directory into the container
COPY ./resources /var/www/html/public

# Port on which app runs
EXPOSE 8000

# Command to run Laravel application
CMD ["php", "artisan", "serve", "--host=0.0.0.0"]
