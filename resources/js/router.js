import Vue from 'vue'
import VueRouter from 'vue-router'
/**
 * Components
 */

import Home from './components/pages/Home'
import Register from './components/pages/Register'
import Login from './components/pages/Login'
import Settings from './components/pages/Settings'
import Profile from './components/pages/Profile'
import Recover from './components/pages/Recover'
import Notifications from './components/pages/Notifications'

Vue.use(VueRouter);

const routes = [
    {path: '/', component: Home, name: 'home'},
    {path: '/register', component: Register, name: 'register'},
    {path: '/login', component: Login, name: 'login'},
    {path: '/settings', component: Settings, name: 'settings', meta: {requireAuth: true}},
    {path: '/user/:id', component: Profile, name: 'user'},
    {path: '/profile', component: Profile, name: 'profile'},
    {path: '/recover', component: Recover, name: 'recover'},
    {path: '/notifications', component: Notifications, name: 'notifications'},
];

const router = new VueRouter({
    mode: 'hash',
    routes
})


export default router