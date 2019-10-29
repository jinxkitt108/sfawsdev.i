import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
      users: [],
    },

    mutations: {
        loadUsers(state, users){
            state.users.push(users.data)
        },
        search(state, users) {
          state.users = users
        }
    },

    getters: {
        users: state => state.users,
        usersCount: state => {
           return state.users.length
        }
      }
})