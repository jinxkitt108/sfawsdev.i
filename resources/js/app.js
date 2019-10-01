/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
//Sweetalerts
window.Swal = require('sweetalert2');
import Vuetify from 'vuetify';
import Routes from '@/js/routes.js';
import App from '@/js/views/App';
import { Form, HasError, AlertError } from 'vform';
import Vuelidate from 'vuelidate'
import VueProgressBar from 'vue-progressbar'
import Vue2Filters from 'vue2-filters'
import moment from 'moment';
import VueCroppie from 'vue-croppie';
import 'croppie/croppie.css' // import the croppie css manually

// Crop Image Plug in
Vue.use(VueCroppie);

// Front-end Access Control
import Gate from './Gate';
Vue.prototype.$gate = new Gate(window.user);

//Global Filters
Vue.use(Vue2Filters)
Vue.filter('createdDate', function(created) {
    return moment(created).format('MMMM D, YYYY');
});
Vue.filter('postDate', function(created) {
    return moment(created).format('lll');
});

// Progress Bar
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '4px'
})

// Fire an event
window.Fire = new Vue();

//Toast
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
})
window.Toast = Toast;


// Vuelidate
Vue.use(Vuelidate)

// VUE FORM
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

// VUETIFY
Vue.use(Vuetify)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router: Routes,
    render: h => h(App),
    vuetify: new Vuetify({
        theme: {
    	    themes: {
                light: {
                    primary: '#0A4D22',
                    secondary: '#0A4D22',
                    accent: '#000',
                    error: '#FF5252',
                    info: '#2196F3',
                    success: '#4CAF50',
                    warning: '#FFC107',
                },
                dark: {
                    primary: '#07fd5f',
                    secondary: '#0A4D22',
                    accent: '#fff',
                    error: '#FF5252',
                    info: '#2196F3',
                    success: '#4CAF50',
                    warning: '#FFC107',
                }
    	    }
    	},
        icons: {
            iconfont: 'fa4'
        }
    })
});

export default app;
