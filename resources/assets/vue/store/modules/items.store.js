import API from "../API";

const state = {
    item: {},
    home: {
        itemsMostPopular: [],
        setsBestFromCommunity: [],
        itemsLatestTrends: [],
        setsBestFromModasti: [],
        itemsBestFromModasti: [],
        homeContests: []
    },
    trending: [],
    feed: [],
    categories: [],
    mainCategories: [],
    category: {items: []},
    catIdMap: {},
    searchResults: {
        items: [],
        offset: 0
    },
    filters: {
        brands: {},
        colors: {},
        sizes: {},
        priceOrder: {}
    },
    appliedFilters: {},
    offsets: {
        category: 0,
        feed: 0,
        trending: 0
    },
    currentCategoryId: -1
};

// getters
const getters = {
    item: state => state.item,
    itemsMostPopular: state => state.home.itemsMostPopular,
    itemsLatestTrends: state => state.home.itemsLatestTrends,
    setsBestFromCommunity: state => state.home.setsBestFromCommunity,
    setsBestFromModasti: state => state.home.setsBestFromModasti,
    itemsBestFromModasti: state => state.home.itemsBestFromModasti,
    homeContests: state => state.home.homeContests,
    trending: state => state.trending,
    categories: stable => state.categories,
    mainCategories: stable => state.mainCategories,
    category: state => state.category,
    categoryItems: state => state.category.items,
    itemSearchResults: state => state.searchResults.items,
    filters: state => state.filters,
    colors: state => state.filters.colors,
    sizes: state => state.filters.sizes,
    brands: state => state.filters.brands,
    coverage: state => state.filters.coverage,
    priceOrder: state => state.filters.priceOrder,
    catIdMap: state => state.catIdMap,
};

// helper function !
const search = (searchString, offset) =>
    API.post("/search", {
        searchString: searchString,
        searchArea: "items",
        offset: offset
    });

// actions
const actions = {
    get_item({commit}, id) {
        return API.post("/itemDetails", {
            itemId: id
        }).then(res => {
            commit("ADD_ITEM", res.data.data, {root: true});
            commit("ADD_ITEMS", res.data.data.similar, {root: true});
            res.data.data.similar = res.data.data.similar.map(item => item.id);
            commit("ITEM", res.data.data);
        });
    },
    get_home_items({commit}) {
        return API.post("/home", {}).then(res => {
            let {data} = res;
            let items = {};
            commit("ADD_ITEMS", data["items_most_popular"], {root: true});
            commit("ADD_ITEMS", data["items_latest_trends"], {root: true});
            commit("ADD_ITEMS", data["items_best_from_modasti"], {root: true});
            commit("ADD_SETS", data["sets_best_from_modasti"], {root: true});
            commit("ADD_SETS", data["sets_best_from_community"], {root: true});
            data["contests"].forEach(item => {
                if (+new Date() <= +new Date(item.expires)) {
                    item._type = "new";
                } else {
                    item._type = "old";
                }
                commit("CONTESTSMAP", item, {root: true});
            });
            Object.keys(data).forEach(key => {
                items[key] = data[key].map(item => item.id);
            });
            commit("HOME_ITEMS", items);
        });
    },
    get_feed({commit, state}) {
        return API.post("/homeFeeds", {
            offset: state.offsets.feed,
            limit: 8
        }).then(res => {
            // commet feed
            commit("FEED", res.data.data);
        });
    },
    get_trending({commit, state}) {
        return API.post("/trending", {
            offset: state.offsets.trending,
            limit: 8
        }).then(res => {
            commit("ADD_ITEMS", res.data.data.items, {root: true});
            commit("TRENDING", res.data.data.items.map(item => item.id));
        });
    },
    get_categories({commit}) {
        return API.post("/getItemsCategories", {}).then(res => {
            const data= {...res.data.data};
            commit("CATEGORIES", res.data.data);
            return data;
        });
    },
    get_category_items({commit, state}, catId) {
        catId = catId || state.currentCategoryId;
        commit("CURRENT_CAT", catId);

        return API.post("/filter", {
            categoryId: catId,
            offset: state.offsets.category,
            ...state.appliedFilters
        }).then(res => {
            commit("ADD_ITEMS", res.data.data);
            commit("CATEGORY", {
                items: res.data.data.map(item => item.id),
                id: catId
            });
        });
    },
    get_category_items_by_name({commit, state, dispatch}, name) {
        let catId = state.catIdMap[name];
        if (!catId) return Promise.reject(new Error("category not found"));
        return dispatch("get_category_items", catId);
    },
    get_more_category_items({commit, state}) {
        return API.post("/filter", {
            categoryId: state.currentCategoryId,
            offset: state.offsets.category + 20,
            limit:20,
            ...state.appliedFilters
        }).then(res => {
            commit("ADD_ITEMS", res.data.data);
            commit("MORE_CATEGORY_ITEMS", res.data.data.map(item => item.id));
        });
    },
    applyFilters({commit}) {
        let getFilterd = obj =>
            Object.keys(obj)
                .map(key => {
                    return obj[key].isSelected ? obj[key] : null;
                })
                .filter(c => c)
                .map(i => i.id);
        let filter = {
            brands: getFilterd(state.filters.brands),
            colors: getFilterd(state.filters.colors),
            sizes: getFilterd(state.filters.sizes),
            coverage: getFilterd(state.filters.coverage)
        };
        if (
            state.filters.priceOrder[1].isSelected ||
            state.filters.priceOrder[2].isSelected
        ) {
            filter.orderby = "price";
            filter.order = state.filters.priceOrder[1].isSelected ? "DESC" : "ASC";
        }
        commit("APPLY_FILTERS", filter);
    },
    search_item({commit, state}, searchString) {
        return search(searchString, state.searchResults.offset).then(res => {
            var data=[...res.data.data]
            commit("ADD_ITEMS", res.data.data, {root: true});
            commit("SEARCH_RESULTS_OFFSET");
            commit("SEARCH_RESULTS", res.data.data.map(item => item.id));
            return data;
        });
    },
    search_item_autocomplete({commit, state}, searchString) {
        return search(searchString, state.searchResults.offset).then(res => {
            return res.data.data;
        });
    },
    search_item_more({commit, state}, searchString) {
        return search(searchString, state.searchResults.offset).then(res => {

            commit("ADD_ITEMS", res.data.data, {root: true});
            commit("SEARCH_RESULTS_OFFSET");
            commit("SEARCH_RESULTS_MORE", res.data.data.map(item => item.id));
        });
    },
    search_item_offset_reset({commit}) {
        commit("SEARCH_RESULTS_OFFSET_RESET");
    },
    like_item_toggle({commit}) {
        commit("LIKE_ITEM_TOGGLE");
    },
    map_filters({commit, rootGetters}) {
        let sizes = {};
        rootGetters.getSizes.forEach(size => size && (sizes[size] = {id: size}));
        let colors = {};
        rootGetters.getColors.forEach(color => (colors[color.id] = {...color }));
        let brands = {};
        rootGetters.getBrands.forEach(brand => (brands[brand.id] = {...brand} ));
        commit("MAP_FILTERS", {sizes, colors, brands});
    }
};

// mutations
const mutations = {
    ITEM(state, data) {
        state.item = data;
    },
    HOME_ITEMS({home}, data) {
        home.itemsLatestTrends = data.items_latest_trends;
        home.itemsBestFromModasti = data.items_best_from_modasti;
        home.itemsMostPopular = data.items_most_popular;
        home.setsBestFromCommunity = data.sets_best_from_community;
        home.setsBestFromModasti = data.sets_best_from_modasti;
        home.homeContests = data.contests;
    },
    TRENDING(state, data) {
        state.trending = state.trending.concat(data);
        state.offsets.trending += 8;
    },
    FEED(state, data) {
        state.feed = state.feed.concat(data);
        state.offsets.feed += 8;
    },
    CATEGORIES(state, data) {
        state.mainCategories = {...data};
        let temp = {};
        for (let key in data) {
            temp[data[key].id] = data[key];
            state.catIdMap[key.toLocaleLowerCase()] = data[key].id;
            data[key].subcategories.forEach(sub => (temp[sub.id] = sub));
            delete data[key];
        }
        state.categories = temp;
    },
    CATEGORY(state, data) {
        state.category = {
            ...state.categories[data.id],
            items: data.items
        };
    },
    MORE_CATEGORY_ITEMS(state, items) {
        state.offsets.category += 20;
        state.category.items = state.category.items.concat(items);
    },
    APPLY_FILTERS(state, filter) {
        state.appliedFilters = filter;
    },
    RESET_FILTERS(state){
        state.appliedFilters = {};
        state.filters = {
            brands: {},
            colors: {},
            sizes: {},
            priceOrder: {}
        };
    },
    SEARCH_RESULTS_OFFSET({searchResults}) {
        searchResults.offset += 8;
    },
    SEARCH_RESULTS_OFFSET_RESET({searchResults}) {
        searchResults.offset = 0;
    },
    SEARCH_RESULTS(state, data) {
        state.searchResults.items = data;
    },
    SEARCH_RESULTS_MORE(state, data) {
        state.searchResults.items = state.searchResults.items.concat(data);
    },
    MAP_FILTERS(state, filters) {
        filters.cat = -1;
        filters.priceOrder = {
            1: {id: 1, title: "From High To Low"},
            2: {id: 2, title: "From Low To High"}
        };
        filters.coverage = {
            1: {id: 1, title: "low"},
            2: {id: 2, title: "medium"},
            // 3: {id: 3, title: "high"},
            4: {id: 4, title: "full"}
        };
        state.filters = filters;
    },
    ADD_FILTER(state, payload) {
        if (!state.filters[payload.filter][payload.id]) return;
        state.filters[payload.filter][payload.id].isSelected = true;
        state.filters[payload.filter][payload.id] = {
            ...state.filters[payload.filter][payload.id]
        };
    },
    REMOVE_FILTER(state, payload) {
        if (!state.filters[payload.filter][payload.id]) return;
        state.filters[payload.filter][payload.id].isSelected = false;
        state.filters[payload.filter][payload.id] = {
            ...state.filters[payload.filter][payload.id]
        };
    },
    CHANGE_FILTER_SUB(state, id) {
        state.filters.sub = id;
    },
    LIKE_ITEM_TOGGLE(state) {
        if (state.item.title_en) {
            state.item.is_liked = !state.item.is_liked;
            state.item.is_liked ? state.item.likes++ : state.item.likes--;
            state.item = {...state.item};
        }
    },
    CURRENT_CAT(state, id) {
        state.currentCategoryId = id;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
