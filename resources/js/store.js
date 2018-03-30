import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex);

const store =  new Vuex.Store({
    state: {
        jwt: null,
        errors: []
    },
    mutations: {
        jwt(state, jwt = null) {
            state.jwt = jwt
        },
        errors(state, errors = []) {
            state.errors = errors
        }
    },
    actions: {
        login({commit}, data) {
            axios.post('api/login', data).then((res) => {
                if (res.status === 200) {
                    commit('jwt', res.data.jwt)
                }
            }).catch((error) => {
                if (error.response.status === 422) {
                    commit('errors', error.response.data.errors)
                }
            })
        },
        logout({commit}) {
            commit('jwt')
        }
    },
    getters: {
        user: state => state.user,
        errors: state => state.errors
    },
    strict: true

});


export default store