import Vue from 'vue';
import Vuex from 'vuex';

import messages from './modules/messages';
import users from './modules/users';
import stores from './modules/stores';
import products from './modules/products';
import discussions from './modules/discussions';

//Load Vuex 
Vue.use(Vuex);

//Create the Store
export default new Vuex.Store({
    modules: {
        messages,
        users,
        stores,
        products,
        discussions,
    }
});