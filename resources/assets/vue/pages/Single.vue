<template>
    <div>
        <div class="secPaddLg whiteBg textCentered">
            <div class="gridContainer">
                <div class="sectionName">
                    <div class="theName">{{title}}</div>
                </div>
            </div>
        </div>

        <div class="gridContainer">
            <div class="aboutPage secPaddLg">
                
                <div class="avatar"><img :src="img" alt=""></div>
                <p :v-html="content" >
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            title:'',
            img:'',
            content:''
        }
    }
}
</script>

