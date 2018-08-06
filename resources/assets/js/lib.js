/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
window.Vue = require('vue');

Vue.prototype.$http = axios;

// Vue.component('example', require('./components/Example.vue'));

// const app = new Vue({
//     el: '#app',

//     data: { 
//         loading: false,    
//         downloading: false,    
//         items: [],
//         message: "Just a test",
//     }
// });