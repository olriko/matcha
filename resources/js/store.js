import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex);

const store =  new Vuex.Store({
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
    getters: {
        user: state => state.user
    },
    strict: true

})


export default store