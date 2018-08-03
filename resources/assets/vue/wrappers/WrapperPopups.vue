<template>
  	<div class="popupPage">
		<div class="verticalCentered">
			<div class="theCell">
				<router-link to="?popup=">
					<div class="outerClose"></div>
				</router-link>
				<div class="thePage">
					<slot/>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
  props: ['title']
}
</script>
