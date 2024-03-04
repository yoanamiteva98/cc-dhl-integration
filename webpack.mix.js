const mix = require('laravel-mix');
mix.setPublicPath('public');
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .vue(); 
