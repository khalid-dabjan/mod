<template>
  <div>
    <RetailerNav/>
    <transition name="partner-subpage" enter-active-class="animated fadeIn">
      <keep-alive :exclude="['new-item','edit-item']">
        <router-view></router-view>
      </keep-alive>
    </transition>
  </div>
</template>

<script>
import RetailerNav from "./RetailerNav";
export default {
  components:{
    RetailerNav
  }
};
</script>

<style>

</style>
