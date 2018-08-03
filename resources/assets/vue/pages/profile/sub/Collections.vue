<template>
  <div class="gridContainer">
    <WrapperCardList>
      <div v-if="collections.length==0&&isMyOwnProfile" class="btn-wrapper">
        <router-link :to="'/collection/add'"  class="btn">
          Create your first collection now
        </router-link>
      </div>

      <div v-if="collections.length==0&&!isMyOwnProfile" class="btn-wrapper">
        <p>
          No collections found
        </p>
      </div>
      <div v-for="collection in collections" :key='collection' class="mycol-lg-3 mycol-sm-6">
        <CollectionCard :collection-id="collection" />
      </div>
    </WrapperCardList>
    <div v-if="collections.length % 8 === 0 && collections.length!==0" class="getMore">
      <a @click.prevent="load" href="#"> {{ loadMoreLoading ? 'Loading...' : 'More' }} </a>
    </div>
    <Loading v-if="loading" />
  </div>
</template>


<script>
import CollectionCard from "@/components/CollectionCard";
import Loading from "@/components/Loading";
import WrapperCardList from "@/wrappers/WrapperCardList";

export default {
  components: {
    CollectionCard,
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
    collections() {
      return this.$store.getters.userCollections;
    },
      isMyOwnProfile(){
        console.log( this.$store.getters.user.userId,this.$route.params.userId);
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
      return this.$store
        .dispatch("get_user_collections", Number(id))
        .then(() => {
          this.loadMoreLoading = false;
        });
    }
  }
};
</script>

