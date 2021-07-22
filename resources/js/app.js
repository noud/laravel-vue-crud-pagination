require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue';

//Import v-from
import { Form,  } from 'vform'
// import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
// Vue.component(HasError.name, HasError)
// Vue.component(AlertError.name, AlertError)

//Pagination laravel-vue-pagination
Vue.component('pagination', require('laravel-vue-pagination'));

let Fire = new Vue()
window.Fire = Fire;

Vue.component('category-component', require('./components/CategoryComponent.vue').default);

//Routes
import { routes } from './routes';

//Register Routes
import VueRouter from 'vue-router'
const router = new VueRouter({
    routes, 
    mode: 'hash',
})

const app = new Vue({
    el: '#app',
    router
});
