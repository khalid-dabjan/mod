import API from "./API";

const state = {
  users: {},
  items: {},
  sets: {},
  collections: {},
  colors: [],
  sizes: [],
  brands: [],
  countries: []
};

const actions = {
  get_sizes({ commit, state }) {
    if (state.colors.length > 0) return Promise.resolve();
    return API.post("/getSizes").then(res => {
      commit("SIZES", res.data.data);
    });
  },
  get_colors({ commit },fillterd) {
    var fillter=fillterd || '';
    if (state.sizes.length > 0) return Promise.resolve();
    return API.post("/getColors",{
        "add_to_filter":fillter,
    }).then(res => {
      const data=[... res.data.data]
      commit("COLORS", res.data.data);
      return data;
    });
  },
  get_brands({ commit },cats_id) {
    var cats=  cats_id || '';
    if (state.sizes.length > 0) return Promise.resolve();
    return API.post("/getBrands",{categoriesId:cats}).then(res => {
      commit("BRANDS", res.data.data);
    });
  },
  get_countries({ commit }) {
    return API.post("/getCountries").then(response => {
      commit("COUNTRIES", response.data.data);
    });
  },
  like_item({ commit }, objId) {
    return API.post("/switchLike", {
      objId,
      targetObject: "item"
    }).then(() => {
      commit("LIKE_ITEM_PROPAGATE", objId);
    });
  },
  like_set({ commit, dispatch }, objId) {
    return API.post("/switchLike", {
      objId,
      targetObject: "set"
    }).then(() => {
      commit("LIKE_SET_PROPAGATE", objId);
    });
  },
  like_collection({ commit, dispatch }, objId) {
    return API.post("/switchLike", {
      objId,
      targetObject: "collection"
    }).then(() => {
      commit("LIKE_COLLECTION_PROPAGATE", objId);
    });
  },
  follow_user({ commit }, id) {
    return API.post("/followUser", {
      userId: id
    }).then(() => {
      commit("FOLLOW_USER_PROPAGATE", id);
    });
  },
  unfollow_user({ commit }, id) {
    return API.post("/unfollowUser", {
      userId: id
    }).then(() => {
      commit("FOLLOW_USER_PROPAGATE", id);
    });
  }
};

// getters
const getters = {
  getUser: state => id => state.users[id],
  getItem: state => id => state.items[id],
  getItems: state => ids => ids.map(id => state.items[id]),
  getSet: state => id => state.sets[id],
  getCollection: state => id => state.collections[id],
  getSizes: state => state.sizes,
  getColors: state => state.colors,
  getCountries: state => state.countries,
  getBrands: state => state.brands
};

// mutations
const mutations = {
  ADD_USER(state, user) {
    state.users[user.id] = user;
  },
  ADD_USERS(state, users) {
    users = users || [];
    users.forEach(user => {
      state.users[user.id] = user;
    });
  },
  ADD_ITEM(state, item) {
    state.items[item.id] = item;
  },
  ADD_ITEMS(state, items) {
    items = items || [];
    items.forEach(item => {
      state.items[item.id] = item;
    });
  },
  ADD_SET(state, set) {
    state.sets[set.id] = set;
  },
  ADD_SETS(state, sets) {
    sets = sets || [];
    sets.forEach(set => {
      state.sets[set.id] = set;
    });
  },
  ADD_COLLECTION(state, collection) {
    state.collections[collection.id] = collection;
  },
  ADD_COLLECTIONS(state, collections) {
    collections = collections || [];
    collections.forEach(collection => {
      state.collections[collection.id] = collection;
    });
  },
  LIKE_ITEM_PROPAGATE(state, id) {
    state.items[id].is_liked = !state.items[id].is_liked;
    state.items[id].is_liked
      ? state.items[id].likes++
      : state.items[id].likes--;
    state.items = { ...state.items };
  },
  LIKE_SET_PROPAGATE(state, id) {
    if (state.sets[id]) {
      state.sets[id].is_liked = !state.sets[id].is_liked;
      state.sets[id].is_liked ? state.sets[id].likes++ : state.sets[id].likes--;
      state.sets = { ...state.sets };
    }
  },
  LIKE_COLLECTION_PROPAGATE(state, id) {
    if (state.collections[id]) {
      state.collections[id].is_liked = !state.collections[id].is_liked;
      state.collections[id].is_liked
        ? state.collections[id].likes++
        : state.collections[id].likes--;
      state.collections = { ...state.collections };
    }
  },
  FOLLOW_USER_PROPAGATE(state, id) {
    if (state.users[id]) {
      state.users[id].is_followed = !state.users[id].is_followed;
      state.users[id] = { ...state.users[id] };
      state.users = { ...state.users };
    }
  },
  COLORS(state, colors) {
    state.colors = colors;
  },
  COUNTRIES(state, countries) {
    state.countries = countries;
  },
  SIZES(state, sizes) {
    state.sizes = sizes;
  },
  BRANDS(state, brands) {
    state.brands = brands;
  },
  FREASH_PROFILE_STATE(state, newState) {
    state._profile = newState;
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};
