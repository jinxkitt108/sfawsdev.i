import axios from "axios";

const state = {
    discussion: [],
    allReply: [],
    allDiscussions: [],
};

const getters = {
    getDiscussion: (state) => state.discussion,
    getAllReply: (state) => state.allReply,
    getAllDiscussions: (state) => state.allDiscussions
};

const actions = {
    async addDiscussion({ commit }, discussion) {
        const response = await axios.post('api/discussion', {
            'title': discussion.title,
            'category': discussion.category,
            'content': discussion.content,
            'photo': discussion.photo,
        })
        
        commit('newDiscussion', response.data)
    },

    async fetchDiscussions({ commit }){
        const response = await axios.get('api/discussion');
        commit('setDiscussions', response.data)
    },

    async fetchDiscussion({ commit }, id){
        const response = await axios.get('api/discussion/' + id);
        commit('setDiscussion', response.data)
    },

    async fetchAllReply({ commit }, id){
        const response = await axios.get('api/reply/' + id);
        commit('setAllReply', response.data)
    },

    async addReply({commit}, reply) {
        const response = await axios.post('api/reply', {
            'discussion_id': reply.discussion_id,
            'to_user_id': reply.to_user_id,
            'content': reply.content,
            'photo': reply.photo
        })
    },

    async updateBestAnswer({commit}, reply) {
        const response = await axios.put('api/best_answer', reply);
    },

    async updateResolved({commit}, discussion) {
        const response = await axios.put('api/resolved', discussion);
    }

};

const mutations = {
    setDiscussions: (state, discussions) => (state.allDiscussions = discussions),
    setDiscussion: (state, discussion) => (state.discussion = discussion),
    newDiscussion: (state, discussion) => state.allDiscussions.unshift(discussion),
    setAllReply: (state, replies) => (state.allReply = replies)
};

export default {
    state,
    getters,
    actions,
    mutations
};
