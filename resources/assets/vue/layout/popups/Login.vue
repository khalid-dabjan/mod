<template>
    <transition name="popups" enter-active-class="animated bounceIn">
        <div>
            <div class="head">
                <span>Login</span>
                <router-link class="head" to="?popup=">
          <span class="icon">
            <i class="fa fa-close"></i>
          </span>
                </router-link>
            </div>
            <div class="content">
                <form @submit="login" class="theForm">
                    <input type="email" class="formEle" placeholder="Email" v-model="email" required>
                    <input type="password" class="formEle" placeholder="Password" v-model="password" required>
                    <div v-for="error in errors" :key="error">
                        <h4 class="errors">
                            {{error}}
                        </h4>
                        <br/>
                    </div>
                    <input type="submit" :disabled="loading" class="formEle btn" :value="isLoading">
                </form>
                <div class="otherLinks">
                    <div class="one">Forgot Password?
                        <router-link to="?popup=forget">Get New</router-link>
                    </div>
                    <div class="one">Not Registered?
                        <router-link :to="'?popup=signup&redirect='+$route.query.redirect">Sign Up</router-link>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        data() {
            return {
                email: "",
                password: "",
                loading: false,
                errors: []
            };
        },
        methods: {
            login(e) {
                e.preventDefault();
                this.loading = true;
                this.$store
                    .dispatch("login", {
                        email: this.email,
                        password: this.password
                    })
                    .then(data => {
                        const errors = data.errors;
                        console.log("WTFFF",data);
                        if (data.errors.length == 0) {
                            this.$router.push({path: this.$route.query.redirect, query: {}});
                            this.$router.go(this.$router.currentRoute);
                            console.log(data.last_login);
                            if(data.last_login===null){
                                this.$router.push({ query: {
                                    'popup':'firsttime'
                                    }});
                            }
                        } else this.errors = errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        },
        computed: {
            isLoading() {
                return this.loading ? "Loading.." : "login";
            }
        }
    };
</script>


<style scoped>
    input:invalid {
        background-color: #fff;
    }

    .errors {
        font-family: "Cheque-Black";
        color: RED;
    }
</style>