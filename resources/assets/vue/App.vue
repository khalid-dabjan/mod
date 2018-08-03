<template>
    <div>
        <app-navbar/>
        <div id="pageContent">
            <transition name="paget" enter-active-class="animated fadeIn">
                <router-view/>
            </transition>
        </div>
        <app-footer/>
    </div>
</template>

<script>
    import Navbar from "./layout/Navbar.vue";
    import Footer from "./layout/Footer.vue";

    export default {
        components: {
            "app-navbar": Navbar,
            "app-footer": Footer
        },
        mounted() {
            console.log("Vue App Mounted 3!");
        }
    };
</script>

<style>
    @import "https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css";

    #pageContent {
        min-width: 99vw;
        min-height: 100vh;
    }

    .productCard .avatar {
        height: 380px;
    }

    .topCategories a {
        opacity: 0.5;
    }

    @media only screen and (max-width: 1350px) {
        #header .bottom #nav ul {
            max-width: 90%;
        }
    }

    .btn {
        padding: 20px;
        background-color: #fbbeb9;
    }

    .btn-wrapper {
        text-align: center;
    }

    .createSetPage .rightArea .theProducts .one .name {
        height: 50px;
    }

    .createSetPage .rightArea .theProducts {
        height: 800px !important;
    }
</style>
