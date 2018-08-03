import API from "../API";

const state = {
  contests: {
    new: [],
    old: []
  },
  contestPhotos: [],
  offset: 0,
  contestsMap: {},
  contestComments: []
};

// getters
const getters = {
  newContests: state => state.contests.new,
  oldContests: state => state.contests.old,
  contest: state => id => state.contestsMap[id],
  contestComments: state => state.contestComments,
  contestPhotos: state => state.contestPhotos
};

// actions
const actions = {
  get_all_contests({ commit }) {
    // missing catch
    return API.post("/getContests", {
      contestId: 0,
      imageName: "string",
      image: "string"
    }).then(res => {
      let _new = [];
      let _old = [];
      res.data.forEach(item => {
        if (+new Date() <= +new Date(item.expires)) {
          item._type = "new";
          _new.push(item.id);
        } else {
          item._type = "old";
          _old.push(item.id);
        }
        commit("CONTESTSMAP", item);
      });
      commit("NEW_CONTESTS", _new);
      commit("OLD_CONTESTS", _old);
    });
  },
  get_contest_details({ commit, dispatch, state }, contestId) {
    return API.post("/getContestPhotos", {
      contestId: contestId,
      offset: 0
    }).then(res => {
      commit("CONTESTPHOTOS", { photos: res.data, contestId });
    });
  },
  get_more_photos({ commit, dispatch, state }, contestId) {
    return API.post("/getContestPhotos", {
      contestId: contestId,
      offset: state.offset
    }).then(res => {
      commit("MORE_CONTESTPHOTOS", { photos: res.data, contestId });
    });
  },
  join_contest({ commit }, payload) {
    return API.post("/publishContestPhoto", payload);
  },
  like_contest({ commit, dispatch }, objId) {
    return API.post("/switchLike", {
      objId,
      targetObject: "contest"
    }).then(() => {
      commit("LIKE_CONTEST_PROPAGATE", objId);
    });
  },
  like_contest_toggle() {},
  like_contest_item({ commit, dispatch }, objId) {
    return API.post("/switchLike", {
      objId,
      targetObject: "contest_item"
    }).then(() => {
      commit("LIKE_CONTEST_ITEM_PROPAGATE", objId);
    });
  },
  like_contest_item_toggle() {},
  get_contest_comments({ commit }, contestId) {
    return API.post("/getContestComments", {
      contestId,
      limit: 30
    }).then(res => {
      commit("CONTEST_COMMENTS", res.data.data.comments);
    });
  },
  add_comment_to_contest({ commit, dispatch }, payload) {
    return API.post("/addCommentToContest", {
      contestId: payload.contestId,
      text: payload.text
    }).then(() => dispatch("get_contest_comments", payload.contestId));
  },
  delete_comment_from_contest({ commit, dispatch }, { contestId, commentId }) {
    return API.post("/deleteContestComment", {
      commentId
    }).then(() => dispatch("get_contest_comments", contestId));
  }
};

// mutations
const mutations = {
  NEW_CONTESTS(state, data) {
    state.contests.new = data;
  },
  OLD_CONTESTS(state, data) {
    state.contests.old = data;
  },
  CONTESTPHOTOS(state, data) {
    state.offset += 8;
    state.contestPhotos = data.photos;
  },
  MORE_CONTESTPHOTOS(state, data) {
    state.offset += 8;
    state.contestPhotos = state.contestPhotos.concat(data.photos);
  },
  CONTESTSMAP(state, item) {
    state.contestsMap[item.id] = item;
  },
  LIKE_CONTEST_PROPAGATE(state, id) {
    state.contestsMap[id].is_liked
      ? state.contestsMap[id].likes--
      : state.contestsMap[id].likes++;
    state.contestsMap[id].is_liked = !state.contestsMap[id].is_liked;
    state.contestsMap = { ...state.contestsMap };
    state.contestsMap[id] = { ...state.contestsMap[id] };
  },
  LIKE_CONTEST_ITEM_PROPAGATE(state, id) {
    state.contestPhotos.forEach(photo => {
      if (photo.id == id) {
        photo.is_liked ? photo.likes-- : photo.likes++;
        photo.is_liked = !photo.is_liked;
      }
    });
  },
  CONTEST_COMMENTS(state, data) {
    state.contestComments = data;
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};
