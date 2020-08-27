/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
//git fetch --all
//git reset --hard origin/master

require('./bootstrap');
//const firebase = require("firebase");
import * as VueGoogleMaps from 'vue2-google-maps';

// Parse Here
const Parse = require('parse');
Parse.initialize("NqwKQLRjvORNCns4fVHZy6jP8HaWDB2zf6X6l9JW", "bSjcbfvOmfq9NpTrEYhraRWrddOTlvhA4jWXiLru");
Parse.serverURL = "https://parseapi.back4app.com/";

var client = new Parse.LiveQueryClient({
    applicationId: 'NqwKQLRjvORNCns4fVHZy6jP8HaWDB2zf6X6l9JW',
    serverURL: 'wss://' + 'taxiplzz.back4app.io', // Example: 'wss://livequerytutorial.back4app.io'
    javascriptKey: 'bSjcbfvOmfq9NpTrEYhraRWrddOTlvhA4jWXiLru'
});
//************************* */

window.Vue = require('vue');
import VueNativeNotification from 'vue-native-notification'

Vue.use(VueNativeNotification, {
    // Automatic permission request before
    // showing notification (default: true)
    requestOnNotify: true
})

Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyANYVpeOpsNN4DqdKR4AKAyd03IQ3_9PvU',
        libraries: 'places,directions'
    },
});
Vue.use(require('vue-moment'));

import translations from '../lang/vue-translations.json'
//php artisan lang:js resources/lang/vue-translations.json --json

Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('user-component', require('./components/UserComponent.vue').default);
Vue.component('role-component', require('./components/RoleComponent.vue').default);
Vue.component('permission-component', require('./components/PermissionComponent.vue').default);
Vue.component('config-component', require('./components/ConfigComponent.vue').default);
Vue.component('settings-component', require('./components/SettingsComponent.vue').default);
Vue.component('home', require('./components/app/HomeComponent.vue').default);
Vue.component('drivers', require('./components/app/DriversComponent.vue').default);
Vue.component('order', require('./components/app/OrderComponent.vue').default);
Vue.component('notification', require('./components/app/NotificationComponent.vue').default);

function coolNumber(num) {
    if (num < 1000) {
        return $num;
    }

    if (num < 1000000) {
        return (num / 1000) + 'K';
    }
    if ($num >= 1000000) {
        return (num / 1000000) + 'M';
    }
}
let http = 'https://';
if (location.protocol !== 'https:') {
    http = 'http://';
}
// ssh root@142.93.174.231
// cd /home/marasiel.com/public_html/taxiplzz
const CONFIG = {

    API_URL: http + window.location.hostname + '/taxi/public/api/',
    PATH: '/taxi/public',
    FULL_PATH: 'http://' + window.location.hostname + '/taxi/public',
    /*API_URL: http + window.location.hostname + '/taxiplzz/public/api/',
    PATH: '/taxiplzz/public',
    FULL_PATH: http + window.location.hostname + '/taxiplzz/public',*/
    LANG: translations,
    PARSE: Parse,
    LIVEQ: client,
    coolNumber: coolNumber
}


export default CONFIG;

const app = new Vue({
    el: '#app',
});
