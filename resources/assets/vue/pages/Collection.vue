<template>
  <div class="gridContainer">
    <div class="proudctDetails secondStyle">
      <div class="clearfix">
        <div class="avatar"><img :src="collection['image']" alt=""></div>
        <div class="content">
          <div class="in">
            <div class="paging">
              <a>Home</a>
              <a>Collection</a>
            </div>
            <h2 class="title">{{collection.title_en}}</h2>
            <div v-html="collection.text_en" class="description"></div>
            <div class="info clearfix">
              <!-- <div class="price">{{setTotalPrice}} $</div> -->
              <a v-if="collection['user']" :href="'/profile/'+collection['user']['id']" class="link">{{collection['user']['name']}}</a>
            </div>
            <div v-if="userId == collection.user_id">
              <router-link :to="'?popup=edit_collection&collectionId='+collection.id" class="mainBtn brandBg">Edit</router-link>
              <a href="#" @click.prevent="remove" class="mainBtn">Remove</a>
            </div>
            <br>
            <CardActions :likeable="true" :is-liked="collection.is_liked" :commentable="true" :sharable="true" :obj-id="collection.id" :num-of-likes="collection.likes" :num-of-comments="collection.comments_counter" context="collection" />
          </div>
        </div>
      </div>
      <div class="PD_comments">
        <div class="addComment">
          <input type="text" v-model="commentToAdd" @keyup.enter="addComment" class="inputEle" placeholder="Add comment">
          <a href="#" @click.prevent="addComment" class="theIcon">
            <i v-if="!sending" class="fa fa-paper-plane"></i>
            <i v-if="sending" class="fa fa-spinner fa-spin"></i>
          </a>
        </div>
        <div v-if="!loadingComments">
          <div v-for="comment of collectionComments.slice(0,showNumOfComments)" :key="comment.id" class="theComments">
            <div class="one clearfix">
              <router-link :to="'/profile/'+comment.from_id">
                <img :src="comment.user.photo ? comment.user.photo.photo_name : '/images/male-user-shadow.png'" class="avatar" alt="" />
                <span class="comment-user-name">{{ comment.user.fname +' ' +  comment.user.lname}}</span>
              </router-link>
              <a href="#" v-if="userId === comment.from_id" @click.prevent="deleteComment(comment.id)" class="deleteComment">Delete</a>
              <div class="itsContent">
                <div class="message">{{comment.text_en}}</div>
                <div class="time">{{comment.created}}</div>
              </div>
            </div>
          </div>
        </div>
        <div v-if="loadingComments">
          <i class="fa fa-spinner fa-spin"></i>
        </div>
        <a v-if="showNumOfComments < collectionComments.length" href="#" @click.prevent="showMoreComments" class="moreLinks">More Comments</a>
      </div>
    </div>
    <WrapperCardListTitled v-if="collection['sets'].length > 0" title="Sets" more="false" url="#">
      <div v-for="set in collection['sets']" :key='set' class="mycol-lg-3 mycol-xs-6">
        <SetCard :set-id="set" />
      </div>
    </WrapperCardListTitled>
    <WrapperCardListTitled v-if="collection['items'].length > 0" title="Items" more="false" url="#">
      <div v-for="item in collection['items']" :key='item' class="mycol-lg-3 mycol-xs-6">
        <ItemCard :item-id="item" />
      </div>
    </WrapperCardListTitled>
    <Loading v-if="loading" />
  </div>
</template>

<script>
import Loading from "@/components/Loading";
import CardActions from "@/components/CardActions";
import WrapperCardListTitled from "@/wrappers/WrapperCardListTitled";
import ItemCard from "@/components/ItemCard";
import SetCard from "@/components/SetCard";
import { mapGetters } from "vuex";

export default {
  components: {
    Loading,
    CardActions,
    WrapperCardListTitled,
    ItemCard,
    SetCard
  },
  computed: {
    ...mapGetters([
      "userId",
      "collectionComments",
      "collection",
      "collectionTotalPrice"
    ])
  },
  data() {
    return {
      loading: true,
      sending: false,
      commentToAdd: "",
      loadingComments: true,
      showNumOfComments: 3
    };
  },
  created() {
    this.load();
  },
  watch: {
    "$route.params.collectionId"(collectionId) {
      if (!collectionId) return;
      this.load();
    }
  },
  methods: {
    load() {
      this.loading = true;
      this.loadingComments = true;
      this.$store
        .dispatch("get_collection_details", this.$route.params.collectionId)
        .then(() => (this.loading = false))
        .catch(err => {
          console.error(err);
          if (this.$store.getters.isAuth)
            this.$router.replace({ path: "/404" });
        });
      this.$store
        .dispatch("get_collection_comments", this.$route.params.collectionId)
        .then(() => (this.loadingComments = false));
    },
    remove() {
      this.loading = true;
      this.$store.dispatch("remove_collection", this.collection.id).then(() => {
        this.$router.push("/profile/me/collections");
        this.loading = false;
      });
    },
    addComment() {
      this.sending = true;
      this.loadingComments = true;
      this.$store
        .dispatch("add_comment_to_collection", {
          collectionId: this.collection.id,
          comment: this.commentToAdd
        })
        .then(() => {
          this.sending = false;
          this.loadingComments = false;
          this.commentToAdd = "";
        });
    },
    deleteComment(id) {
      this.loadingComments = true;
      this.$store
        .dispatch("delete_comment_from_collection", {
          commentId: id,
          collectionId: this.collection.id
        })
        .then(() => {
          this.loadingComments = false;
        });
    },
    showMoreComments() {
      this.showNumOfComments += 3;
    }
  }
};
</script>
