<template>
  <div class="gridContainer">
    <div v-if="!loading">
      <div class="proudctDetails">
        <div class="avatar"><img v-if="data.photos" :src="data.photos[0].photo_name" alt=""></div>
        <div class="content">
          <div class="in">
            <div class="paging">
              <router-link  :to="'/category/'+data.categories[0].name.toLowerCase()">{{data.categories[0].name}}</router-link>
              <router-link  :to="'/category/'+data.categories[0].name.toLowerCase()+'/'+data.categories[1].id">{{data.categories[1].name}}</router-link>
            </div>
            <h2 class="title">{{data.title_en}}</h2>
              <router-link class="link" :to="(data.url_en?data.url_en :'/profile/'+data.user_id)">{{data.brand}}</router-link>
            <div v-html="data.text_en" class="description"></div>
            <div class="info clearfix">
              <div class="price">{{data.price}} {{getCurrencySymbolItem(data.currency)}}</div>
              <!--<router-link class="item-buy" :to="">Buy</router-link>-->
              <a :href="(data.url_en?data.url_en :'#')" class="item-buy">Buy</a>
            </div>
            <CardActions :is-liked="data.is_liked" :obj-id="data.id" :context="'item'" :likebale="true" :num-of-likes="data.likes" :sharable="true" />
          </div>
        </div>
      </div>
      <WrapperCardListTitled title="Similar" url="#" more="false">
        <div v-for="item in data.similar" :key='item' class="mycol-lg-3 mycol-xs-6">
          <ItemCard :item-id="item" />
        </div>
      </WrapperCardListTitled>
    </div>
    <Loading v-if="loading" />
  </div>
</template>

<script>
import WrapperCardListTitled from "@/wrappers/WrapperCardListTitled";
import ItemCard from "@/components/ItemCard";
import CardActions from "@/components/CardActions";
import Loading from "@/components/Loading";
import {getCurrencySymbol} from "@/pages/retailer/sub/currency.js"
export default {
  components: {
    WrapperCardListTitled,
    ItemCard,
    CardActions,
    Loading
  },
  data() {
    return {
      loading: true,
    };
  },
  created() {
    this.loading = true;
    this.$store
      .dispatch("get_item", this.$route.params.itemId)
      .then(() => (this.loading = false))
      .catch(() => {
        this.loading = false;
        if (this.$store.getters.isAuth) this.$router.replace({ path: "/404" });
      });
  },
    methods:{
        getCurrencySymbolItem(currency){
            return   getCurrencySymbol(currency)
        }
    },
  watch: {
    "$route.params.itemId"(itemId) {
      if (!itemId) return;
      this.loading = true;
      this.$store
        .dispatch("get_item", itemId)
        .then(() => (this.loading = false))
        .catch(err => {
          console.error(err);
          this.loading = false;
          this.$router.replace({ path: "/404" });
        });
    }
  },
  computed: {
    data() {
      return this.$store.getters.item;
    },
  }
};
</script>


<style scoped>
.avatar {
  max-height: 600px;
}
</style>
