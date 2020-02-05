import axios from "axios";

const state = {
    sentMessages: [],
    inboxMessages: [],
    message: []
};

const getters = {
    getSentMessages: state => state.sentMessages,
    getInboxMessages: state => state.inboxMessages,
    getMessage: state => state.message
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
    },

    async fetchMessage({commit}, id) {
        const response = await axios.get(`api/message/${id}`);
        commit("setMessage", response.data)
    },

    async deleteInboxMessage({ commit }, message) {
        const response = await axios.put("api/delete_inbox", message);
    },
};

const mutations = {
    setSentMessages: (state, messages) => (state.sentMessages = messages),
    setInboxMessages: (state, messages) => (state.inboxMessages = messages),
    setMessage: (state, message) => (state.message = message),
};

export default {
    state,
    getters,
    actions,
    mutations
};
