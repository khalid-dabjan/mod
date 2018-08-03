<template>
	<div class="secPaddLg whiteBg textCentered">
		<div class="gridContainer">
			<div class="sectionName">
				<div class="theName">SEARCH IN {{searchIn}}S</div>
				<!-- <div class="second">{{num}} results</div> -->
			</div>
		</div>
	</div>
</template>

<script>
export default {
  props: ["searchIn"]
};
</script>

<style scoped>
.theName {
  text-transform: uppercase;
}
</style>

