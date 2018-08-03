<template>
    <div class="productCard">
        <div class="avatar">
            <router-link :to="(item.id?'/item/'+item.id : '')" class="verticalCentered">
                <div class="theCell"><img
                        :src="(item['photos'] && item['photos'][0]&&item['photos'][0]['photo_name'])||notFoundImg"
                        :alt="item.title_en"></div>
            </router-link>
        </div>
        <div class="content">
            <h3>
                <router-link :to="(item.id?'/item/'+item.id : '')">{{item.title_en}}</router-link>
                <a class="content-brand-name">{{item.brand}}</a>
            </h3>
            <div class="price">{{item.price}} {{getCurrencySymbolItem(item.currency)}}</div>
            <div class="link">
                <a :href=" (item.url_en?item.url_en :'#/profile/'+item.user_id)">{{ domainName}}</a>
            </div>
        </div>
        <CardActions :sharable="true" :num-of-likes="item.likes||item.likes_counter" :likebale="true"
                     :is-liked="item.is_liked" :obj-id="item.id" :context="'item'"/>
    </div>
</template>

<script>
    import CardActions from "./CardActions";
    import {getCurrencySymbol} from "@/pages/retailer/sub/currency.js"

    export default {
        props: ["itemId"],
        components: {
            CardActions
        },
        data() {
            return {
                notFoundImg:
                    "http://www.zusjes.cz/system/show_image.php?src=storage%2FMech%2Fakce-a-terminy%2F%2Flogo3-1510042365.jpg&size=250x450&blank=1"
            };
        },
        computed: {
            item() {
                return this.$store.getters.getItem(this.itemId);
            },
            isAuth() {
                return this.$store.getters.isAuth;
            },
            domainName(){
                return (new URL(this.item.url_en)).hostname;
            }
        },
        methods: {
            getCurrencySymbolItem(currency) {
                return getCurrencySymbol(currency)
            }
        },
        watch: {
            "item.is_liked"() {
                this.$forceUpdate();
            }
        }
    };
</script>
