<template>
	<div v-if="contest" class="gridContainer">
		<div class="proudctDetails secondStyle">
			<div class="clearfix">
				<div class="avatar"><img :src="(contest['photo']&&contest['photo']['photo_name'])||'images/img5.jpg'" alt=""></div>
				<div class="content">
					<div class="in">
						<div class="paging">
							<router-link :to="'/'" class="name">Home</router-link>
							<router-link :to="'/contest/s/old'" class="name">Contest</router-link>
						</div>
						<h2 class="title">{{contest.text_en}}</h2>
						<div v-html="contest.text2_en" class="description">
						</div>
						<CardActions :likeable="true" :is-liked="contest.is_liked" :commentable="true" :sharable="true" :obj-id="contest.id" :num-of-likes="contest.likes" :num-of-comments="contest.comment" context="contest" />
					</div>
				</div>
			</div>
			<div class="PD_winner clearfix" v-for="winner of winners" :key="winner.id">
				<div class="itsTitle">
					<span>The Winner</span>
				</div>
				<div class="itsContent" >
					<div class="PD_Card">
						<div class="PD_cardAvatar"><img :src="winner.photo.photo_name" :alt="winner.photo.title"></div>
						<div class="PD_cardContent">
							<h3>
								<a href="javascript:void(0)">{{winner.contest_title}}</a>
							</h3>
							<hr>
							<div class="createdBy">Created by</div>
							<div>Modasti retail - {{winner.user.fname +' '+winner.user.lname}}</div>
						</div>
						<CardActions :callback="changeWinner(winner)" :likebale="true" :no-wait-action="true" :afterLike="changeWinner(winner)" :is-liked="winner.is_liked" :sharable="true" :num-of-likes="winner.likes" :parentId="contest.id" :obj-id="winner.id" :parent-context="'contest'" :context="'contest_item'" />					</div>
					<div class="PD_about">
						<div class="PD_aboutUser clearfix">
							<img :src="winner.user.photo?winner.user.photo.photo_name:'https://i.stack.imgur.com/1gPh1.jpg?s=328&g=1'" alt="" class="itsAvatar">
							<div class="itsData">
								<div class="name">{{winner.user.fname +' '+winner.user.lname}}</div>
								<div class="brandColor joinedDate">Joined on {{winner.join_on}}</div>
								<div>
									<router-link :class="'mainBtn'" :to="'/profile/'+winner.user_id" class="name">{{ !winner.user.is_followed?'Follow':'Unfollow'  }}</router-link>
								</div>
							</div>
						</div>
						<div class="description">
							{{winner.user.about}}
						</div>
					</div>
				</div>
			</div>
			<ContestComments :contest-id="contest.id" />
		</div>
	</div>
</template>

<script>
import CardActions from "@/components/CardActions";
import ContestComments from "./ContestComments";
export default {
  props: ["contest"],
	computed:{
        winners(){
           return this.contest.winners
        }
	},
	methods:{
        changeWinner(winner) {
            var self=this;
            return function () {
                winner.is_liked = !winner.is_liked;
                winner.likes = winner.is_liked? winner.likes+1:winner.likes-1;
                self.$forceUpdate()
            }
        }
	},
  components: {
    CardActions,
    ContestComments
  },
};
</script>
