import API from "../API";

const state = {
  myfeed: [],
  recommendedUsers: [],
  offsets: {
    myfeed: 0,
    recommendedUsers: 0
  }
};

// getters
const getters = {
  myfeed: state => state.myfeed,
  recommendedUsers: state => state.recommendedUsers
};

// actions
const actions = {
  get_myfeed({ commit, state }) {
    return API.post("/feed", {
      offset: state.offsets.myfeed,
      limit: 8
    }).then(res => {
      commit("ADD_ITEMS", res.data.data.items, { root: true });
      commit("MYFEED", res.data.data.items.map(i => i.id));
    });
  },
  get_recommended({ commit, state }) {
    return API.post("/recommendedUser", { limit: 8 }).then(res => {
      commit("ADD_USERS", res.data.data, { root: true });
      commit("RECOMMENDED", res.data.data.map(u => u.id));
    });
  }
};

// mutations
const mutations = {
  MYFEED(state, data) {
    state.myfeed = state.myfeed.concat(data);
    state.offsets.myfeed += 8;
  },
  RECOMMENDED(state, data) {
    state.recommendedUsers = state.recommendedUsers.concat(data);
    state.offsets.recommendedUsers += 8;
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};
