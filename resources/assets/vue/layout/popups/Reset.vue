<template>
	<transition name="popups" enter-active-class="animated bounceIn">
		<div>
			<div class="head">
				<span>forget</span>
				<router-link class="head" to="?popup=">
					<span class="icon">
						<i class="fa fa-close"></i>
					</span>
				</router-link>
			</div>
			<div class="content">
				<form @submit.prevent="send" class="theForm">
					<input type="password" class="formEle" placeholder="New Password" v-model="password" required>
					<input type="password" class="formEle" placeholder="Confirm Password" v-model="password2" required>
					<div v-for="(error,i) in errors" :key="i">
						<h4 class="errors">
							{{error}}
						</h4>
						<br/>
					</div>
					<input type="submit" :disabled="loading" class="formEle btn" :value="isLoading">
				</form>
			</div>
		</div>
	</transition>
</template>


<script>
export default {
  data() {
    return {
      password: "",
      password2: "",
      loading: false,
      errors: []
    };
  },
  methods: {
    send() {
			if(this.password && this.password == this.password2 ){
				this.errors = ["no API"];
			}else{
				this.errors = ["Passwords not maching"];				
			}
    }
  },
  computed: {
    isLoading() {
      return this.loading ? "Loading.." : "Change";
    }
  }
};
</script>


<style scoped>
input:invalid {
  background-color: #fff;
}
.errors {
  font-family: "Cheque-Black";
  color: RED;
}
</style>
