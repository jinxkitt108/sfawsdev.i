import axios from "axios";

const state = {
    sentMessages: [],
    inboxMessages: []
};

const getters = {
    getSentMessages: state => state.sentMessages,
    getInboxMessages: state => state.inboxMessages
};

const actions = {
    async addMessage({ commit }, message) {
        const response = await axios.post("api/message", message);
    },

    async fetchSentMessages({ commit }) {
        const response = await axios.get("api/message");
        commit("setSentMessages", response.data);
    },

    async fetchInboxMessages({ commit }) {
        const response = await axios.get("api/inbox");
        commit("setInboxMessages", response.data);
    }
};

const mutations = {
    setSentMessages: (state, messages) => (state.sentMessages = messages),
    setInboxMessages: (state, messages) => (state.inboxMessages = messages)
};

export default {
    state,
    getters,
    actions,
    mutations
};
