<template>
        <div class="productCard">
            <div class="avatar">
                <div class="verticalCentered">
                    <router-link :to="isAuth?'/contest/'+contest.id:'?popup=login'" class="theCell"><img
                            :src="(contest['photo'] && contest['photo']['photo_name']) || 'images/img5.jpg'" alt="">
                    </router-link>
                </div>
            </div>
            <div class="content">
                <h3>
                    <router-link :to="isAuth?'/contest/'+contest.id:'?popup=login'">{{contest.title_en}}</router-link>
                </h3>
                <hr>
                <div v-if="isAuth">
                    <router-link v-if="!contest.is_photo_submitted && contest._type=='new'"
                                 :to="'?popup=join_contest&contestId='+contestId" class="mainBtn">Join Contest
                    </router-link>
                    <router-link v-else-if="contest._type=='old'" class="mainBtn" :to="'/contest/'+contest.id"> Check
                        Winner
                    </router-link>
                    <router-link v-else class="mainBtn" :to="'/contest/'+contest.id"> View</router-link>
                </div>
            </div>
            <CardActions :likeable="true" :is-liked="contest.is_liked" :commentable="true" :sharable="true"
                         :obj-id="contest.id" :num-of-likes="contest.likes" :num-of-comments="contest.comments"
                         context="contest"/>
        </div>
</template>
<script>
    import CardActions from "@/components/CardActions";

    export default {
        props: ["contestId"],
        components: {
            CardActions
        },
        computed: {
            contest() {
                return this.$store.getters.contest(this.contestId);
            },
            isAuth() {
                return this.$store.getters.isAuth;
            }
        }
    };
</script>
