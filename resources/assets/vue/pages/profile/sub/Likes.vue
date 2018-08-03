<template>
  <div class="gridContainer">
    <div class="secPaddMd">
      <div class="myrow clearfix">
        <div v-if="likedItems.length==0&&likedSets.length==0" class="btn-wrapper">
          <router-link v-if="isMyOwnProfile" :to="'/category/clothing'"  class="btn">
            Explore Modasti
          </router-link>

          <p v-if="!isMyOwnProfile">No found Data</p>
        </div>
      </div>
    </div>
    <WrapperCardListTitled v-if="likedItems.length > 0" title="Liked Items" url="#" :more="'false'">
      <div v-for="item in likedItems" :key='item' class="mycol-lg-3 mycol-sm-6">
        <ItemCard :item-id="item" />
      </div>
    </WrapperCardListTitled>
    <div v-if="likedItems.length % 8 === 0 && likedItems.length !== 0" class="getMore">
      <a @click.prevent="loadmoreItems" href="#"> {{ loadMoreLoading ? 'Loading...' : 'More' }} </a>
    </div>
    <WrapperCardListTitled v-if="likedSets.length > 0" title="Liked Sets" url="#" :more="'false'">
      <div v-for="set in likedSets" :key="set" class="mycol-lg-3 mycol-sm-6">
        <SetCard :set-id="set" />
      </div>
    </WrapperCardListTitled>
    <div v-if="likedSets.length % 8 === 0 && likedSets.length!==0" class="getMore">
      <a @click.prevent="loadmoreSets" href="#"> {{ loadMoreLoading ? 'Loading...' : 'More' }} </a>
    </div>
    <br>
    <br>
    <Loading v-if="loading" />
  </div>
</template>

<script>
import ItemCard from "@/components/ItemCard";
import SetCard from "@/components/SetCard";
import Loading from "@/components/Loading";
import WrapperCardListTitled from "@/wrappers/WrapperCardListTitled";
import { mapGetters } from "vuex";

export default {
  components: {
    ItemCard,
    SetCard,
    Loading,
    WrapperCardListTitled
  },
  data() {
    return {
      id: null,
      loading: true,
      loadMoreLoading: false
    };
  },
  computed: {
    ...mapGetters(["likedItems", "likedSets", "likedCollections"]),
      isMyOwnProfile(){
          return (
              this.$store.getters.user.userId == this.$route.params.userId ||
              this.$route.params.userId == "me"
          );
      }
  },
  created() {
    let id = isNaN(this.$route.params.userId)
      ? this.$store.getters.user.userId
      : this.$route.params.userId;
    Promise.all([
      this.$store.dispatch("get_user_liked_items", id),
      this.$store.dispatch("get_user_liked_sets", id)
      //  this.$store.dispatch("get_user_liked_collections",id)
    ]).finally(() => {
      this.loading = false;
    });
  },
  methods: {
    loadmoreItems() {
      this.loadMoreLoading = true;
      this.$store
        .dispatch("get_user_liked_items", this.id)
        .then(() => (this.loadMoreLoading = false));
    },
    loadmoreSets() {
      this.loadMoreLoading = true;
      this.$store
        .dispatch("get_user_liked_sets", this.id)
        .then(() => (this.loadMoreLoading = false));
    }
  }
};
</script>
