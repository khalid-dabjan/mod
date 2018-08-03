<template>
    <div>
        <div class="secPaddLg whiteBg textCentered">
            <div class="gridContainer">
                <div class="sectionName">
                    <div class="theName" >{{pageTitle}}</div>
                </div>
            </div>
        </div>

        <div class="gridContainer">
            <div class="termsPage secPaddLg" v-html="pageContent">
            </div>
        </div>
        <Loading v-if="loading" />
    </div>
</template>

<script>

    import API from "@/store/API";
    import Loading from "@/components/Loading";

    export default {
        components: {
            Loading
        },
        created() {
            this.loading=true;
            API.get('/getPages/contact-us').then((res)=>{
                this.pageTitle = res.data.data.title;
                this.pageContent = res.data.data.content;
                this.loading=false;
            });
        },
        data() {
            return {
                loading: false,
                pageContent: '',
                pageTitle: '',
            }
        }
    };
</script>