import Vue from 'vue';
import App from './App.vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap';
import router from './router';
import store from './store';

import BootstrapVue from 'bootstrap-vue';
import FlashMessage from '@smartweb/vue-flash-message';
Vue.use(BootstrapVue);
Vue.use(FlashMessage);

new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
});
