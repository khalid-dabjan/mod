<template>
    <transition name="popups" enter-active-class="animated bounceIn">
        <div>
            <div class="head">
                <span>Notes</span>
                <router-link class="head" to="?popup=">
          <span class="icon">
            <i class="fa fa-close"></i>
          </span>
                </router-link>
            </div>
            <div class="content">
                Please Enter you about and you currency    <router-link to="/profile/edit" class="button-black">Edit now</router-link>
            </div>
        </div>
    </transition>
</template>