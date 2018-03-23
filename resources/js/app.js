import Vuex from 'vuex'
import VueRouter from 'vue-router'
import BootstrapVue from 'bootstrap-vue'
import App from './App.vue'
import router from './router'

require('./bootstrap');

window.Vue = require('vue');

Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(BootstrapVue);

/**
 * Global Components
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

new Vue({
    router,
    store: require('./store.js'),
    render: h => h(App)
}).$mount('#app');