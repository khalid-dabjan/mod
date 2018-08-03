import Vue from "vue";
import Vuex from "vuex";
import _auth from "./modules/auth.store";
import _notification from "./modules/notifications.store";
import _profile from "./modules/profile.store";
import _sets from "./modules/sets.store";
import _items from "./modules/items.store";
import _collections from "./modules/collections.store";
import _retailer from "./modules/retailer.store";
import _contests from "./modules/contests.store";
import _feed from "./modules/feed.store";

import rootStore from "./root.store";
Vue.use(Vuex);

export default new Vuex.Store({
  ...rootStore,
  modules: {
    _auth,
    _notification,
    _profile,
    _sets,
    _items,
    _collections,
    _contests,
    _retailer,
    _feed
  }
});
