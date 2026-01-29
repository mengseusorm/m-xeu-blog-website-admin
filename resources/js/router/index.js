import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
    history: createWebHistory(),
    routes: [ 
        {
            path: '/',
            name: 'Home',
            component: () => import('../pages/Home.vue'),
            meta: { requiresAuth: false }
        },
        {
            path: '/blog',
            name: '/Blog',
            component: () => import('../pages/Blog.vue'),
            meta: { requiresAuth: false }
        },
        {
            path: '/post',
            name: '/post',
            component: () => import('../pages/post/PostListComponent.vue'),
            meta: { requiresAuth: false }
        },
        {
            path: '/about',
            name: '/About',
            component: () => import('../pages/About.vue'),
            meta: { requiresAuth: false }
        },
    ],
})

export default router
