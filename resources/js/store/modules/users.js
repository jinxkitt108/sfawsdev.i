import axios from "axios";

const state = {
    followingUsers: [],
};

const getters = {
    allFollowingUsers: (state) => state.followingUsers
};

const actions = {
    async fetchFollowingUsers({ commit }) {
        const response = await axios.get('api/follow');
        commit('setfollowingUsers', response.data);
    }
};

const mutations = {
    setfollowingUsers: (state, users) => (state.followingUsers = users)
};

export default {
    state,
    getters,
    actions,
    mutations
};
