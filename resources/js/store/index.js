import Vue from 'vue';
import Vuex from 'vuex';

import messages from './modules/messages';
import users from './modules/users';

//Load Vuex 
Vue.use(Vuex);

//Create the Store
export default new Vuex.Store({
    modules: {
        messages,
        users,
    }
});