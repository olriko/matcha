import Vuex from 'vuex'

export default new Vuex.Store({
    state: {
        user: null,
        errors: []
    },
    mutations: {
        user(state, user = null) {
            state.user = user
        }
    },
    actions: {
        login({commit}, data) {
            axios.post('api/login', data).then((rep) => {
                if (rep.status === 200) {
                    commit('user', rep.data.user)
                }
            })
        },
        logout({commit}) {
            commit('user')
        }
    },
    strict: true
})