import Vue from 'vue';
import VueRouter from 'vue-router';


// Component Routers
Vue.use(VueRouter)
let routes = [
    { path: '/home', component: require('./components/Home.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/marketplace', component: require('./components/Marketplace.vue').default },
    { path: '/thestore', component: require('./components/TheStore.vue').default },
    { path: '/mystore', component: require('./components/MyStore.vue').default },
    { path: '/buildstore', component: require('./components/BuildStore.vue').default },

    // Admin Routes
    { path: '/users', component: require('./components/User.vue').default },
    { path: '/experts', component: require('./components/Expert.vue').default },
    { path: '/admin_session', component: require('./components/AdminSession.vue').default },

    //Expert Routes
    { path: '/session', component: require('./components/Session.vue').default },

    { path: '/search', component: require('./components/Search.vue').default },
    { path: '/view_user', component: require('./components/ViewUser.vue').default },
    { path: '/view-product/:id',  props: { default: true}, component: require('./components/ViewProduct.vue').default },
    { path: '/discussion', component: require('./components/Discussion.vue').default },
    { path: '/view_discussion', component: require('./components/ViewDiscussion.vue').default },
    { path: '/mailbox', component: require('./components/Mailbox.vue').default },
    { path: '/view_message', component: require('./components/ViewMessage.vue').default },
    { path: '/sample', component: require('./components/Sample.vue').default }
]
const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
})

const originalPush = VueRouter.prototype.push
VueRouter.prototype.push = function push (location, onResolve, onReject) {
  if (onResolve || onReject) return originalPush.call(this, location, onResolve, onReject)
  try {
    return originalPush.call(this, location).catch(err => err)
  } catch (error) {
    console.log(error)
  }
}

export default router;
