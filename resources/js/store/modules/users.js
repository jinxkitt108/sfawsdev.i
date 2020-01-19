import axios from "axios";

const state = {
    followingUsers: [],
    currentUser: []
};

const getters = {
    allFollowingUsers: (state) => state.followingUsers,
    getCurrentUser: (state) => state.currentUser
};

const actions = {

    async fetchFollowingUsers({ commit }) {
        const response = await axios.get('api/follow');
        commit('setfollowingUsers', response.data);
    },

    async fetchCurrentUser({ commit }) {
        const response = await axios.get("api/profile");
        commit('setCurrentUser', response.data);
    },
    
};

const mutations = {
    setfollowingUsers: (state, users) => (state.followingUsers = users),
    setCurrentUser: (state, user) => (state.currentUser = user)
};

export default {
    state,
    getters,
    actions,
    mutations
};
