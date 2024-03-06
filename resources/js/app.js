require('./bootstrap');

import { createApp } from 'vue';
import HomePageComponent from './components/HomePageComponent.vue';
import router from './routes';

const app = createApp(HomePageComponent); 
app.use(router);
app.mount('#app');
