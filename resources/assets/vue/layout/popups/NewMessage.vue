<template>
  <transition name="popups" enter-active-class="animated bounceIn">
    <div>
      <div class="head">
        <span>New Message</span>
        <router-link class="head" to="?popup=">
          <span class="icon">
            <i class="fa fa-close"></i>
          </span>
        </router-link>
      </div>
      <div class="content">
        <form @submit.prevent="send" class="theForm">
          <input type="text" class="formEle" placeholder="message" v-model="message">
          <div v-for="error in errors" :key="error">
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
      message: "",
      loading: false,
      errors: []
    };
  },
  computed: {
    isLoading() {
      return this.loading ? "Loading.." : "Send";
    }
  },
  methods: {
    send() {
      if (!this.$route.query.userId) {
        this.errors.push("no user provided !");
        return;
      } else if (this.message == "") {
        this.errors.push("Text Message is required!");
        return;
      }
      this.loading = true;
      this.$store.commit("CURR_MESSAGING_USER", this.$route.query.userId);
      this.$store.dispatch("send_message", this.message).then(() => {
        this.loading = false;
        this.$router.push({ query: {} });
      });
    }
  }
};
</script>

<style>

</style>
