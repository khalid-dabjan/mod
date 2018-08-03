<template>
  <div class="PD_comments">
    <div class="addComment">
      <input type="text" v-model="commentToAdd" @keyup.enter="addComment" class="inputEle" placeholder="Add comment">
      <a href="#" @click.prevent="addComment" class="theIcon">
        <i v-if="!sending" class="fa fa-paper-plane"></i>
        <i v-if="sending" class="fa fa-spinner fa-spin"></i>
      </a>
    </div>
    <div v-if="!loadingComments">
      <div v-for="comment of contestComments.slice(0,showNumOfComments)" :key="comment.id" class="theComments">
        <div class="one clearfix">
          <router-link :to="'/profile/'+comment.from_id"> <img :src="comment.user.photo && comment.user.photo.photo_name == 'string' ? user.photo.photo_name : 'https://i.stack.imgur.com/1gPh1.jpg?s=328&g=1'" class="avatar" alt=""> </router-link>
          <a href="#" v-if="comment.from_id===$store.getters.userId" @click.prevent="deleteComment(comment.id)" class="deleteComment">Delete</a>
          <router-link v-else :to="`?popup=report&objid=${comment.id}&type=contest_comment&url=${url}`" class="deleteComment" style="color:red">Report</router-link>
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
    <a v-if="showNumOfComments < contestComments.length" href="#" @click.prevent="showMoreComments" class="moreLinks">More Comments</a>
  </div>
</template>

<script>
export default {
    props: ["contestId"],
    data() {
        return {
            sending: false,
            commentToAdd: "",
            loadingComments: true,
            showNumOfComments: 3
        };
    },
    computed: {
        contestComments() {
            return this.$store.getters.contestComments;
        },
        url() {
            return window.location.origin + "/#/contest/" + this.contestId;
        }
    },
    created() {
        this.load();
    },
    methods: {
        load() {
            this.$store
                .dispatch("get_contest_comments", this.contestId)
                .then(() => (this.loadingComments = false));
        },
        addComment() {
            this.sending = true;
            this.loadingComments = true;
            this.$store
                .dispatch("add_comment_to_contest", {
                    contestId: this.contestId,
                    text: this.commentToAdd
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
                .dispatch("delete_comment_from_contest", {
                    contestId: this.contestId,
                    commentId: id
                })
                .then(() => (this.loadingComments = false));
        },
        showMoreComments() {
            this.showNumOfComments += 3;
        }
    }
};
</script>

<style>
</style>
