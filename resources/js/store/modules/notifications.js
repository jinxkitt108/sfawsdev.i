import axios from "axios";

const state = {
    allNotifications: []
};

const getters = {
    getAllNotifications: state => state.allNotifications,
};

const actions = {
    async fetchAllNotifications({ commit }) {
        const response = await axios.get("api/notification");
        commit("setAllNotifications", response.data)
    }
};

const mutations = {
    setAllNotifications: (state, notifications) =>
    (state.allNotifications = notifications),
};

export default {
    state,
    getters,
    actions,
    mutations
};
