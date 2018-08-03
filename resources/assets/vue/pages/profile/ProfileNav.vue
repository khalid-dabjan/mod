<template>
  <div id="profilenav">
	<div class="categoriesTop whiteBg">
		<div class="gridContainer clearfix">
			<div class="uppercase">
				<router-link active-class="active-header" to="sets">Sets</router-link>
				<router-link active-class="active-header" to="collections">Collections</router-link>
				<router-link active-class="active-header" to="likes">Likes</router-link>
				<router-link active-class="active-header" to="following">Following</router-link>
				<router-link active-class="active-header" to="followers">Followers</router-link>
				<router-link active-class="active-header" to="wins">Wins</router-link>
				<router-link v-if="$route.params.userId=='me'||$route.params==$store.getters.userId" active-class="active-header" to="blocked"> Blocked users </router-link>
			</div>
		</div>
	</div>
  </div>
</template>

<style scoped>
.uppercase > a{
	text-transform: uppercase;
}
</style>
