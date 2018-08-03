<template>
  <div class="mycol-lg-3 mycol-md-4 mycol-sm-6">
    <div class="userCard textCentered">
      <div class="avatar"><img :src="user.photo ? user.photo.photo_name : '/images/male-user-shadow.png' " alt=""></div>
      <router-link style="margin-bottom:10px;" :to="'/profile/'+user.id" class="name">{{user.fname|| user.username}}</router-link>
      <div class="joined"></div>
      <a v-if="!user.is_blocked && user.id !== curruserId" href="#" @click.prevent="toggleFollow" class="followBtn" :class="{ 'follow': !following }">
        <i v-if="!canChange" class="fa fa-spinner fa-spin"></i>
        <span v-if="canChange">{{following ? 'Unfollow':'Follow' }}</span>
      </a>
      <router-link v-if="user.is_blocked && user.id !== curruserId" :to="'/profile/'+user.id" class="blocked" :class="{ 'follow': !following }">
        <span>{{"BLOCKED"}}</span>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  props: ["userId"],
  data() {
    return {
      following: this.$store.getters.getUser(this.userId).is_followed,
      canChange: true
    };
  },
  computed: {
    user() {
      return this.$store.getters.getUser(this.userId);
    },
    isAuth() {
      return this.$store.getters.isAuth;
    },
    curruserId() {
      return this.$store.getters.userId;
    }
  },
  watch: {
    "user.is_followed"(following) {
      console.log("CHANGED");
      this.following = following;
      this.canChange = true;
    }
  },
  methods: {
    toggleFollow() {
      if (this.isAuth) {
        if (this.canChange) {
          let action = this.following ? "unfollow_user" : "follow_user";
          this.following = !this.following;
          this.$store.dispatch(action, this.userId);
        }
        this.canChange = false;
      } else {
        this.openLogin();
      }
    },
    openLogin() {
      this.$router.push({ query: { popup: "login" } });
    }
  }
};
</script>

<style>
.blocked {
  display: block !important;
  padding: 14px;
}
.userCard .followBtn.follow {
  background: #ffbeb8;
}
</style>
