<template>
  <span>
    <div class="dropdown">
      <a href="#" class="one">
        <i class="icon-headicon"></i>
      </a>
      <div class="dropdown-content">
        <router-link to="/set/add" class="addlinks">New set</router-link>
        <router-link to="/collection/add" class="addlinks">New collection</router-link>
      </div>
    </div>
    <router-link to="/messages" class="one">
      <i class="fa fa-envelope"></i>
    </router-link>
    <NotificationsDropDown/>
    <UserMenu :username="user.name" />
  </span>
</template>

<script>
import NotificationsDropDown from "./NotificationsDropDown";
import UserMenu from "./UserMenu";

export default {
  components: {
    NotificationsDropDown,
    UserMenu
  },
  computed: {
    user() {
      return this.$store.getters.user;
    }
  }
};
</script>

<style scoped>
.addlinks {
  display: block;
  color: black !important;
  font-size: 1em;
  text-align: left;
  padding: 5px;
}
.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  transition: opacity 0.4s ease-out;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
  padding: 10px 14px;
  margin-left: 39px;
  z-index: 1;
}

.dropdown:hover .dropdown-content {
  display: block;
}
</style>
