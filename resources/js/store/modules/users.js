import axios from "axios";

const state = {
    followingUsers: [],
    currentUser: [],
    expertUsers: []
};

const getters = {
    allFollowingUsers: (state) => state.followingUsers,
    getCurrentUser: (state) => state.currentUser,
    getExpertUsers: (state) => state.expertUsers
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

    async fetchExpertUsers({ commit }) {
        const response = await axios.get("api/experts");
        commit('setExpertUsers', response.data);
    },

};

const mutations = {
    setfollowingUsers: (state, users) => (state.followingUsers = users),
    setCurrentUser: (state, user) => (state.currentUser = user),
    setExpertUsers: (state, users) => (state.expertUsers = users)
};

export default {
    state,
    getters,
    actions,
    mutations
};
