import router from "../../router";

export default {
    namespaced: true,
    state: {
        token: localStorage.getItem('token') || null,
        user: JSON.parse(localStorage.getItem('user')) || null
    },
    getters: {
        user(state) {
            return state.user
        },
        token(state) {
            return state.token
        }
    },
    mutations: {
        setUser(state, value) {
            state.user = value
        },
        setToken(state, value) {
            state.token = value
        }
    },
    actions: {

        checkAuth({state, dispatch, commit}) {

            const config = {
                headers: {
                    'Authorization' : 'Bearer ' + state.token
                }
            }

            axios.get('/api/user', config).then(response => {
                let user = response.data

                localStorage.setItem('user', JSON.stringify(user))
                commit('setUser', user)
            }).catch(() => {
                dispatch('signOut')
            })
        },

        signIn({commit, dispatch}, token) {

            localStorage.setItem('token', token)
            commit('setToken', token)

            router.push({name: "users.index"})
        },
        signOut({commit}) {

            localStorage.removeItem('token')
            localStorage.removeItem('user')

            commit('setUser', null)
            commit('setToken', null)

            router.push({name: "login"})
        }
    }
}
