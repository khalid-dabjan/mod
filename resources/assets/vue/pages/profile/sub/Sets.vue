<template>
  <div class="gridContainer">
    <WrapperCardList>
      <div v-if="sets.length==0&&isMyOwnProfile" class="btn-wrapper">
        <router-link :to="'/set/add'"  class="btn">
          Create your first set now
        </router-link>
      </div>
      <div v-if="sets.length==0&&!isMyOwnProfile" class="btn-wrapper">
          <p>No sets found</p>
      </div>
      <div v-for="set in sets" :key='set' class="mycol-lg-3 mycol-sm-6">
        <SetCard :set-id="set" />
      </div>
    </WrapperCardList>
    <div v-if="sets.length % 8 === 0 && sets.length!==0" class="getMore">
      <a @click.prevent="load" href="#"> {{ loadMoreLoading ? 'Loading...' : 'More' }} </a>
    </div>
    <Loading v-if="loading" />
  </div>
</template>

<script>
import SetCard from "@/components/SetCard";
import Loading from "@/components/Loading";
import WrapperCardList from "@/wrappers/WrapperCardList";

export default {
  components: {
    SetCard,
    WrapperCardList,
    Loading
  },
  data() {
    return {
      loading: true,
      loadMoreLoading: false
    };
  },
  computed: {
    sets() {
      return this.$store.getters.userSets;
    },
      isMyOwnProfile(){
          return (
              this.$store.getters.user.userId == this.$route.params.userId ||
              this.$route.params.userId == "me"
          );
      }
  },
  created() {
    this.load().then(() => {
      this.loading = false;
    });
  },
  methods: {
    load() {
      this.loadMoreLoading = true;
      let id = isNaN(this.$route.params.userId)
        ? this.$store.getters.user.userId
        : this.$route.params.userId;
      return this.$store.dispatch("get_user_sets", id).then(() => {
        this.loadMoreLoading = false;
      });
    }
  }
};
</script>
