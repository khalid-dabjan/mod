<template>
    <transition name="popups" enter-active-class="animated bounceIn">
        <div>
            <div :style="{background:'url('+$route.query.imgURL+') center', backgroundSize:'cover'}"  class="content">
                <img :src="$route.query.imgURL" class="hidn" />
            </div>
        </div>
    </transition>
</template>

<script>
export default {
  data() {
    return {};
  },
  methods: {
    
  },
  computed: {
    
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
.hidn{
    visibility: hidden;
    height: 85%;
    max-width: 85%;
}
</style>