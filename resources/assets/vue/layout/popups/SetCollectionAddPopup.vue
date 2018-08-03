<template>
  <transition name="popups" enter-active-class="animated bounceIn">
    <div>
      <div class="head">
        <span>New Set</span>
        <router-link class="head" to="?popup=">
          <span class="icon">
            <i class="fa fa-close"></i>
          </span>
        </router-link>
      </div>
      <div class="content">
        <form @submit.prevent="add" class="theForm">
          <input type="test" class="formEle" placeholder="title" v-model="formData.title" required>
          <input type="text" class="formEle" placeholder="Description" v-model="formData.description" required>
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
  props: ["submitType", "base64Img", "items", "sets", "background","me"],
  data() {
    return {
      formData: {
        title: "",
        description: "",
        data: "",
        background: this.background,
        items: this.items,
        image: this.base64Img,
        sets: this.sets
      },
      loading: false,
      errors: []
    };
  },
  methods: {
    add() {
      this.loading = true;
      this.$store
        .dispatch("add_" + this.submitType, this.formData)
        .then(res => {
          this.$router.push({
            path: "/profile/me/" + this.submitType + "s",
            query: {}
          });
          this.me.$destroy();
          // window.location.reload();
        })
        .catch(err => {
          this.errors = err.response.data.errors;
        })
        .finally(() => {
          this.loading = false;
        });
    }
  },
  computed: {
    isLoading() {
      return this.loading ? "Loading.." : "Publish";
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