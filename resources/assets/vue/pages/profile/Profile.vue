<template>
  <div id="profile">
    <div class="secPaddLg whiteBg">
      <div class="gridContainer">
        <div class="top_userProfile clearfix">
          <div class="avatar profile-after"><img :src="user.photo?user.photo.photo_name:'images/male-user-shadow.png'" alt=""></div>
          <div class="content">
            <div class="info">
              <div class="name">{{user.fname}}</div>
              <div class="other"> <span class="suboth0" > {{user.profession||''}} </span> <br/> <span class="suboth1" >  {{user.about}} </span> </div>
            </div>
            <div class="top_message">
              <div>Create 15 sets to become a STYLIST!</div>
              <hr>
              <div>{{user.sets_count||0}} / 15 Sets</div>
            </div>
          </div>
        </div>
        <router-link v-if="isCurrUser" to="/profile/edit" class="topHeadBtn">Edit Profile</router-link>
        <a v-if="!user.is_blocked && !isCurrUser" href="#" @click.prevent="toggleFollow" class="topHeadBtn followBtn" :class="{ 'follow': !user.is_followed }">
          <i v-if="!canChange" class="fa fa-spinner fa-spin"></i>
          <span v-if="canChange">{{user.is_followed ? 'unfollow':'follow' }}</span>
        </a>
        <div v-if="!isCurrUser" class="TUP_otherBtns">
          <a href="#" :disabled="blockBtnLoading" @click.prevent="toggleBlock" class="mainBtn">{{ blockBtnLoading ? "Loading..":user.is_blocked ? "Unblock":"Block"}}</a>
          <router-link :to="'?popup=new_message&userId='+user.id" class="mainBtn">send Message</router-link>
        </div>
      </div>
      <Loading v-if="loading" />
    </div>
    <ProfileNav/>
    <div v-if="!loading">
      <transition name="subpage" enter-active-class="animated fadeIn">
        <keep-alive>
          <router-view></router-view>
        </keep-alive>
      </transition>
    </div>
    <transition name="popup_new_message" enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
      <WrapperPopups v-if="$route.query.popup && $route.query.popup=='new_message'">
        <NewMessage v-if="$route.query.popup=='new_message'" />
      </WrapperPopups>
    </transition>
  </div>
</template>

<script>
import ProfileNav from "./ProfileNav";
import Loading from "@/components/Loading";
import WrapperPopups from "@/wrappers/WrapperPopups";
import NewMessage from "@/layout/popups/NewMessage";

export default {
  components: {
    ProfileNav,
    Loading,
    NewMessage,
    WrapperPopups
  },
  data() {
    return {
      loading: true,
      blockBtnLoading: false,
      // following: false,
      canChange: true
    };
  },
  computed: {
    user() {
      return this.$store.getters.userProfile;
    },
    isCurrUser() {
      return (
        this.$store.getters.user.userId == this.$route.params.userId ||
        this.$route.params.userId == "me"
      );
    },
    isAuth() {
      return this.$store.getters.isAuth;
    }
  },
  created() {
    this.load(this.$route.params.userId);
  },
  watch: {
    "$route.params.userId"(userId) {
      this.load(userId);
    }
  },
  methods: {
    load(userId) {
      this.loading = true;
      let id = isNaN(userId) ? this.$store.getters.user.userId : userId;
      this.$store
        .dispatch("get_user_profile", id)
        .then(() => {
          this.loading = false;
        })
        .catch(err => {
          console.log(err);

          this.$router.push("/404");
        });
    },
    toggleBlock() {
      this.blockBtnLoading = true;
      this.$store.dispatch("toggle_block").then(() => {
        this.blockBtnLoading = false;
        this.$router.push("/profile/me/blocked");
      });
    },
    toggleFollow() {
      if (this.isAuth) {
        if (this.canChange) {
          this.canChange = false;
          let action = this.user.is_followed ? "unfollow_user" : "follow_user";
          this.user.is_followed = !this.user.is_followed;
          this.$store.dispatch(action, this.$route.params.userId).then(() => {
            this.canChange = true;
          });
        }
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

<style scoped>
.follow {
  background: #ffbeb8;
}
.profile-after img {
  width: 100%;
  height: 100%;
}

.suboth1{
  font-size: 1em;
  font-weight: 300;
  color:#555454;
}
.suboth0
{
  font-size: 1.3em;
  font-weight: 900;
  color:#3b3b3b;
}
</style>

