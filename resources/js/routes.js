// resources/js/routes.js

import { createRouter, createWebHistory } from 'vue-router';
import HomePageComponent from './components/HomePageComponent.vue';

const routes = [
  { path: '/', component: HomePageComponent },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
