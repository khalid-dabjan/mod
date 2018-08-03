<template>
  <div>
    <component :is="theComponent" :contest="contest"></component>
    <Loading v-if="loading" />
    
    <WrapperPopups v-if="$route.query.popup && $store.getters.isAuth && $route.query.popup=='image'">
          <ContestImage v-if="$route.query.popup=='image'"></ContestImage>
    </WrapperPopups>
  </div>
</template>
<script>
import DetailsNew from "./DetailsNew";
import DetailsOld from "./DetailsOld";
import ContestImage from "@/layout/popups/ContestImage"
import Loading from "@/components/Loading";
import WrapperPopups from "@/wrappers/WrapperPopups";

export default {
  components: {
    DetailsNew,
    DetailsOld,
    Loading,
    ContestImage,
    WrapperPopups
  },
  data() {
    return {
      loading: true,
      theComponent: null
    };
  },
  computed: {
    contest() {
      return this.$store.getters.contest(this.$route.params.contId);
    }
  },
  created() {
    this.$store
      .dispatch("get_contest_details", this.$route.params.contId)
      .then(() => {
        if (
          this.$store.getters.contest(this.$route.params.contId)._type == "new"
        ) {
          this.theComponent = DetailsNew;
        } else if (
          this.$store.getters.contest(this.$route.params.contId)._type == "old"
        ) {
          this.theComponent = DetailsOld;
        }
        this.loading = false;
      }).catch((err)=>{
          this.$router.push('/404');
        this.loading = false;
    });;
  },
  watch: {
    "$route.params.contId"(contId) {
      if (!contId) return;
      this.loading = true;
      this.$store.dispatch("get_contest_details", contId).then(() => {
        this.loading = false;
      }).catch((err)=>{
          this.loading = false;
          console.log('error');
      });
    }
  }
};
</script>
