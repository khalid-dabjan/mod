<template>
        <div class="productCard">
            <router-link :to="(collection.id && '/collection/'+collection.id)||''">
                <div class="avatar">
                    <div class="verticalCentered">
                        <div class="theCell"><img :src="(collection['image'])||notFoundImg" alt=""></div>
                    </div>
                </div>
            </router-link>
            <div class="content">
                <h3>
                    <router-link :to="(collection.id && '/collection/'+collection.id)||''">{{collection.title_en}}
                    </router-link>
                </h3>
                <hr>
                <div v-if="collection['user_id'] == $store.getters.userId">
                    <div class="setEdit">
                        <router-link :to="'?popup=edit_collection&collectionId='+collection.id">Edit</router-link>
                        <a href="#" @click.prevent="remove" class="remove">{{loading?'loading..':'Remove'}}</a>
                    </div>
                </div>
                <div v-else>
                    <div class="createdBy">Created by</div>
                    <router-link
                            :to="collection['user']&& collection['user']['id']?'/profile/'+collection['user']['id']:''">
                        {{(collection['user']&&collection['user']['username'])||'Modasti'}}
                    </router-link>
                </div>
            </div>
            <CardActions :likebale="true" :is-liked="collection.is_liked" :sharable="true" :commentable="true"
                         :num-of-comments="collection.comments_counter" :num-of-likes="collection.likes"
                         :obj-id="collection.id" :context="'collection'"/>
        </div>
</template>

<script>
    import CardActions from "@/components/CardActions";

    export default {
        props: ["collectionId"],
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
            collection() {
                return this.$store.getters.getCollection(this.collectionId);
            }
        },
        methods: {
            remove() {
                this.loading = true;
                this.$store.dispatch("remove_collection", this.collectionId).then(() => {
                    this.$router.push("/profile/me/collections");
                    window.location.reload();
                    this.loading = false;
                });
            }
        }
    };
</script>
