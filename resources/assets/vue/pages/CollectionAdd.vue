<template>
    <div class="gridContainer">
        <div class="createSetPage secPaddLg clearfix">
            <div class="leftArea">
                <div class="areaToDrop">
                    <div class="intialText" v-if="itemsCounter == 0">Drag sets or items here</div>
                    <div @drop="drop" ref="dropareaC" @dragover.prevent="nothing" id="dropareaC"
                         style="background:#fff; height:100%; width:100%;"></div>
                </div>
                <div class="actionBtns">
                    <a href="#" @click.prevent="publish" class="publishBtn">Publish</a>
                    <div class="otherBtns">
                        <div class="oneBtn">
                            <a @click.prevent="remove" href="#">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rightArea">
                <div class="theTabs">
                    <CategoriesDropdown id="set-select-categories" v-model="category" @change="changeCategory"
                                        :options="[{id:'liked_items',label:'Liked item'}]"></CategoriesDropdown>
                    <!-- <ColorDropdown id="set-select-colors" @change="changeCategory" v-model="color"></ColorDropdown> -->
                    <input id="set-item-search" type="search" col="50" @input="searchItems" v-model.trim="query"
                           placeholder="Search for item ..."/>
                </div>
                <div class="theProducts">
                    <div class="myrow clearfix">
                        <div v-if="!loading&&(category!==0||query!=='')">
                            <div v-for="(item) of items" :key="item.id" class="mycol-sm-4">
                                <div @dragstart="dragStart" draggable="true" :src="item['photos'][0]['photo_name']"
                                     :data-id="item.id" class="one">
                                    <div class="avatar">
                                        <div class="verticalCentered">
                                            <div class="theCell"><img :src="item['photos'][0]['photo_name']"
                                                                      :data-id="item.id" alt=""></div>
                                        </div>
                                    </div>
                                    <div class="name">{{item.title_en}}</div>
                                </div>
                            </div>
                            <div v-if="canloadmore&&!loading" class="getMore">
                                <a @click.prevent="loadmore" href="#"> {{ loadMoreLoading ? 'Loading' : 'More' }} </a>
                            </div>
                        </div>
                        <div v-else>
                            <div v-for="item of items" :key="item.id" class="mycol-sm-2 col-category">
                                <a @click.prevent="changeCategory(item.id)" href="#">
                                    <div class="one category-grid">
                                        <div class="avatar" draggable="false">
                                            <div class="verticalCentered" draggable="false">
                                                <div class="theCell"><img draggable="false" :src="item['photo']"></div>
                                            </div>
                                        </div>
                                        <div class="name nameS">{{item.name}}</div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div v-if="loading" class="set-loading"><img src="/images/loading.gif" width="50px"
                                                                     alt="loading"></div>
                        <div v-if="items&&items.length==0&&!loading" class="set-no-found">No found items</div>
                    </div>
                </div>
            </div>
        </div>
        <transition name="popups" enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
            <WrapperPopups v-if="$route.query.popup && $store.getters.isAuth">
                <SetCollectionAddPopup v-if="$route.query.popup=='create_collection'" submit-type="collection"
                                       :base64-img="base64Img" :items="collectionItems"
                                       :sets="collectionSets"></SetCollectionAddPopup>
            </WrapperPopups>
        </transition>
        <!--<Loading v-if="loading" />-->
    </div>
</template>
<script>
    import Knova from "konva";
    import WrapperPopups from "@/wrappers/WrapperPopups";
    import SetCollectionAddPopup from "@/layout/popups/SetCollectionAddPopup";
    import Loading from "@/components/Loading";
    import {mapGetters} from "vuex";
    import CategoriesDropdown from "@/components/CategoriesDropdown";
    import ColorDropdown from "@/components/ColorDropdown";
    import _ from "lodash";

    var $vm = null;
    export default {
        components: {
            SetCollectionAddPopup,
            WrapperPopups,
            Loading,
            CategoriesDropdown,
            ColorDropdown
        },

        data() {
            return {
                view: 0,
                stage: {},
                layer: {},
                selected: null,
                itemsCounter: 0,
                flipBit: -1,
                base64Img: "",
                collectionItems: [],
                collectionSets: [],
                loading: true,
                query: "",
                color: 0,
                category: 0,
                loadMoreLoading: false
            };
        },
        computed: {
            items() {
                return this.$store.getters.itemsToAddSet;
            },
            canloadmore() {
                return (
                    this.items && this.items.length != 0 && this.items.length % 6 === 0
                );
            }
        },
        created() {
            if (this.query === "" && this.category === 0) {
                this.$store.dispatch("get_default_items_for_add_set");
                this.loading = false;
            } else {
                this.$store
                    .dispatch("get_items_for_add_set", {
                        query: this.query,
                        category: this.category,
                        color: this.color,
                        clearOffset: true
                    })
                    .then(() => {
                        this.loading = false;
                    });
            }
            $vm = this;
        },
        mounted() {
            var width = this.$refs.dropareaC.offsetWidth - 15;
            var height = this.$refs.dropareaC.offsetHeight - 15;
            this.stage = new Konva.Stage({
                container: "dropareaC",
                width: width,
                height: height
            });
            this.layer = new Konva.Layer();
            this.stage.add(this.layer);
            let drawImage = this.drawImage;
            let transformerFunction = e => {
                if (e.target === this.stage) {
                    this.stage.find("Transformer").destroy();
                    this.layer.draw();
                    this.selected = null;
                    this.stage.container().style.cursor = "default";
                    return;
                } else if (e.target.hasName("img") && e.target !== this.selected) {
                    this.stage.find("Transformer").destroy();
                    var tr = new Konva.Transformer();
                    this.layer.add(tr);
                    tr.attachTo(e.target);
                    this.selected = e.target;
                    this.layer.draw();
                }
            };

            this.stage.on("click", transformerFunction);
            this.stage.on("dragstart", transformerFunction);
        },
        methods: {
            nothing() {
            },
            loadmore() {
                this.loadMoreLoading = true;
                this.$store
                    .dispatch("get_items_for_add_set", {
                        query: this.query,
                        category: this.category,
                        color: this.color,
                        clearOffset: false
                    })
                    .then(() => {
                        this.loadMoreLoading = false;
                        this.loading = false;
                    });
            },
            changeCategory(id) {
                this.category = id || this.category;
                this.loading = true;
                this.$store
                    .dispatch("get_items_for_add_set", {
                        query: this.query,
                        category: this.category,
                        color: this.color,
                        clearOffset: true
                    })
                    .then(() => {
                        this.loading = false;
                    });
            },
            searchItems: _.debounce(event => {
                this.loading = true;

                $vm.$store
                    .dispatch("get_items_for_add_set", {
                        query: $vm.query,
                        category: $vm.category,
                        color: $vm.color,
                        clearOffset: true
                    })
                    .then(() => {
                        $vm.loading = false;
                    });
            }, 500).bind(this),
            dragStart(event) {
                event.dataTransfer.setData(
                    "item",
                    JSON.stringify({
                        src: event.target.src,
                        id: event.target.getAttribute("data-id"),
                        type: event.target.getAttribute("data-type")
                    })
                );
            },
            drop(event) {
                event.preventDefault();
                this.itemsCounter++;
                let item = JSON.parse(event.dataTransfer.getData("item"));
                if (item.type == "set") {
                    this.collectionSets.indexOf(item.id) === -1
                        ? this.collectionSets.push(item.id)
                        : null;
                } else {
                    this.collectionItems.indexOf(item.id) === -1
                        ? this.collectionItems.push(item.id)
                        : null;
                }
                let img = new Image();
                img.onload = () => {
                    this.drawImage(img, item.id, event.offsetX, event.offsetY);
                };
                img.src = item.src;
            },
            drawImage(imageObj, id, x, y) {
                // darth vader
                var darthVaderImg = new Konva.Image({
                    image: imageObj,
                    name: "img",
                    id,
                    x: x - imageObj.width / 8,
                    y: y - imageObj.height / 8,
                    draggable: true,
                    width: imageObj.width / 4,
                    height: imageObj.height / 4
                });

                // add cursor styling
                darthVaderImg.on("mouseover", function () {
                    document.body.style.cursor = "pointer";
                });
                darthVaderImg.on("mouseout", function () {
                    document.body.style.cursor = "default";
                });

                this.stage.find("Transformer").destroy();

                var tr = new Konva.Transformer();
                this.layer.add(darthVaderImg);
                this.layer.add(tr);
                tr.attachTo(darthVaderImg);
                this.selected = darthVaderImg;
                this.layer.draw();
            },
            remove() {
                if (!this.selected) return;
                this.itemsCounter--;
                let index = this.collectionItems.indexOf(this.selected.getId());
                this.collectionItems.splice(index, 1);
                this.selected.destroy();
                this.stage.find("Transformer").destroy();
                this.layer.draw();
            },
            forward() {
                this.stage.find("Transformer").destroy();
                this.selected.moveUp();
                this.selected = null;
                this.layer.draw();
            },
            backward() {
                this.stage.find("Transformer").destroy();
                this.selected.moveDown();
                this.selected = null;
                this.layer.draw();
            },
            flip() {
                this.selected.scaleX(this.flipBit);
                this.selected.x(
                    this.selected.x() + this.selected.width() * this.flipBit * -1
                );
                this.flipBit *= -1;

                this.stage.find("Transformer").destroy();

                var tr = new Konva.Transformer();
                this.layer.add(tr);

                tr.attachTo(this.selected);

                this.layer.draw();
            },
            copy() {
                let cloned = this.selected.clone();
                cloned.x(cloned.x() - 50);
                cloned.y(cloned.y() - 50);
                this.selected = cloned;
                this.layer.add(cloned);
                this.stage.find("Transformer").destroy();
                var tr = new Konva.Transformer();
                this.layer.add(tr);
                tr.attachTo(cloned);
                this.layer.draw();
                this.itemsCounter++;
            },
            publish() {
                this.stage.find("Transformer").destroy();
                this.layer.draw();
                this.selected = null;
                this.base64Img = this.stage.toDataURL();
                this.$router.push({query: {popup: "create_collection"}});
            }
        }
    };
</script>


<style scoped>
    .nameS {
        color: #4646fc;
        font-size: 1.2em;
        margin-top: -20px;
    }

    .createSetPage .leftArea .actionBtns .otherBtns .oneBtn {
        width: 100%;
    }

    .otherBtns {
        width: 60px;
    }
</style>
