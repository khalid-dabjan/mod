<template>
	<div>
		<div v-if="isTab" class="secPaddLg whiteBg">
			<div class="gridContainer">
				<div class="sectionName">
					<div class="theName">CONTESTS</div>
					<div class="second">JOIN A CONTEST AND WIN FANTASTIC DISCOUNTS!</div>
				</div>
			</div>
		</div>
		<div class="contestTop whiteBg">
			<div class="gridContainer clearfix">
				<router-link active-class="active-header" exact to="/contest/s/new">New Contests</router-link>
				<router-link active-class="active-header" exact to="/contest/s/old">Old Contests</router-link>
			</div>
		</div>
		<div v-if="!loading" class="gridContainer">
			<transition name="paget" enter-active-class="animated fadeIn">
				<router-view />
			</transition>
		</div>
		<Loading v-if="loading" />
	</div>
</template>

<script>
import Loading from "@/components/Loading";
export default {
  data() {
    return {
      loading: true
    };
  },
  created() {
    this.$store.dispatch("get_all_contests").then(() => {
      this.loading = false;
    });
  },
  computed: {
    isTab() {
      return (/\/contest\/s\/(new|old)/).test( this.$route.path);
    }
	},
	components: {
		Loading
	}
};
</script>
