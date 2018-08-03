import API from "../API";
import cats from "./cats";

const state = {
    set: {},
    setComments: [],
    itemsToAddSet:[],
    itemsToAddSetOffset:0,
};

// getters
const getters = {
    set: state => state.set,
    setComments: state => state.setComments,
    setTotalPrice: (state, _, __, rootGetters) =>
        state.set.items
            ? state.set.items
                .reduce(
                    (sum, itemId) =>
                        sum + parseFloat(rootGetters.getItem(itemId).price),
                    0
                )
                .toFixed(2)
            : "000",
    itemsToAddSet: state => state.itemsToAddSet,
};

// actions
const actions = {
    get_set_details({commit, state}, setId) {
        if (setId == state.set.id) return Promise.resolve();
        return API.post("/setDetails", {
            setId
        }).then(res => {
            commit("ADD_ITEMS", res.data.data.set.items, {root: true});
            res.data.data.set.items = res.data.data.set.items.map(item => item.id);
            commit("SET", res.data.data.set);
        });
    },
    add_set({commit}, payload) {
        return API.post("/addSet", payload);
    },
    edit_set({commit}, payload) {
        return API.post("/editSet", payload);
    },
    remove_set({commit}, setId) {
        return API.post("/deleteSet", {
            setId
        }).then(res => {
            commit("REMOVE_SET", res.data.data.set);
        });
    },
    like_set_toggle({commit}) {
        commit("LIKE_SET_TOGGLE");
    },
    get_set_comments({commit}, setId) {
        return API.post("/getSetComments", {
            setId,
            limit: 30
        }).then(res => {
            commit("SET_COMMENTS", res.data.data.comments);
        });
    },
    add_comment_to_set({commit, dispatch}, payload) {
        return API.post("/addCommentToSet", {
            setId: payload.setId,
            text: payload.comment,
            parentId: "0"
        }).then(() => dispatch("get_set_comments", payload.setId));
    },
    delete_comment_from_set({commit, dispatch}, {setId, commentId}) {
        return API.post("/deleteComment", {
            commentId
        }).then(() => dispatch("get_set_comments", setId));
    },
    get_default_items_for_add_set({commit}){
        commit("ITEMS_TO_ADD_SET_RESET", cats );
    },
    get_items_for_add_set({commit, state, rootGetters},q) {
        return API.post("/getSearchForAddSet", {
            query: q.query,
            category:q.category,
            color: q.color,
            offset:q.clearOffset?0:state.itemsToAddSetOffset,
            limit:6
        }).then((res) =>{
            if(q.clearOffset){
                commit("ITEMS_TO_ADD_SET_OFFSET_CLEAR");
            }
            commit("ADD_ITEMS", res.data.data, {root: true});
            commit("ITEMS_TO_ADD_SET", res.data.data);

        });

    },
};

// mutations
const mutations = {
    SET(state, data) {
        state.set = data;
    },
    REMOVE_SET(state) {
        state.set = {};
    },
    LIKE_SET_TOGGLE(state) {
        if (state.set.title_en) {
            state.set.is_liked = !state.set.is_liked;
            state.set.is_liked ? state.set.likes++ : state.set.likes--;
            state.set = {...state.set};
        }
    },
    SET_COMMENTS(state, data) {
        state.setComments = data;
    },
    ITEMS_TO_ADD_SET(state,data){
        state.itemsToAddSet = state.itemsToAddSet.concat(data);
        state.itemsToAddSetOffset+=6;
    },
    ITEMS_TO_ADD_SET_RESET(state,data){
        state.itemsToAddSet = data;
    },
    ITEMS_TO_ADD_SET_OFFSET_CLEAR(state){
        state.itemsToAddSetOffset=0;
        state.itemsToAddSet=[];
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
