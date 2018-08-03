<template>
    <div>
        <div class="sectionTitle clearfix">
            <h2 class="theName">{{title}}</h2>
            <router-link v-if="more != 'false'" :to="url" class="more">More</router-link>
        </div>

        <div class="secPaddMd">
            <div class="myrow clearfix">
                <slot/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:[ 'title' , 'url' , 'more' ]
}
</script>
