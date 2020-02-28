import axios from "axios";

const state = {
    allConversations: [],
    allUnreadChatCount: [],
    conversation: []
};

const getters = {
    getAllConversations: state => state.allConversations,
    getConversation: state => state.conversation,
    getAllUnreadChatCount: state => state.allUnreadChatCount
};

const actions = {
    resetConversation({ commit }) {
        commit("reset");
    },

    async addChatMessage({ commit }, chat) {
        const response = await axios.post("api/chat", chat);
        commit("newChat", response.data);
    },

    async fetchConversation({ commit }, with_user) {
        const response = await axios.get("api/conversation/" + with_user.id);
        commit("setConversation", response.data);
    },

    async fetchAllConversations({ commit }) {
        const response = await axios.get("api/all_conversation");
        commit("setAllConversations", response.data);
    },

    async fetchAllUnreadChatCount({ commit }) {
        const response = await axios.get("api/all_chat_count");
        commit("setAllUnreadChatCount", response.data);
    }
};

const mutations = {
    reset: state => (state.conversation = []),

    setConversation: (state, conversation) => {
        if (conversation.total) {
            state.conversation = conversation.data;
        } else {
            state.conversation = [];
        }
    },

    setAllConversations: (state, conversations) =>
        (state.allConversations = conversations.data),

    newChat: (state, chat) => state.conversation.push(chat),

    setAllUnreadChatCount: (state, count) => (state.allUnreadChatCount = count)
};

export default {
    state,
    getters,
    actions,
    mutations
};
