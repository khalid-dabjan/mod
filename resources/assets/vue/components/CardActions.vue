<template>
  <div class="likesAndComments">
    <transition name="actions" enter-active-class="animated bounceInLeft" mode="out-in">
      <span key="actions" v-if="!shareActions">
        <a @click.prevent="toggleLike" href="#">
          <transition enter-active-class="animated bounceIn" mode="out-in">
            <i key="liked" class="fa fa-heart" v-if="liked&&canChange"></i>
            <i key="notLiked" class="fa fa-heart-o" v-if="!liked&&canChange"></i>
            <i key="load" v-if="!canChange" class="fa fa-spinner fa-spin"></i>
          </transition>
          <span>{{numOfLikes}}</span>
        </a>
        <router-link v-if="commentable" :to="this.isAuth ? commentUrl||'#':'?popup=login'">
          <i class="icon-comment"></i>
          <span>{{numOfComments}}</span>
        </router-link>
      </span>
      <span key="shares" v-if="shareActions">
        <a target="_blank" :href="facebook">
          <i class="fa fa-facebook"></i>
        </a>
        <a target="_blank" :href="twitter">
          <i class="fa fa-twitter"></i>
        </a>
        <a target="_blank" :href="pinterest">
          <i class="fa fa-pinterest"></i>
        </a>
      </span>
    </transition>
    <a v-if="sharable" @click.prevent="toggleShareActions" href="#">
      <i class="fa fa-share-alt"></i>
    </a>
  </div>
</template>

<script>
export default {
  props: [
    "sharable",
    "likable",
    "commentable",
    "numOfLikes",
    "numOfComments",
    "objId",
    "context",
    "isLiked",
    "commentUrl",
    "parentContext",
    "parentId",
      "noWaitAction",
      "callback"
  ],
  data() {
    return {
      shareActions: false,
      liked: this.isLiked,
      canChange: true
    };
  },
  computed: {
    facebook() {
      return this.shareLink("https://www.facebook.com/sharer/sharer.php?u=");
    },
    twitter() {
      return this.shareLink("http://twitter.com/share?text=");
    },
    pinterest() {
      return this.shareLink("http://pinterest.com/pin/create/button/?url=");
    },
    isAuth() {
      return this.$store.getters.isAuth;
    }
  },
  methods: {
    toggleLike() {
      if (this.isAuth) {
        if (this.canChange) {
          this.liked = !this.liked;
          this.$store.dispatch("like_" + this.context, this.objId);
          this.$store.dispatch("like_" + this.context + "_toggle");
          if(this.callback){
              this.callback();
          }
        }
        if(this.noWaitAction){
            this.canChange = true;

        }else{
            this.canChange = false;
        }
      } else {
        this.openLogin();
      }
    },
    toggleShareActions() {
      if (this.isAuth) {
        this.shareActions = !this.shareActions;
      } else {
        this.openLogin();
      }
    },
    shareLink(url) {
      let link =
        url +
        encodeURIComponent(
          window.baseURL +
            "/#/" +
            (this.parentContext || this.context) +
            "/" +
            (this.parentId || this.objId)
        );
      return link;
    },
    openLogin() {
      this.$router.push({ query: { popup: "login" } });
    }
  },
  watch: {
    isLiked(isLiked) {
      console.log("CHANGED");
      this.liked = isLiked;
      this.canChange = true;
    }
  }
};
</script>

<style scoped>
.fa {
  margin-left: 3px;
  margin-right: 3px;
  font-size: 20px;
}
.fa-heart {
  color: #f76c6c !important;
}
</style>
