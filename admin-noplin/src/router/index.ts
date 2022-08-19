import {createRouter, createWebHistory} from 'vue-router'

const routes = [
    {
        path: '/login',
        name: 'login',
        component: () => import('@/views/auth/Layout.vue')
    },
    {
        path: '/',
        name: 'home',
        component: () => import('@/views/home/Layout.vue')
    }
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
})

export default router
