/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
//git fetch --all
//git reset --hard origin/master

require('./bootstrap');

window.Vue = require('vue');

import translations from '../lang/vue-translations.json'
//php artisan lang:js resources/lang/vue-translations.json --json

Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('user-component', require('./components/UserComponent.vue').default);
Vue.component('role-component', require('./components/RoleComponent.vue').default);
Vue.component('permission-component', require('./components/PermissionComponent.vue').default);
Vue.component('config-component', require('./components/ConfigComponent.vue').default);
Vue.component('settings-component', require('./components/SettingsComponent.vue').default);


let http = 'https://';
if (location.protocol !== 'https:') {
    http = 'http://';
}

const CONFIG = {

    API_URL: http + window.location.hostname + '/taxi/public/api/',
    PATH: '/taxi/public',
    LANG: translations
}

export default CONFIG;

const app = new Vue({
    el: '#app',
});