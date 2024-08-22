import { createRouter, createWebHistory } from "vue-router";
import Home from '../components/Home.vue'
import Stamp from '../components/Stamp.vue'
import Envelope from '../components/Envelope.vue'
const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
  },
  {
    path: '/stamp',
    name: 'stamp',
    component: Stamp,
  },

  {
    path: '/envelope',
    name: 'envelope',
    component: Envelope,
  },

]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    if(savedPosition) {
      return savedPosition;
    } else {
      return {top:0};
    }
  },
  routes

});

export default router;
