<template>
  <div class="one notifications">
    <div class="theMain">
      <i class="fa fa-bell"></i>
      <span class="numbers">{{unseenCount}}</span>
    </div>
    <div class="theNotifications">
      <div class="in">
        <div class="itsTitle clearfix">
          <span> Notifications </span>
          <span class="pull-right">
            <!-- <label class="switch">
              <input type="checkbox" checked>
              <span class="slider round"></span>
            </label> -->
          </span>
        </div>
        <div class="allNotifications">
          <a v-for="notification of notifications" :key="notification.id" @click.prevent="see(notification.id,notification.object_id,notification.action)" href="#" class="oneItem" :style="notification.seen?'background:rgb(247, 247, 247);':''">
            <img :src="notification.sender.avatar||'https://i.stack.imgur.com/1gPh1.jpg?s=328&g=1'" class="avatar" alt="">
            <!-- <img src="images/3.jpg" alt="" class="theImage"> -->
            <span class="content">
              <span class="message"> {{notification.message}} </span>
              <span class="time">{{notification.since}}</span>
            </span>
          </a>
          <br>
          <h3 v-if="!notifications.length" style="text-align:center"> No Unseen Notifications </h3>
          <br>
        </div>
        <router-link to="/notifications" class="seeAllNotifications">See All</router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  created() {
    this.$store.dispatch("update_notifications");
    setInterval(() => {
      if (this.$store.getters.isAuth)
        this.$store.dispatch("update_notifications");
    }, 45000);
  },
  computed: {
    notifications() {
      return this.$store.getters.nav;
    },
    unseenCount() {
      return this.$store.getters.unseenCount;
    }
  },
  methods: {
    see(id, objid, action) {
      this.$store.dispatch("see", id);
      this.$router.push("/" + action.split(".")[0] + "/" + objid);
    }
  }
};
</script>
