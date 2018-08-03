<template>
        <div class="productCard">
            <router-link :to="(set.id && '/set/'+set.id)||''">
                <div class="avatar">
                    <div class="verticalCentered">
                        <div class="theCell"><img :src="(set['photo']&&set['photo']['photo_name'])||notFoundImg" alt="">
                        </div>
                    </div>
                </div>
            </router-link>
            <div class="content">
                <h3>
                    <router-link :to="(set.id && '/set/'+set.id)||''">{{set.title_en}}</router-link>
                </h3>
                <hr>
                <div v-if="set['user']&& set['user_id'] == $store.getters.userId">
                    <div class="setEdit">
                        <router-link :to="{name:'set_edit',params:{setId:set.id}}">Edit</router-link>
                        <a href="#" @click.prevent="remove" class="remove">{{loading?'loading..':'Remove'}}</a>
                    </div>
                </div>
                <div v-else>
                    <div class="createdBy">Created by</div>
                    <router-link :to="set['user']&& set['user_id']?'/profile/'+set['user_id']:''">
                        {{(set['user']&&set['user']['fname']+' '+set['user']['lname'])||'Modasti'}}
                    </router-link>
                </div>
            </div>
            <CardActions :likebale="true" :is-liked="set.is_liked" :sharable="true" :commentable="true"
                         :comment-url="set.id && '/set/'+set.id" :num-of-comments="set.comments_counter"
                         :num-of-likes="set.likes" :obj-id="set.id" :context="'set'"/>
        </div>
</template>

<script>
    import CardActions from "@/components/CardActions";

    export default {
        props: ["setId"],
        components: {
            CardActions
        },
        data() {
            return {
                loading: false,
                notFoundImg:
                    "http://www.zusjes.cz/system/show_image.php?src=storage%2FMech%2Fakce-a-terminy%2F%2Flogo3-1510042365.jpg&size=250x450&blank=1"
            };
        },
        computed: {
            set() {
                return this.$store.getters.getSet(this.setId);
            }
        },
        methods: {
            remove() {
                this.loading = true;
                this.$store.dispatch("remove_set", this.setId).then(() => {
                    this.$router.push("/profile/me/sets");
                    window.location.reload();
                    this.loading = false;
                });
            }
        }
    };
</script>

<style scoped>
    .content {
        min-height: 133px;
    }
</style>
