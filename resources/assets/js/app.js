
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('articles', require('./components/Articles.vue').default);
Vue.component('navbar', require('./components/Navbar.vue').default);
Vue.component('freeze', require('./components/Freeze.vue').default);
Vue.component('drinks', require('./components/Drinks.vue').default);
Vue.component('sweets', require('./components/Sweets.vue').default);
Vue.component('meats', require('./components/Meats.vue').default);
Vue.component('comparedismarket', require('./components/CompareDisMarket.vue').default);

const app = new Vue({
    el: '#app'
});
