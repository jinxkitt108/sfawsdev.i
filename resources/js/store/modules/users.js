import axios from "axios";

const state = {
    user: [],
    followingUsers: [],
    currentUser: [],
    expertUsers: []
};

const getters = {
    getUser: (state) => state.user,
    allFollowingUsers: (state) => state.followingUsers,
    getCurrentUser: (state) => state.currentUser,
    getExpertUsers: (state) => state.expertUsers
};

const actions = {

    async fetchUser({ commit }, id) {
        const response = await axios.get('api/user/' + id);
        commit('setUser', response.data);
    },

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
    setUser: (state, user) => (state.user = user),
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
