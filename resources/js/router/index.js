import { createWebHistory, createRouter } from 'vue-router'
import store from '@/store'

const Login = () => import('@/pages/auth/Login.vue');
const Register = () => import('@/pages/auth/Register.vue');

const Layout = () => import('@/layouts/Default.vue');
const UsersIndex = () => import('@/pages/users/Index.vue');
const UsersCreate = () => import('@/pages/users/Create.vue');
const UsersEdit = () => import('@/pages/users/Edit.vue');

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
                path: "/users",
                component: UsersIndex,
                meta: {
                    title: `Users`
                },
            },
            {
                name: "users.create",
                path: "/users/create",
                component: UsersCreate,
                meta: {
                    title: `Create new user`
                },
            },
            {
                name: "users.edit",
                path: "/users/:id/edit",
                component: UsersEdit,
                meta: {
                    title: `Edit user`
                },
                beforeEnter(to, from, next) {

                    store.dispatch('users/fetchOneUse', to.params.id).then(() => {
                        next()
                    })

                }
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
            store.dispatch('auth/checkAuth')
            next()
        } else {
            next({ name: "login" })
        }
    }
})

export default router
