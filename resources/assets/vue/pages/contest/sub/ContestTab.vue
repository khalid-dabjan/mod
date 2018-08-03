<template>
  <div class="gridContainer">
    <div class="secPaddMd">
      <div class="myrow clearfix">
        <transition-group enter-active-class="animated slideInUp">
          <div v-for="contest of contests" class="mycol-lg-3 mycol-sm-6" :key="contest">
            <ContestCard :contest-id="contest" />
          </div>
        </transition-group>
      </div>
    </div>
  </div>
</template>

<script>
import ContestCard from "@/components/ContestCard";
export default {
  components: {
    ContestCard
  },
  data() {
    return {
      contests: []
    };
  },
  created() {
    let { contestTab } = this.$route.params;
    if (contestTab == "new") {
      this.isOld == false;
      this.contests = this.$store.getters.newContests;
    } else if (contestTab == "old") {
      this.isOld == true;
      this.contests = this.$store.getters.oldContests;
    }
  },
  watch: {
    "$route.params.contestTab"(contestTab) {
      if (!contestTab) return;
      if (contestTab == "new") {
        this.contests = this.$store.getters.newContests;
      } else if (contestTab == "old") {
        this.contests = this.$store.getters.oldContests;
      }
    }
  }
};
</script>