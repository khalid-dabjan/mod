<template>
  <div class="gridContainer">
    <div class="followersPage secPaddLg">
      <div v-if="users.length==0" class="btn-wrapper">
        <p>No user found</p>
      </div>
      <div class="myrow clearfix">
        <UserCard v-for="user of users" :key="user" :user-id="user" />
      </div>
    </div>
    <Loading v-if="loading"/>
  </div>
</template>

<script>
import UserCard from "@/components/UserCard";
import Loading from "@/components/Loading";
export default {
  components: {
    UserCard,
    Loading
  },
  data(){
    return {
      loading: true
    }
  },
  computed:{
    users(){
      return this.$store.getters.followers;
    }
  },
  created(){
    let id =
      isNaN(this.$route.params.userId)
        ? this.$store.getters.user.userId
        :  this.$route.params.userId;
    this.$store.dispatch("get_user_followers",id).then( () => {
      this.loading = false;
    });
  }
};
</script>

