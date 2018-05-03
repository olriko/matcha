import BootstrapVue from 'bootstrap-vue'
import App from './App.vue'
import router from './router'
import store from './store'
import Vue from 'vue'
import Luxon from 'vue-luxon'
import * as VueGoogleMaps from 'vue2-google-maps'


Vue.use(VueGoogleMaps, {
    load: {
        key: process.env.MIX_GOOGLE_API,
        libraries: 'places'
    }
});

require('./bootstrap');

Vue.use(BootstrapVue);
Vue.use(Luxon, {
    serverZone: 'UTC',
    serverFormat: 'sql',
    clientZone: 'locale',
    clientFormat: 'locale',
    localeLang: null,
    localeFormat: {}, // see localeFormat below
    diffForHumans: {}, // see diffForHumans below
    i18n: {} // see i18n below
});
Vue.component('google-map', VueGoogleMaps.Map);
Vue.component('google-marker', VueGoogleMaps.Marker);

/**
 * Global Components
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

new Vue({
    render: h => h(App),
    router,
    store
}).$mount('#app');