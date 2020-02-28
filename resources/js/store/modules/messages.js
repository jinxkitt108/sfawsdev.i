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
        const config = { headers: { "Content-Type": "multipart/form-data", "type" : "POST" } };
        const response = await axios.post("api/message", message, config);
    },

    async fetchSentMessages({ commit }) {
        const response = await axios.get("api/sent");
        commit("setSentMessages", response.data);
    },

    async fetchInboxMessages({ commit }) {
        const response = await axios.get("api/inbox");
        commit("setInboxMessages", response.data);
    },

    async fetchMessage({ commit }, id) {
        const response = await axios.get(`api/message/${id}`);
        commit("setMessage", response.data);
    },

    async deleteOneInbox({ commit }, message) {
        const response = await axios.put("api/delete_one_inbox", message);
        commit("removeOneInbox", message.id);
    },

    async deleteInboxMessage({ commit }, message) {
        const response = await axios.put("api/delete_inbox", message);
        commit("removeInbox", message);
    },

    async deleteSentMessage({ commit }, message) {
        const response = await axios.put("api/delete_sent", message);
        commit("removeSent", message);
    }
};

const mutations = {
    setSentMessages: (state, messages) => (state.sentMessages = messages),
    setInboxMessages: (state, messages) => (state.inboxMessages = messages),
    setMessage: (state, message) => (state.message = message),
    removeSent: (state, messages) => {
        messages.forEach(item => {
            state.sentMessages = state.sentMessages.filter(
                message => message.id !== item.id
            );
        });
    },
    removeOneInbox: (state, id) =>
        (state.inboxMessages = state.inboxMessages.filter(
            message => message.message_id !== id
        )),
    removeInbox: (state, messages) => {
        messages.forEach(item => {
            state.inboxMessages = state.inboxMessages.filter(
                message => message.id !== item.id
            );
        });
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
