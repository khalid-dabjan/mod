<template>
	<div class="gridContainer">
		<div class="proudctDetails secondStyle">
			<div class="clearfix">
				<div class="avatar"><img :src="(contest['photo']&&contest['photo']['photo_name'])||'images/img5.jpg'" alt=""></div>
				<div class="content">
					<div class="in">
						<div class="paging">
							<router-link :to="'/'" class="name">Home</router-link>
							<router-link :to="'/contest/s/new'" class="name">Contest</router-link>
						</div>
						<h2 class="title">{{contest.title_en}}</h2>
						<div v-html="contest.text2_en" class="description"></div>
						<h2 class="title">End date</h2>
						<div class="mrgBtmLg">
							{{days}}
							<span class="brandColor">{{days>1?'DAYS':"DAY"}}</span>
							{{hours}}
							<span class="brandColor">{{hours>1?'hours':"hour"}}</span>
							{{minutes}}
							<span class="brandColor">{{minutes>1?'minutes':"minute"}}</span>
						</div>
						<CardActions :likeable="true" :is-liked="contest.is_liked" :sharable="true" :obj-id="contest.id" :num-of-likes="contest.likes" :num-of-comments="contest.comment" context="contest" />
						<br>
						<router-link :to="'?popup=join_contest&contestId='+contest.id" class="mainBtn">Join contest</router-link>
					</div>
				</div>
			</div>
			<ContestComments :contest-id="contest.id" />
		</div>
		<div class="sectionTitle clearfix">
			<h2 class="theName">Competitors</h2>
		</div>
		<div class="secPaddMd">
			<div class="myrow clearfix">
				<div v-for="photo of photos" :key="photo.id" class="mycol-lg-3 mycol-sm-6">
					<div class="productCard">
						<div class="avatar">
							<div class="verticalCentered">
								<router-link :to="'?popup=image&imgURL='+(photo['photo']&&photo['photo']['photo_name'])" class="theCell"><img :src="(photo['photo']&&photo['photo']['photo_name'])||notFoundImg" alt=""></router-link>
							</div>
						</div>
						<div class="content">
							<h3>
								{{photo.title_en}}
							</h3>
							<hr>
							<div>
								<div class="createdBy">Created by</div>
								<router-link :to="photo['user']&& photo['user_id']?'/profile/'+photo['user_id']:''">{{(photo['user']&&photo['user']['fname'])||'Modasti'}}</router-link>
							</div>
						</div>
						<CardActions :likebale="true" :is-liked="photo.is_liked" :sharable="true" :num-of-likes="photo.likes" :parentId="contest.id" :obj-id="photo.id" :parent-context="'contest'" :context="'contest_item'" />
					</div>
				</div>
			</div>
			<div v-if="photos.length && photos.length %8 === 0" class="getMore">
				<a @click.prevent="loadmore" href="#"> {{ loadMoreLoading ? 'Loading' : 'More' }} </a>
			</div>
		</div>
	</div>
</template>

<script>
import CardActions from "@/components/CardActions";
import ContestComments from "./ContestComments";
export default {
  props: ["contest"],
  components: {
    CardActions,
    ContestComments
  },
  data() {
    return {
      days: 0,
      hours: 0,
      minutes: 0,
      loadMoreLoading: false,
      notFoundImg:
        "http://www.zusjes.cz/system/show_image.php?src=storage%2FMech%2Fakce-a-terminy%2F%2Flogo3-1510042365.jpg&size=250x450&blank=1"
    };
  },
  computed: {
    photos() {
      return this.$store.getters.contestPhotos;
    }
  },
  methods: {
    loadmore() {
      this.loadMoreLoading = true;
      this.$store
        .dispatch("get_more_photos", this.contest.id)
        .then(() => (this.loadMoreLoading = false));
    }
  },
  created() {
    let countDownDate = new Date(this.contest.expires).getTime();
    let culTime = () => {
      let now = new Date().getTime();
      let distance = countDownDate - now;
      this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
      this.hours = Math.floor(
        (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
      );
      this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    };
    culTime();
    var x = setInterval(culTime, 1000 * 60);
  }
};
</script>
