import Vue from "vue";
import Router from "vue-router";
import Home from "@/pages/Home";
import Category from "@/pages/Category";
import Item from "@/pages/Item";
import Set from "@/pages/Set";
import Collection from "@/pages/Collection";
import Search from "@/pages/Search";
import Single from "@/pages/Single";
import Page404 from "@/pages/404";
import Page500 from "@/pages/500";
import RetailerRegistration from "@/pages/RetailerRegistration";
import SetAdd from "@/pages/SetAdd";
import SetEdit from "@/pages/SetEdit";
import CollectionAdd from "@/pages/CollectionAdd";
import Messages from "@/pages/Messages";
import Trending from "@/pages/Trending";
import Feed from "@/pages/Feed";
import RecommendedMembers from "@/pages/RecommendedMembers";
import Notifications from "@/pages/Notifications";
// import ServerRendered from "@/pages/ServerRendered";

// Nested Routers
import StaticPagesRouter from "./static.router";
import ProfileRouter from "./profile.router";
import ContestsRouter from "./contest.router";
import RetailerRouter from "./retailer.router";

Vue.use(Router);

const router = new Router({
    routes: [
        {
            path: "/",
            name: "home",
            component: Home
        },
        {
            path: "/notifications",
            name: "user-notifications",
            component: Notifications
        },
        {
            path: "/trending",
            name: "trending",
            component: Trending,
            meta: {requiresAuth: false}
        },
        {
            path: "/feed",
            name: "feed",
            component: Feed,
            meta: {requiresAuth: true}
        },
        {
            path: "/recommended",
            name: "recommended",
            component: RecommendedMembers,
            meta: {requiresAuth: true}
        },
        {
            path: "/item/:itemId(\\d+)",
            name: "item",
            component: Item
        },
        {
            path: "/set/add",
            name: "set_add",
            component: SetAdd,
            meta: {requiresAuth: true}
        },
        {
            path: "/set/:setId/edit",
            name: "set_edit",
            component: SetEdit,
            meta: {requiresAuth: true}
        },
        {
            path: "/collection/add",
            name: "collection_add",
            component: CollectionAdd,
            meta: {requiresAuth: true}
        },
        {
            path: "/set/:setId(\\d+)",
            name: "set",
            component: Set
        },
        {
            path: "/collection/:collectionId(\\d+)",
            name: "collection",
            component: Collection
        },
        {
            path: "/search/user/:searchString",
            name: "searchInUser",
            props: {searchIn: "user"},
            component: Search
        },
        {
            path: "/search/item/:searchString",
            name: "searchInItem",
            props: {searchIn: "item"},
            component: Search
        },
        {
            path: "/page/:slug",
            name: "page",
            component: Single
        },
        {
            path: "/category/:name/:subCat(\\d+)?",
            name: "category",
            component: Category
        },
        {
            path: "/messages/:userMessagesId?",
            name: "messages",
            component: Messages
        },
        {
            path: "/partner-register",
            name: "RetailerRegistration",
            component: RetailerRegistration
        },
        {path: "/category", redirect: "/category/clothing"},
        {
            path: "/404",
            name: "404",
            component: Page404
        },
        {
            path: "/500",
            name: "500",
            component: Page500
        },
        ...ProfileRouter,
        ContestsRouter,
        RetailerRouter,
        ...StaticPagesRouter,
        {path: "**", redirect: "/404"}
    ],
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        }
        if (
            /\/contest\/s\/*/.test(to.fullPath) ||
            /^\/profile\//i.test(to.fullPath)
        ) {
            return {x: 0, y: 150};
        } else {
            return {x: 0, y: 0};
        }
    }
});

// Auth guard
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (window._store.getters.isAuth) {
            next();
        } else {
            next({
                path: "/?popup=login",
                query: {popup: "login", redirect: to.fullPath}
            });
        }
    } else {
        next(); // make sure to always call next()!
    }
});

export default router;
