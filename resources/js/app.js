import BootstrapVue from 'bootstrap-vue'
import App from './App.vue'
import router from './router'
import store from './store'
import Vue from 'vue'

require('./bootstrap');

Vue.use(BootstrapVue);

/**
 * Global Components
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

new Vue({
    render: h => h(App),
    router,
    store
}).$mount('#app');