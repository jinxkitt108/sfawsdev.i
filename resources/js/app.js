/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");
window.moment = moment;
//Sweetalerts
window.Swal = require("sweetalert2");
import Vuetify from "vuetify";
import Routes from "@/js/routes.js";
import App from "@/js/views/App";
import { Form, HasError, AlertError } from "vform";
import Vuelidate from "vuelidate";
import VueProgressBar from "vue-progressbar";
import Vue2Filters from "vue2-filters";
import moment from "moment";
import VueCroppie from "vue-croppie";
import "croppie/croppie.css"; // import the croppie css manually
import store from "./store/";
import vueCountryRegionSelect from "vue-country-region-select";
import { Ripple } from "vuetify/lib/directives";
import Gate from "./Gate";
import VueAwesomeSwiper from "vue-awesome-swiper";
// require styles
import "swiper/dist/css/swiper.css";

Vue.use(VueAwesomeSwiper /* { default global options } */);

// VUE COUNTRY LIST
Vue.use(vueCountryRegionSelect);

// Crop Image Plug in
Vue.use(VueCroppie);

// Front-end Access Control
Vue.prototype.$gate = new Gate(window.user);

//Global Filters
Vue.use(Vue2Filters);
Vue.filter("createdDate", function(created) {
    return moment(created).format("MMMM D, YYYY");
});
Vue.filter("postDate", function(created) {
    return moment(created).format("lll");
});

Vue.filter("sinceDate", function(created) {
    return moment(created).fromNow();
});

Vue.filter("dateAtTime", function(created) {
    return moment(created).calendar();
});

// Progress Bar
Vue.use(VueProgressBar, {
    color: "rgb(143, 255, 199)",
    failedColor: "red",
    height: "4px"
});

// Fire an event
window.Fire = new Vue();

//Toast
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000
});
window.Toast = Toast;

// Vuelidate
Vue.use(Vuelidate);

// VUE FORM
window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

// VUETIFY
Vue.use(Vuetify, {
    // other stuff
    directives: {
        Ripple
    }
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);

Vue.component(
    "view-post",
    require("./components/ViewPost.vue").default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    router: Routes,
    store,
    render: h => h(App),
    vuetify: new Vuetify({
        theme: {
            themes: {
                light: {
                    primary: "#0A4D22",
                    secondary: "#d2d2d2",
                    info: "#2196F3",
                    success: "#4CAF50",
                    warning: "#FFC107",
                    danger: "#f30f0f"
                },
                dark: {
                    secondary: "#212121",
                    danger: "#ff3f3f"
                }
            }
        }
    })
});

export default app;
