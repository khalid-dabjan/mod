<template>
  <div>
    <div v-html="htmlPage" class="from-server" />
    <Loading v-if="loading" />
  </div>
</template>

<script>
// %%%%%%%%%%%%%%%%%% DEPRECATED %%%%%%%%%%%%%%%5
// import Loading from "@/components/Loading";
// import axios from "axios";
// export default {
//   components: {
//     Loading
//   },
//   data() {
//     return {
//       htmlPage: "",
//       loading: true
//     };
//   },
//   created() {
//     axios
//       .get("http://127.0.0.1:8000/api/" + this.$route.params.path, {
//         headers: { Authorization: `Bearer ${this.$store.getters.api_token}` }
//       })
//       .then(res => {
//         this.htmlPage = res.data;
//         this.loading = false;
//       });
//   },
//   watch: {
//     "this.$route.params.path"(path) {
//       this.loading = true;
//       axios
//         .get("http://127.0.0.1:8000/" + path, {
//           headers: { Authorization: `Bearer ${this.$store.getters.api_token}` }
//         })
//         .then(res => {
//           this.htmlPage = res.data;
//           this.loading = false;
//         });
//     }
//   }
// };
</script>

