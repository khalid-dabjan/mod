<template>
  <transition name="popups" enter-active-class="animated bounceIn">
    <div>
      <div class="head">
        <span>login</span>
        <router-link class="head" to="?popup=">
          <span class="icon">
            <i class="fa fa-close"></i>
          </span>
        </router-link>
      </div>
      <div class="content">
        <form @submit.prevent="submit" class="theForm">
          <input type="text" maxlength="200" class="formEle" placeholder="Title" v-model="form.imageName" required>
          <label for="uploadImg" class="inputEle brandBg vCenter textCentered mrgBtmLg"> Upload Image </label>
          <input type="file" id="uploadImg" class="disNone" @change="processFile($event)">
          <div class="uploadedPhotoDisplay mrgBtmLg">
            <span v-if="form.image === ''" class="fontLarger grayColor hideAfterUpload">No photo</span>
            <img :src="form.image" style="min-width:250px; max-width:300px" alt="">
          </div>
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
      form: {
        image: "",
        imageName: "",
        contestId: this.$route.query.contestId
      },
      success: false,
      loading: false,
      errors: []
    };
  },
  methods: {
    submit() {
      this.loading = true;
      this.$store
        .dispatch("join_contest", this.form)
        .then(res => {
          this.loading = false;
          this.$router.push("/contest");
          window.location.reload();
        })
        .catch(err => {
          this.errors = err.response.data.errors;
          this.loading = false;
        });
    },
    processFile(e) {
      let input = e.target;
      if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = e => {
          this.form.image = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
  },
  computed: {
    isLoading() {
      return this.loading ? "Loading.." : "Submit";
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