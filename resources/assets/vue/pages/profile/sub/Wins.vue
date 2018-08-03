<template>
    <div class="gridContainer">
        <WrapperCardList>
            <div v-if="contests.length==0&&isMyOwnProfile" class="btn-wrapper">
                <router-link :to="'/contest'"  class="btn">
                    Join a contest and win fantastic discounts!
                </router-link>

            </div>

            <div v-if="contests.length==0&&(!isMyOwnProfile)" class="btn-wrapper">
                <p>
                    No contests found
                </p>
            </div>
            <div v-for="contest in contests" :key='contest' class="mycol-lg-3 mycol-sm-6">
                <ContestCard :contest-id="contest" />
            </div>
        </WrapperCardList>
        <div v-if="contests.length % 8 === 0 && contests.length!==0" class="getMore">
            <a @click.prevent="load" href="#"> {{ loadMoreLoading ? 'Loading...' : 'More' }} </a>
        </div>
        <Loading v-if="loading" />
    </div>
</template>

<script>
    import ContestCard from "@/components/ContestCard";
    import Loading from "@/components/Loading";
    import WrapperCardList from "@/wrappers/WrapperCardList";

    export default {
        components: {
            ContestCard,
            WrapperCardList,
            Loading
        },
        data() {
            return {
                loading: true,
                loadMoreLoading: false
            };
        },
        computed: {
            contests() {
                return this.$store.getters.winsContests;
            },
            isMyOwnProfile(){
                return (
                    this.$store.getters.user.userId == this.$route.params.userId ||
                    this.$route.params.userId == "me"
                );
            }
        },
        created() {
            this.load().then(() => {
                this.loading = false;
            });
        },
        methods: {
            load() {
                this.loadMoreLoading = true;
                let id = isNaN(this.$route.params.userId) ? this.$store.getters.user.userId : this.$route.params.userId;
                return this.$store.dispatch("get_wins_contests", id).then(() => {
                    this.loadMoreLoading = false;
                });
            }
        }
    };
</script>
<style>

</style>