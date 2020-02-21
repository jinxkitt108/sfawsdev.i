import Vue from 'vue';
import Vuex from 'vuex';

import messages from './modules/messages';
import users from './modules/users';
import posts from './modules/posts';
import stores from './modules/stores';
import products from './modules/products';
import discussions from './modules/discussions';
import notifications from './modules/notifications';
import chats from './modules/chats';

//Load Vuex
Vue.use(Vuex);

//Create the Store
export default new Vuex.Store({
    modules: {
        messages,
        users,
        posts,
        stores,
        products,
        discussions,
        notifications,
        chats
    }
});
