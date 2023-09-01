/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

import router from './routes/index'
import axios from './axios/index'
import store from './store/index'

// Main || Base Component
import App from './layout/App.vue'
import VueAxios from 'vue-axios';

import VueGoogleMaps from '@fawmi/vue-google-maps'


/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp(App)
app.use(store)
app.use(router)
app.use(VueAxios, axios)
app.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyAYO-YGm7Ht36NG-1h8jlghCmI6KyaQ2rM',
    },
})
app.mount('#app');
