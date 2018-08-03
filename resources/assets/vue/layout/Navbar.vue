<template>
    <div>
        <div class="fixHeaderSpace"></div>
        <header id="header">
            <div class="top">
                <div class="gridContainer clearfix" :class="{'open-search':openInMobile}">
                    <router-link to="/" class="logo"><img src="/images/logo.png" alt="Modacity"></router-link>
                    <div class="rightArea">
                        <div class="userActions">
                            <span v-if="!isAuth">
                                <router-link to="?popup=signup" class="one">SIGNUP</router-link>
                                <router-link to="?popup=login" class="one">LOGIN</router-link>
                            </span>
                            <span v-if="isAuth">
                                <UserActions/>
                            </span>
                        </div>
                        <nav id="nav-button">
                            <div @click="navOpend=!navOpend" class="icon">
                                <i class="fa fa-bars"></i>
                            </div>
                        </nav>
                        <div class="search">
                            <span class="icon" @click="openInMobile=!openInMobile">
                                <i class="fa fa-search"></i>
                            </span>
                            <form @submit.prevent="searchSubmit" action="#">
                                <select v-model="area" @change="changeArea">
                                    <option value="item">Items</option>
                                    <option value="user">Users</option>
                                    <!-- <option value="2">Group</option> -->
                                </select>
                                <div class="autocomplete">
                                    <input v-model="searchString" @input="searchAutocomplete" @blur="toggleAutoomplete" @focus="toggleAutoomplete" maxlength="500" type="text">
                                    <div class="autocomplete-items" id="autocomplete-list" v-show="openAutocomplete">
                                        <div v-if="!searching" v-for="item in source" :key="item.id" @click="search(item.label)">
                                            {{item.label}}
                                        </div>
                                        <div v-if="searching" style="text-align: center">
                                            <img src="/images/loading.gif" width="15px" alt="loading">
                                        </div>
                                        <div v-if="!searching&&source.length==0&&!firsttime" style="text-align: center">
                                            No match result
                                        </div>
                                    </div>
                                </div>
                                <button type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <div class="bottom">
                <div class="gridContainer clearfix relative">
                    <nav id="nav">
                        <div @click="navOpend=!navOpend" class="icon">
                            <i class="fa fa-bars"></i>
                        </div>
                        <ul class="clearfix">
                            <li v-for="route of routes" v-if="!route.auth || isAuth " :key="route.uri">
                                <router-link active-class="active-header" :to="route.uri" exact>
                                    <i :class="route.icon"></i>
                                    <span>{{route.name}}</span>
                                </router-link>
                            </li>
                        </ul>
                    </nav>
                    <router-link :to="isAuth?'/contest':'?popup=login'" class="contest">
                        <img src="/images/new.png" alt="">
                        <span>CONTESTS</span>
                    </router-link>
                </div>
            </div>
        </header>
        <div class="mobileMenu" @click="navOpend=false" :style="'display: '+( navOpend?'block':'none')">
            <div class="in">
                <div class="nav">
                    <ul>

                        <li v-for="route of routes" v-if="!route.auth || isAuth " :key="route.uri">
                            <router-link active-class="active-header" :to="route.uri" exact>
                                <i :class="route.icon"></i>
                                <span>{{route.name}}</span>
                            </router-link>
                        </li>
                        <li>
                            <router-link :to="'/#/contest'" exact>
                                <i></i>
                                <span>Contest</span>
                            </router-link>
                        </li>
                    </ul>
                </div>
                <div class="userArea">
                    <span v-if="!isAuth">
                        <router-link to="?popup=signup" class="one">SIGNUP</router-link>
                        <router-link to="?popup=login" class="one">LOGIN</router-link>
                    </span>
                    <router-link to="/profile/me/" class="one" v-if="isAuth">MY PROFILE</router-link>
                    <a href="#" class="one" v-if="isAuth" @click="logout">LOGOUT</a>
                </div>
            </div>
        </div>
        <transition name="popups" enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
            <WrapperPopups v-if="$route.query.popup && !$store.getters.isAuth">
                <Login v-if="$route.query.popup=='login'"></Login>
                <SignUp v-if="$route.query.popup=='signup'"></SignUp>
                <Forget v-if="$route.query.popup=='forget'"></Forget>
            </WrapperPopups>

            <WrapperPopups v-if="$route.query.popup && $store.getters.isAuth && $route.query.popup=='edit_collection'">
                <SetCollectionEditPopup v-if="$route.query.popup=='edit_collection'" submitType="collection"></SetCollectionEditPopup>
            </WrapperPopups>
            <WrapperPopups v-if="$route.query.popup && $store.getters.isAuth && $route.query.popup=='join_contest'">
                <ContestUpload v-if="$route.query.popup=='join_contest'"></ContestUpload>
            </WrapperPopups>
        </transition>
        <transition name="popups" enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
            <WrapperPopups v-if="$route.query.popup && $store.getters.isAuth&&$route.query.popup=='report'">
                <Report v-if="$route.query.popup=='report'" />
            </WrapperPopups>
        </transition>
    </div>
</template>

<script>
import Login from "./popups/Login";
import SignUp from "./popups/Signup";
import Forget from "./popups/Forget";
import ContestUpload from "./popups/ContestUpload";
import SetCollectionEditPopup from "./popups/SetCollectionEditPopup";
import UserActions from "./UserActions";
import WrapperPopups from "@/wrappers/WrapperPopups";
import Report from "@/layout/popups/Report";
import routes from "./NavbarRoutes";
import _ from "lodash";
// vue Components
var $vm = null;
export default {
    components: {
        Login,
        SignUp,
        Forget,
        WrapperPopups,
        UserActions,
        SetCollectionEditPopup,
        ContestUpload,
        Report
    },
    data() {
        return {
            navOpend: false,
            routes,
            area: "item",
            searchString: "",
            source: [],
            openAutocomplete: false,
            searching: false,
            firsttime: true,
            openInMobile: false
        };
    },
    methods: {
        logout() {
            this.$store.dispatch("logout");
            this.$router.push("/");
        },
        search(search) {
            const search2 = search || this.searchString;
            if (search2.length > 0) {
                this.$router.push(`/search/${this.area}/${search2}`);
                this.searchString = search2;
                (this.area == "item"
                    ? this.$store.dispatch("search_item_offset_reset")
                    : this.$store.dispatch("search_user_offset_reset")
                ).then(() => (this.searchString = ""));
            }
        },
        searchSubmit() {
            if (this.searchString.length > 0) {
                this.$router.push(`/search/${this.area}/${this.searchString}`);
                (this.area == "item"
                    ? this.$store.dispatch("search_item_offset_reset")
                    : this.$store.dispatch("search_user_offset_reset")
                ).then(() => (this.searchString = ""));
            } else {
                this.openInMobile = false;
            }
        },
        toggleAutoomplete() {
            setTimeout(() => {
                this.openAutocomplete = !this.openAutocomplete;
            }, 300);
        },
        autoCompleteUpdate(data) {
            this.firsttime = false;
            if (this.area === "item") {
                this.source = data.map(item => {
                    return {
                        ...item,
                        label: item.title_en,
                        id: item.id + "_item"
                    };
                });
            }
            if (this.area === "user") {
                console.log("user", data);
                this.source = data.map(user => {
                    return {
                        ...user,
                        label: user.fname + " " + user.lname,
                        id: user.id + "_user"
                    };
                });
            }
        },
        searchAutocomplete: _.debounce(() => {
            if ($vm.searchString.length == 0) {
                return;
            }
            $vm.searching = true;
            if ($vm.area === "item") {
                $vm.$store
                    .dispatch("search_item_autocomplete", $vm.searchString)
                    .then(data => {
                        $vm.autoCompleteUpdate(data);
                        $vm.searching = false;
                    })
                    .catch(() => {
                        $vm.searching = false;
                    });
            }
            if ($vm.area === "user") {
                console.log("users");
                $vm.$store
                    .dispatch(
                        "search_user_autocomplete",
                        $vm.searchString,
                        true
                    )
                    .then(data => {
                        $vm.autoCompleteUpdate(data);
                        $vm.searching = false;
                    })
                    .catch(() => {
                        $vm.searching = false;
                    });
            }
        }, 500),
        changeArea() {
            if (this.searchString) {
                if (
                    this.$route.name === "searchInUser" &&
                    this.area === "item"
                ) {
                    this.$router.push(
                        `/search/${this.area}/${this.searchString}`
                    );
                    return;
                }
                if (
                    this.$route.name === "searchInItem" &&
                    this.area === "user"
                ) {
                    this.$router.push(
                        `/search/${this.area}/${this.searchString}`
                    );
                    return;
                }
            }
            this.searchAutocomplete();
        }
    },
    computed: {
        isAuth() {
            return this.$store.getters.isAuth;
        }
    },
    created() {
        $vm = this;
    }
};
</script>

<style>
.active-header {
    border-bottom-color: #ffbeb8 !important;
}

.popupPage {
    z-index: 50;
}

.mobileMenu .in {
    overflow: scroll;
}

#header .top .rightArea .search form select {
    text-transform: uppercase;
}

#header .top .rightArea .search form select:hover {
    color: #df6262;
    cursor: pointer;
}
</style>
