import router from "../../router";

export default {
    namespaced: true,
    state: {
        userOtp: JSON.parse(localStorage.getItem('userOtp')) || null
    },
    getters: {
        userOtp(state) {
            return state.userOtp
        }
    },
    mutations: {
        setUserOtp(state, value) {
            state.userOtp = value
        }
    },
    actions: {
        saveUserOtp({commit}, userOtp) {
            localStorage.setItem('userOtp', JSON.stringify(userOtp))
            commit('setUserOtp', userOtp)

            router.push({name: 'otp.login'})
        },
        deleteUserOtp({commit}) {
            localStorage.removeItem('userOtp')
            commit('setUserOtp', null)
        }
    }
}
