<template>
  <div id="category">
    <div class="topCategories whiteBg">
      <div v-if="category" class="gridContainer">
        <router-link v-for="sub of category.subcategories" :key="sub.id" :to="category.title.toLowerCase()+'/'+sub.id" active-class="curr">{{sub.title}}</router-link>
      </div>
    </div>
    <div class="gridContainer">
      <ClothingFilter/>
      <WrapperCardList v-if="category">
        <div v-for="item in categoryItems" :key='item' class="mycol-lg-3 mycol-xs-6">
          <ItemCard :item-id="item" />
        </div>
      </WrapperCardList>
      <div v-if="categoryItems.length % 8 === 0 && categoryItems.length !== 0" class="getMore">
        <a @click.prevent="loadmore" href="#"> {{ loadMoreLoading ? 'Loading...' : 'More' }} </a>
      </div>
      <br>
      <br>
    </div>
    <Loading v-if="loading" />
  </div>
</template>

<script>
import ItemCard from "@/components/ItemCard";
import ClothingFilter from "@/components/ClothingFilter";
import Loading from "@/components/Loading";
import WrapperCardList from "@/wrappers/WrapperCardList";
import { mapGetters } from "vuex";

export default {
  components: {
    ItemCard,
    WrapperCardList,
    ClothingFilter,
    Loading
  },
  data() {
    return {
      loading: true,
      loadMoreLoading:false
    };
  },
  computed: {
    ...mapGetters(["categoryFiltered", "category", "categoryItems"]),
    subId() {
      return this.$store.getters.filters.sub;
    }
  },
  created() {
      var cats_id=[this.$store.getters.catIdMap[this.$route.params.name]];
      if(this.$route.params.subCat){
          cats_id=[(parseInt(this.$route.params.subCat))];
      }
      Promise.all([
          this.$store.dispatch("get_colors",true),
          this.$store.dispatch("get_brands",cats_id),
          this.$store.dispatch("get_sizes")
      ]).then(() => {
          this.loading = false;
          this.$store.dispatch("map_filters");
      });
    this.$store.dispatch("get_categories").then(() => {
      this.loadItems(this.$route.params.name,this.$route.params.subCat).then(() => {
        });
    });

  },
  watch: {
    "$route.params"({name,subCat}) {
      if (!name) return;
      this.$store.commit("RESET_FILTERS");
      this.$store.dispatch("map_filters");
      this.loadItems(name,subCat);
        var cats_id=[this.$store.getters.catIdMap[this.$route.params.name]];
        if(this.$route.params.subCat){
            cats_id=[(parseInt(this.$route.params.subCat))];
        }
        Promise.all([
            this.$store.dispatch("get_colors",true),
            this.$store.dispatch("get_brands",cats_id),
            this.$store.dispatch("get_sizes")
        ]).then(() => {
            this.loading = false;
            this.$store.dispatch("map_filters");
        });
    }
  },
  methods: {
    loadmore() {
      this.loadMoreLoading = true;
      this.$store
        .dispatch("get_more_category_items")
        .then(() => (this.loadMoreLoading = false));
    },
    loadItems(name,subCat) {
      this.loading = true;
      if(subCat){
        return this.$store
        .dispatch("get_category_items", subCat)
        .then(() => {
          this.loading = false;
        })
        .catch(err => {
          this.$router.push("/404");
          console.error(err);
        });
      }
      else{
        return this.$store
        .dispatch("get_category_items_by_name", name)
        .then(() => {
          this.loading = false;
        })
        .catch(err => {
          this.$router.push("/404");
          console.error(err);
        });
      }
    },
    
  }
};
</script>

<style scoped>
.curr {
  color: red;
}
</style>