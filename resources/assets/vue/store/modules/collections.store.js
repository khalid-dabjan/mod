import API from "../API";
import cats from './cats';

const state = {
  collection: {},
  collectionComments: [],
  itemsToAdd: [],
  offsets: [0, 0, 0, 0, 0]
};

// getters
const getters = {
  collection: state => state.collection,
  collectionComments: state => state.collectionComments,
  // collectionTotalPrice: (state, _, __, rootGetters) =>
  //   state.collection.items
  //     ? state.collection.items
  //         .reduce(
  //           (sum, itemId) =>
  //             sum + parseFloat(rootGetters.getItem(itemId).price),
  //           0
  //         )
  //         .toFixed(2)
  //     : "000",
  itemsToAddCollections: state => id => state.itemsToAdd[id]
};

// actions
const actions = {
  get_collection_details({ commit, state }, collectionId) {
    if (collectionId == state.collection.id) return Promise.resolve();
    return API.post("/collectionDetails", {
      collectionId
    }).then(res => {
      commit("ADD_ITEMS", res.data.data.items, { root: true });
      commit("ADD_SETS", res.data.data.sets, { root: true });
      res.data.data.items = res.data.data.items.map(item => item.id);
      res.data.data.sets = res.data.data.sets.map(set => set.id);
      commit("COLLECTION", res.data.data);
    });
  },
  add_collection({ commit }, payload) {
    return API.post("/createCollection", payload);
  },
  edit_collection({ commit }, payload) {
    return API.post("/editCollection", payload);
  },
  remove_collection({ commit }, collectionId) {
    return API.post("/deleteCollection", { collectionId }).then(res => {
      commit("REMOVE_COLLECTION", res.data.data.collection);
    });
  },
  like_collection_toggle({ commit }) {
    commit("LIKE_COLLECTION_TOGGLE");
  },
  get_collection_comments({ commit }, collectionId) {
    return API.post("/getCollectionComments", {
      collectionId,
      limit: 30
    }).then(res => {
      commit("COLLECTION_COMMENTS", res.data.data.comments);
    });
  },
  add_comment_to_collection({ commit, dispatch }, payload) {
    return API.post("/addCommentToCollection", {
      collectionId: payload.collectionId,
      text: payload.comment,
      parentId: "0"
    }).then(() => dispatch("get_collection_comments", payload.collectionId));
  },
  delete_comment_from_collection({ commit, dispatch }, payload) {
    return API.post("/deleteCollectionComment", payload).then(() =>
      dispatch("get_collection_comments", payload.collectionId)
    );
  },
  get_default_items_for_add_collection({ commit, state, rootGetters }){
    commit("ITEMS_FOR_ADD_COLLECTION",cats);
  },
  get_items_for_add_collection({ commit, state, rootGetters }) {
    return Promise.all(itemsToAdd(rootGetters.userId)).then(resArray => {
      commit("ITEMS_FOR_ADD_COLLECTION", resArray.map(res => res.data.data));
    });
  },
  collection_load_more_to_add({ commit, state, rootGetters }, view) {
    return itemsToAdd(rootGetters.userId, state.offsets[view])[view].then(
      res => {
        commit("ADD_ITEMS", res.data.data, { root: true });
        commit("LOAD_MORE_ITEMS_FOR_ADD_COLLECTION", {
          data: res.data.data,
          view
        });
      }
    );
  }
};

// mutations
const mutations = {
  COLLECTION(state, data) {
    state.collection = data;
  },
  REMOVE_COLLECTION(state) {
    state.collection = {};
  },
  LIKE_COLLECTION_TOGGLE(state) {
    if (state.collection) {
      state.collection.is_liked = !state.collection.is_liked;
      state.collection.is_liked
        ? state.collection.likes++
        : state.collection.likes--;
      state.collection = { ...state.collection };
    }
  },
  COLLECTION_COMMENTS(state, data) {
    state.collectionComments = data;
  },
  ITEMS_FOR_ADD_COLLECTION(state, arrayOfData) {
    state.offcollections = state.offsets.map(i => i + 6);
    state.itemsToAdd = arrayOfData;
  },
  LOAD_MORE_ITEMS_FOR_ADD_COLLECTION(state, payload) {
    state.offsets[payload.view] += 6;
    state.itemsToAdd[payload.view] = state.itemsToAdd[payload.view].concat(
      payload.data
    );
    state.itemsToAdd = [...state.itemsToAdd];
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};

function itemsToAdd(userId, offset) {
  offset = offset || 0;
  return [
    API.post("/getLikedSets", {
      userId: userId,
      offset: offset,
      limit: 6
    }),
    API.post("/getLikedItems", {
      userId: userId,
      offset: offset,
      limit: 6
    }),
    API.post("/getItemsFromCategory", {
      offset: offset,
      limit: 6,
      categoryId: 1
    }),
    API.post("/getItemsFromCategory", {
      offset: offset,
      limit: 6,
      categoryId: 4
    }),
    API.post("/getItemsFromCategory", {
      offset: offset,
      limit: 6,
      categoryId: 6
    }),
    API.post("/getItemsFromCategory", {
      offset: offset,
      limit: 6,
      categoryId: 24
    })
  ];
}
