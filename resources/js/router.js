import VueRouter from 'vue-router'

/**
 * Components
 */

import Home from './components/pages/Home'

const routes = [
    { path: '/', component: Home },
];

export default new VueRouter({
    mode: 'history',
    routes
})