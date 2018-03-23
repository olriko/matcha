import Vue from 'vue'
import VueRouter from 'vue-router'

/**
 * Components
 */

import Home from './components/pages/Home'
import Register from './components/pages/Register'
import Login from './components/pages/Login'

Vue.use(VueRouter);

const routes = [
    { path: '/', component: Home, name: 'home'},
    { path: '/register', component: Register, name: 'register' },
    { path: '/login', component: Login, name: 'login' },
];

export default new VueRouter({
    mode: 'hash',
    routes
})