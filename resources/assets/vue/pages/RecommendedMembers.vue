<template>
  <div>
    <div class="contestTop whiteBg">
      <div class="gridContainer clearfix">
        <router-link to="/feed">Feed</router-link>
        <router-link to="/recommended" class="active">Recommended Members</router-link>
      </div>
    </div>
    <div class="gridContainer">
      <div class="secPaddLg">
        <user-card v-for="userId of users" :key="userId" :user-id="userId" />
      </div>
    </div>
  </div>
</template>

<script>
import WrapperCardList from "@/wrappers/WrapperCardList";
import UserCard from "@/components/UserCard";
export default {
  components: {
    WrapperCardList,
    UserCard
  },
  data() {
    return {
      loading: true
    };
  },
  computed: {
    users() {
      return this.$store.getters.recommendedUsers;
    }
  },
  created() {
    this.$store.dispatch("get_recommended").then(() => {
      this.loading = false;
    });
  }
};
</script>

<style>

</style>
