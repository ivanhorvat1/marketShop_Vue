
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import VTooltip from 'v-tooltip';

//window.Vue = require('vue');
Vue.use(VTooltip);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('articles', require('./components/Articles.vue').default);
Vue.component('navbar', require('./components/Navbar.vue').default);
Vue.component('action', require('./components/Action.vue').default);
Vue.component('freeze', require('./components/Freeze.vue').default);
Vue.component('drinks', require('./components/Drinks.vue').default);
Vue.component('sweets', require('./components/Sweets.vue').default);
Vue.component('meats', require('./components/Meats.vue').default);
Vue.component('adminhome', require('./components/AdminHome.vue').default);
Vue.component('comparedismarket', require('./components/CompareDisMarketDrink.vue').default);
Vue.component('comparedismarketmeat', require('./components/CompareDisMarketMeat.vue').default);
Vue.component('comparedismarketfreeze', require('./components/CompareDisMarketFreeze.vue').default);
Vue.component('comparedismarketsweet', require('./components/CompareDisMarketSweet.vue').default);
Vue.component('compareuniverexportmarketdrink', require('./components/CompareUniverexportMarketDrink.vue').default);
// Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');


const app = new Vue({
    el: '#app'
});
