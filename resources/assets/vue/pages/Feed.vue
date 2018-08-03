<template>
  <div>
    <div class="contestTop whiteBg">
      <div class="gridContainer clearfix">
        <router-link to="/feed" class="active">Feed</router-link>
        <router-link to="/recommended">Recommended Members</router-link>
      </div>
    </div>
    <div class="gridContainer">
      <WrapperCardList title="Most Liked From Our Community" more="false" url="/trending">
        <div v-for="itemId in items" :key='itemId' class="mycol-lg-3 mycol-xs-6">
          <ItemCard :item-id="itemId" />
        </div>
      </WrapperCardList>
    </div>
  </div>
</template>

<script>
import WrapperCardList from "@/wrappers/WrapperCardList";
import ItemCard from "@/components/ItemCard";
export default {
  components: {
    WrapperCardList,
    ItemCard
  },
  data() {
    return {
      loading: true
    };
  },
  computed: {
    items() {
      return this.$store.getters.myfeed;
    }
  },
  created() {
    this.$store.dispatch("get_myfeed").then(() => {
      this.loading = false;
    });
  }
};
</script>
