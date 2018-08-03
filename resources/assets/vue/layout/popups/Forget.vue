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
				<form @submit="send" class="theForm" v-if="!upload">
					<input type="email" class="formEle" placeholder="Email" v-model="email" required>
					<div v-for="(error,i) in errors" :key="i">
						<h4 class="errors">
							{{error}}
						</h4>
						<br/>
					</div>
					<input type="submit" :disabled="loading" class="formEle btn" :value="isLoading">
				</form >
				<p style="color: green" v-if="upload">Check you E-mail</p>
			</div>
		</div>
	</transition>
</template>


<script>
    import API from "@/store/API";
export default {
  data() {
    return {
      email: "",
      loading: false,
      errors: [],
		upload:false,
    };
  },
  methods: {
    send(e) {
      e.preventDefault();
      	this.loading=true;
        API.post('/passwordReset',{
            email:this.email
		}).then((res)=>{
            this.loading=false;
            this.upload=true;
		}).catch((err)=>{
		    this.errors=['You email not exist'];
            this.loading=false;
        });
    }
  },
  computed: {
    isLoading() {
      return this.loading ? "Loading.." : "Send";
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
