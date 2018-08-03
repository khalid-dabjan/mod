<template>
    <div>
        <div class="secPaddLg whiteBg textCentered">
            <div class="gridContainer">
                <div class="sectionName">
                    <div class="theName">Help</div>
                </div>
            </div>
        </div>
        <div class="gridContainer">
            <div class="helpPage">

                <div class="oneQuestion" v-if="questions&&questions.length>0&&!loading" v-for="question of questions"
                     :key="question.id" :class="{'opened':question.show}" @click="question.show=!question.show">
                    <div class="theQuestion">{{question.title}}
                        <span class="icon">
              <i class="fa fa-plus"></i>
            </span>
                    </div>
                    <transition name="fade">
                        <div class="theAnswer" v-html="question.answer">
                        </div>
                    </transition>
                </div>
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
        data() {
            return {
                loading:false,
                questions: [],
            }
        },
        created(){
            this.loading=true;
            API.get('/getQuestions').then((res)=>{
                this.questions=res.data.data.map((question)=>{
                    return {
                        ...question,
                        show:false
                    }
                });
                this.loading=false;
            });
        }
    };
</script>

<style>

</style>
