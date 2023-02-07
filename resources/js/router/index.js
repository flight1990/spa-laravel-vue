import { createWebHistory, createRouter } from 'vue-router'
import store from '@/store'

const Login = () => import('@/pages/auth/Login.vue');
const Register = () => import('@/pages/auth/Register.vue');


const Layout = () => import('@/layouts/Default.vue');
const UsersIndex = () => import('@/pages/users/Index.vue');

const routes = [
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: "/",
        component: Layout,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "users.index",
                path: '/users',
                component: UsersIndex,
                meta: {
                    title: `Users`
                },

            }
        ],
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        if (store.state.auth.token) {
            next({ name: "users.index" })
        }
        next()
    } else {
        if (store.state.auth.token) {
            if (store.dispatch('auth/checkAuth')) {
                next()
            }
        } else {
            next({ name: "login" })
        }
    }
})

export default router
