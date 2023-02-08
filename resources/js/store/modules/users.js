export default {
    namespaced: true,
    state: {
        users: null,
        user: null,
        isLoadingUsers: false
    },
    getters: {
        users(state) {
            return state.users
        },
        user(state) {
          return state.user
        },
        isLoadingUsers(state) {
            return state.isLoadingUsers
        }
    },
    mutations: {
        setUsers(state, value) {
            state.users = value
        },
        setUser(state, value) {
          state.user = value
        },
        setIsLoading(state, value) {
            state.isLoadingUsers = value
        }
    },
    actions: {
        fetchUsers({commit, rootState}, params) {

            commit('setIsLoading', true)

            const token = rootState.auth.token

            const config = {
                params: params,
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            }
            axios.get('/api/users', config).then(({data}) => {
                commit('setUsers', data)
            }).catch((e) => {
                console.log(e)
            }).finally(() => {
                commit('setIsLoading', false)
            })
        },
        fetchOneUse({commit, rootState}, id) {

            const token = rootState.auth.token

            const config = {
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            }

            axios.get(`/api/users/${id}`, config).then(({data}) => {
                commit('setUser', data.data)
            })
        }
    }
}
