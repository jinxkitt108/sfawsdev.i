import axios from "axios";

const state = {
    discussion: [],
    allReply: [],
    allDiscussions: [],
    myDiscussions: []
};

const getters = {
    getDiscussion: state => state.discussion,
    getAllReply: state => state.allReply,
    getAllDiscussions: state => state.allDiscussions,
    getMyDiscussions: state => state.myDiscussions
};

const actions = {
    async addDiscussion({ commit }, discussion) {
        const response = await axios.post("api/discussion", {
            title: discussion.title,
            category: discussion.category,
            content: discussion.content,
            photo: discussion.photo
        });

        commit("newDiscussion", response.data);
    },

    async fetchDiscussions({ commit }) {
        const response = await axios.get("api/discussion");
        commit("setDiscussions", response.data);
    },

    async fetchDiscussion({ commit }, id) {
        const response = await axios.get("api/discussion/" + id);
        commit("setDiscussion", response.data);
    },

    async fetchAllReply({ commit }, id) {
        const response = await axios.get("api/reply/" + id);
        commit("setAllReply", response.data);
    },

    async addReply({ commit }, reply) {
        const response = await axios.post("api/reply", {
            discussion_id: reply.discussion_id,
            to_user_id: reply.to_user_id,
            content: reply.content,
            photo: reply.photo
        });
    },

    async updateBestAnswer({ commit }, reply) {
        const response = await axios.put("api/best_answer", reply);
    },

    async updateResolved({ commit }, discussion) {
        const response = await axios.put("api/resolved", discussion);
    },

    async fetchMyDiscussions({ commit }) {
        const response = await axios.get("api/mydiscussions");
        commit("setMyDiscussions", response.data);
    },

    async deleteDiscussion({ commit, dispatch }, id) {
        const response = await axios.delete(`api/discussion/${id}`).then(() => {
            Toast.fire({
                type: "success",
                title: "Topic deleted successfuly!"
            });
            dispatch("fetchDiscussions");
            commit("removeDiscussion", id);
        });
    },
    async deleteReply({ commit }, id) {
        const response = await axios.delete(`api/reply/${id}`).then(() => {
            Toast.fire({
                type: "success",
                title: "Reply deleted successfuly!"
            });
            commit("removeReply", id);
        });
    },

    async updateDiscussion({ commit }, discussion) {
        const response = await axios.put(
            "api/discussion/" + discussion.id,
            discussion
        );
        commit("updtDiscussion", response.data);
    }
};

const mutations = {
    setDiscussions: (state, discussions) =>
        (state.allDiscussions = discussions),
    setDiscussion: (state, discussion) => (state.discussion = discussion),
    newDiscussion: (state, discussion) =>
        state.allDiscussions.unshift(discussion),
    setAllReply: (state, replies) => (state.allReply = replies),
    setMyDiscussions: (state, discussions) =>
        (state.myDiscussions = discussions),
    updtDiscussion: (state, discussion) => {
        const index = state.myDiscussions.findIndex(
            topic => topic.id === discussion.id
        );
        if (index !== -1) {
            state.myDiscussions.splice(index, 1, discussion);
        }
    },
    removeDiscussion: (state, id) =>
        (state.myDiscussions = state.myDiscussions.filter(
            discussion => discussion.id !== id
        )),
    removeReply: (state, id) =>
        (state.allReply = state.allReply.filter(reply => reply.id !== id))
};

export default {
    state,
    getters,
    actions,
    mutations
};
