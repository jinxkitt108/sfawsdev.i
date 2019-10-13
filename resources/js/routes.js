import Vue from 'vue';
import VueRouter from 'vue-router';


// Component Routers
Vue.use(VueRouter)
let routes = [
    { path: '/home', component: require('./components/Home.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/search', component: require('./components/Search.vue').default },
]
const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
})

export default router;