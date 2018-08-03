<template>
  <transition name="popups" enter-active-class="animated bounceIn">
    <div>
      <div class="head">
        <span>Edit {{submitType}}</span>
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
  props: ["submitType",'title_en','description_en'],
  data() {
    return {
      formData: {
        setId: this.$route.query.setId,
        collectionId: this.$route.query.collectionId,
        title: this.title_en? this.title_en:'',
        description:  this.description_en? this.description_en:'',
      },
      loading: false,
      errors: []
    };
  },
  methods: {
    add() {
      this.loading = true;
      this.$store
        .dispatch("edit_" + this.submitType, this.formData)
        .then(res => {
          this.$router.push({
            path: "/profile/me/" + this.submitType + "s",
            query: {}
          });
          window.location.reload();
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