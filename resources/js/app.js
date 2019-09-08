/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.$= window.jQuery= require('jquery');
window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('category-component', require('./components/Category.vue').default);
Vue.component('article-component', require('./components/Article.vue').default);
Vue.component('client-component', require('./components/Client.vue').default);
Vue.component('supplier-component', require('./components/Supplier.vue').default);
Vue.component('rol-component', require('./components/Rol.vue').default);
Vue.component('user-component', require('./components/User.vue').default);
Vue.component('income-component', require('./components/Income.vue').default);
Vue.component('sale-component', require('./components/Sale.vue').default);
Vue.component('dashboard-component', require('./components/Dashboard.vue').default);
Vue.component('consultincome-component', require('./components/ConsultIncome.vue').default);
Vue.component('consultsale-component', require('./components/ConsultSale.vue').default);
Vue.component('notification-component', require('./components/Notification.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data:
    {
        menu: 0,
        notifications: []
    },
    created()
    {
        let me= this;
        axios.post('notifications/get').then(function(response)
        {
            me.notifications= response.data;
        }).catch(function(error)
        {
            console.log(error);
        });

        var userId= $('meta[name="userId"]').attr('content');

        Echo.private('App.User.' + userId).notification((notification) => {
            me.notifications.unshift(notification);
        });
    }
});
