<template>
  <transition name="popups" enter-active-class="animated bounceIn">
    <div>
      <div class="head">
        <span>Sign Up</span>
        <router-link class="head" to="?popup=">
          <span class="icon">
            <i class="fa fa-close"></i>
          </span>
        </router-link>
      </div>
      <div v-if="done" class="content">
        <h1 style="font-size: 2em; padding:20px;" >Thank you</h1>
        <h3 style="padding:20px;" >Please check your email to verify your account, then <router-link class="mainBtn" to="?popup=login" >LOGIN</router-link></h3>
      </div>
      <div v-else class="content">
        <form @submit="signUp" class="theForm">
          <input type="text" class="formEle" placeholder="Full Name" v-model="name" required>
          <input type="email" class="formEle" placeholder="Email" v-model="email" required>
          <input type="password" class="formEle" placeholder="Password" v-model="password" required>
          <input type="password" class="formEle" placeholder="Repeat Password" v-model="password2" required>
          <div v-for="(error,i) in errors" :key="i">
            <h4 class="errors">
              {{error}}
            </h4>
            <br/>
          </div>
          <input type="submit" :disabled="loading" class="formEle btn" :value="isLoading">
        </form>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  data() {
    return {
      name: "",
      email: "",
      password: "",
      password2: "",
      loading: false,
      errors: [],
      done: false
    };
  },
  methods: {
    signUp(e) {
      e.preventDefault();
      this.loading = true;
      if (this.password == this.password2 && this.password.length >= 6) {
        this.$store
          .dispatch("register", {
            email: this.email,
            password: this.password,
            name: this.name
          })
          .then(errors => {
            if (errors.length == 0) this.done = true;
            else this.errors = errors;
          })
          .finally(() => {
            this.loading = false;
          });
      } else {
        this.errors = [];
        if (this.password != this.password2)
          this.errors.push("Passwords not matching");
        if (this.password.length < 6)
          this.errors.push(
            "Minimum Password length is 6 characters or numbers"
          );
        this.loading = false;
      }
    }
  },
  computed: {
    isLoading() {
      return this.loading ? "Loading.." : "Sign Up";
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