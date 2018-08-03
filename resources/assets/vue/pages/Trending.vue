<template>
  <div>
    <div class="secPaddLg whiteBg textCentered">
      <div class="gridContainer">
        <div class="sectionName">
          <div class="theName">Trending</div>
        </div>
      </div>
    </div>
    <div class="gridContainer">
      <WrapperCardList title="Latest Trends" url="#">
        <div v-for="itemId in trending" :key='itemId' class="mycol-lg-3 mycol-xs-6">
          <ItemCard :item-id="itemId" />
        </div>
      </WrapperCardList>
      <div v-if="trending.length % 8 === 0" class="getMore">
        <a @click.prevent="loadmore" href="#"> {{ loadMoreLoading ? 'Loading...' : 'More' }} </a>
      </div>
      <br>
      <br>
    </div>

    <Loading v-if="loading" />
  </div>

</template>

<script>
import Loading from "@/components/Loading";
import WrapperCardList from "@/wrappers/WrapperCardList";
import ItemCard from "@/components/ItemCard";

export default {
  components: {
    Loading,
    WrapperCardList,
    ItemCard
  },
  created() {
    this.$store.dispatch("get_trending").then(() => (this.loading = false));
  },
  data() {
    return {
      loading: true,
      loadMoreLoading: false
    };
  },
  computed: {
    trending() {
      return this.$store.getters.trending;
    }
  },
  methods: {
    loadmore() {
      this.loadMoreLoading = true;
      this.$store
        .dispatch("get_trending")
        .then(() => (this.loadMoreLoading = false));
    }
  }
};
</script>
