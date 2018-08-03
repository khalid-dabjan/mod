<template>
  <div class="gridContainer">
    <div class="proudctDetails secondStyle">
      <div class="clearfix">
        <div class="avatar"><img :src="set['photo'] && set['photo']['photo_name']" alt=""></div>
        <div class="content">
          <div class="in">
            <div class="paging">
              <a href="/">Home</a>
              <a href="/set">Set</a>
            </div>
            <h2 class="title">{{set.title_en}}</h2>
            <div v-html="set.text_en" class="description"></div>
            <div class="info clearfix">
              <div class="price">{{setTotalPrice}} $</div>
              <router-link class="report set-report" :to="`?popup=report&objid=${set.id}&type=set&url=${url}`">Report</router-link>
            </div>
            <CardActions :likeable="true" :is-liked="set.is_liked" :commentable="true" :sharable="true" :obj-id="set.id" :num-of-likes="set.likes" :num-of-comments="set.comments_counter" context="set" />
            <br>
            <br>
            <div v-if="userId === set.user_id">
              <router-link :to="{name:'set_edit',params:{setId:set.id}}" class="mainBtn brandBg">Edit</router-link>
              <a href="#" @click.prevent="remove" class="mainBtn">Remove</a>
            </div>
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
          <div v-for="comment of setComments.slice(0,showNumOfComments)" :key="comment.id" class="theComments">
            <div class="one clearfix">
              <router-link :to="'/profile/'+comment.from_id">
                <img :src="comment.user.photo ? comment.user.photo.photo_name : '/images/male-user-shadow.png'" class="avatar" alt="" />
                <span class="comment-user-name">{{ comment.user.fname +' ' + comment.user.lname}}</span>
              </router-link>
              <a v-if="userId === comment.from_id" href="#" @click.prevent="deleteComment(comment.id)" class="deleteComment">Delete</a>
              <router-link v-else :to="`?popup=report&objid=${comment.id}&type=set_comment&url=${url}`" class="deleteComment" style="color:red" >Report</router-link>
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
        <a v-if="showNumOfComments < setComments.length" href="#" @click.prevent="showMoreComments" class="moreLinks">More Comments</a>
      </div>
    </div>
    <WrapperCardListTitled title="Items" url="#" more="false">
      <div v-for="item in set['items']" :key='item' class="mycol-lg-3 mycol-xs-6">
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
import WrapperPopups from "@/wrappers/WrapperPopups";
import SetCollectionEditPopup from "@/layout/popups/SetCollectionEditPopup";
import { mapGetters } from "vuex";

export default {
    components: {
        Loading,
        CardActions,
        WrapperCardListTitled,
        ItemCard,
        WrapperPopups,
        SetCollectionEditPopup
    },
    computed: {
        ...mapGetters(["userId", "setComments", "set", "setTotalPrice"]),
        url() {
            return window.location.origin + "/#/set/" + this.set.id;
        }
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
        "$route.params.setId"(setId) {
            if (!setId) return;
            this.load();
        }
    },
    methods: {
        load() {
            this.loading = true;
            this.loadingComments = true;
            this.$store
                .dispatch("get_set_details", this.$route.params.setId)
                .then(() => (this.loading = false))
                .catch(err => {
                    if (this.$store.getters.isAuth)
                        this.$router.replace({ path: "/404" });
                });
            this.$store
                .dispatch("get_set_comments", this.$route.params.setId)
                .then(() => (this.loadingComments = false));
        },
        remove() {
            this.loading = true;
            this.$store.dispatch("remove_set", this.set.id).then(() => {
                this.$router.push("/profile/me/sets");
                window.location.reload();
                this.loading = false;
            });
        },
        addComment() {
            this.sending = true;
            this.loadingComments = true;
            this.$store
                .dispatch("add_comment_to_set", {
                    setId: this.set.id,
                    comment: this.commentToAdd
                })
                .then(() => {
                    this.commentToAdd = "";
                    this.sending = false;
                    this.loadingComments = false;
                });
        },
        deleteComment(id) {
            this.loadingComments = true;
            this.$store
                .dispatch("delete_comment_from_set", {
                    commentId: id,
                    setId: this.set.id
                })
                .then(() => (this.loadingComments = false));
        },
        showMoreComments() {
            this.showNumOfComments += 3;
        }
    }
};
</script>
