<template>
  <transition name="popups" enter-active-class="animated bounceIn">
    <div>
      <div class="head">
        <span>Imported Items Status</span>
        <router-link class="head" to="?popup=">
          <span class="icon">
            <i class="fa fa-close"></i>
          </span>
        </router-link>
      </div>
      <div class="content">
        	<form @submit="()=>[]" class="theForm">
				<h2>
          Status: <span class="uploaded"> {{status.status ?"Uploaded Successfully":"Rejected"}}</span>
          <br>
          New Inserted Items: <span class="bluetext"> {{status.newItems}}</span>
          <br>
          <span class="errors"> Already Exist:  {{status.foundItems}}</span>
        </h2>	
        <br>
        <input type="submit" @click.prevent="redirectAndReloud" class="formEle btn" :value="'Got it, Redirect me to my Items'">
				</form>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props:["status"],
  methods: {
    redirectAndReloud() {
      this.$router.push('/retailer/');
      window.location.reload();
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
.uploaded{
  color: rgb(23, 94, 23);
}
.bluetext{
  color: teal;
}
h2{
  font-size: 1.2em;
}
</style>