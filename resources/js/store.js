import Vuex from 'vuex'
import Vue from 'vue'
import router from './router'
import cookies from 'js-cookie';
import jsonwebtoken from 'jsonwebtoken'


Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        jwt: null,
        payload: null,
        errors: []
    },
    mutations: {
        jwt(state, jwt = null) {
            state.jwt = jwt;
            if (jwt) {
                window.axios.defaults.headers.common['TOKEN'] = jwt;
                cookies.set('token', jwt, {expires: 1});
                state.payload = jsonwebtoken.decode(jwt)
            } else {
                delete window.axios.defaults.headers['TOKEN'];
                cookies.remove('token');
                state.payload = null
            }
        },
        errors(state, errors = []) {
            state.errors = errors
        }
    },
    actions: {
        login({commit}, data) {
            axios.post('api/login', data).then((res) => {
                if (res.status === 200) {
                    commit('jwt', res.data.jwt);
                    router.push('home');
                }
            }).catch((error) => {
                if (error.response.status === 422) {
                    commit('errors', error.response.data.errors)
                }
            })
        },
        logout({commit}) {
            cookies.remove('token');
            delete window.axios.defaults.headers['TOKEN'];
            commit('jwt')
            router.push('login');
        }
    },
    getters: {
        jwt: state => state.jwt,
        payload: state => state.payload,
        currentUser: state => state.payload ? state.payload.user : null,
        errors: state => state.errors
    },
    strict: true

});


export default store