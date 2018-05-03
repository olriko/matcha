import Vuex from 'vuex'
import Vue from 'vue'
import router from './router'
import cookies from 'js-cookie';
import jsonwebtoken from 'jsonwebtoken';

Vue.use(Vuex);

function geoloacation() {
    navigator.geolocation.getCurrentPosition((pos) => {
        updateGeo({
            lat: pos.coords.latitude,
            lng: pos.coords.longitude
        })
    }, (err) => {
        console.warn(`ERROR(${err.code}): ${err.message}`);
        axios.post('https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyC8OPijDCwhwmX-2xPT_R4Rd8TH4bq9MZk').then((res) => {
            if (res.status === 200) {
                updateGeo(res.data.location);
            }
        })
    }, {
        enableHighAccuracy: false,
        timeout: 5000,
        maximumAge: 0
    });
}

function updateGeo(geo) {
    axios.put('/api/user/geo', geo).then(() => {

    })
}

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
                geoloacation()
            } else {
                delete window.axios.defaults.headers['TOKEN'];
                cookies.remove('token');
                state.payload = null;
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
                    router.push({name: 'home'});
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
            router.push({name: 'login'});
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