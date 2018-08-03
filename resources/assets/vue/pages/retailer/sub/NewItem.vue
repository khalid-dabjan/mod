<template>
    <div class="gridContainer">
        <div class="secPaddLg">
            <form @submit.prevent="submit" action="#">
                <div class="myrow clearfix">
                    <div class="mycol-md-9 mycol-sm-6">
                        <div class="myrow clearfix">
                            <div class="mycol-md-6">
                                <div class="mrgBtmLg">
                                    <div class="mrgBtmMd fontLarger">Item title* :</div>
                                    <input required v-model="form.title" type="text" class="inputEle">
                                </div>
                            </div>
                            <div class="mycol-md-6">
                                <div class="mrgBtmLg">
                                    <div class="mrgBtmMd fontLarger">Category* :</div>
                                    <select required v-model="form.category" type="text" class="inputEle"
                                            placeholder="set category">
                                        <option hidden value="">...</option>
                                        <optgroup v-for="cat in mainCategories" :key="cat.id" :label="cat.title">
                                            <option v-for="subcat in cat.subcategories" :key="subcat.id"
                                                    :value="subcat.id">{{subcat.title}}
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="mycol-md-6">
                                <div class="mrgBtmLg">
                                    <div class="mrgBtmMd fontLarger">Short description* :</div>
                                    <input required v-model="form.description" type="text" class="inputEle">
                                </div>
                            </div>
                            <div class="mycol-md-6">
                                <div class="mrgBtmLg">
                                    <div class="mrgBtmMd fontLarger">Coverage* :</div>
                                    <select required v-model="form.coverage" class="inputEle">
                                        <option hidden value="">...</option>
                                        <option value="1">Low</option>
                                        <option value="2">Medium</option>
                                        <!-- <option value="3">high</option> -->
                                        <option value="4">Full</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mycol-md-6">
                                <div class="mrgBtmLg">
                                    <div class="mrgBtmMd fontLarger">Item Brand* :</div>
                                    <input required v-model="form.brand" type="text" class="inputEle">
                                </div>
                            </div>
                            <div class="mycol-md-6">
                                <div class="mrgBtmLg">
                                    <div class="mrgBtmMd fontLarger">Color* :</div>
                                    <select required v-model="form.color" type="text" class="inputEle"
                                            placeholder="set category">
                                        <option hidden value="">...</option>
                                        <option v-for="color of getColors" :key="color.id" :value="color.id">
                                            {{color.value}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mycol-md-6">
                                <div class="mrgBtmLg">
                                    <div class="mrgBtmMd fontLarger">Web shop url* :</div>
                                    <input required v-model="form.shop_url" type="text" class="inputEle">
                                </div>
                            </div>
                            <div class="mycol-md-6">
                                <div class="mrgBtmLg">
                                    <div class="mrgBtmMd fontLarger">Size* :</div>
                                    <vue-tags-input v-model="size" :tags="selectedSizes"
                                                    :autocompleteItems="getSizesFilterd" :addOnlyFromAutocomplete="true"
                                                    placeholder="Add Size"
                                                    @tags-changed="newTags => selectedSizes = newTags">
                                        <div slot="tagActions" slot-scope="props">
                                            &nbsp;
                                            <i @click="props.performDelete(props.item)" class="fa fa-close"></i>
                                        </div>
                                    </vue-tags-input>
                                </div>
                            </div>
                            <div class="mycol-md-6">
                                <div class="mrgBtmLg">
                                    <div class="mrgBtmMd fontLarger">Price* :</div>
                                    <input required v-model="form.price" type="text" class="inputEle">
                                </div>
                            </div>
                            <div class="mycol-md-6">
                                <div class="mrgBtmLg">
                                    <div class="mrgBtmMd fontLarger">Size System :</div>
                                    <select v-model="form.sizeSystem" class="inputEle">
                                        <option hidden value="">...</option>
                                        <option value="eu">EU</option>
                                        <option value="uk">UK</option>
                                        <option value="us">US</option>
                                        <option value="jp">JP</option>
                                        <option value="cn">CN</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mycol-md-6">
                                <div class="mrgBtmLg">
                                    <div class="mrgBtmMd fontLarger">Sale price :</div>
                                    <input v-model="form.sale_price" type="text" class="inputEle"
                                           placeholder="Ex: 15.00">
                                </div>
                            </div>
                            <div class="mycol-md-6">
                                <div class="mrgBtmLg">
                                    <div class="mrgBtmMd fontLarger">Currency* :</div>
                                    <select required v-model="form.currency" class="inputEle">
                                        <option hidden value="">...</option>
                                        <option v-for="curr of currency" :key="curr" :value="curr">{{curr}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mycol-md-3 mycol-sm-6">
                        <div class="mrgBtmMd fontLarger">Image</div>
                        <label for="uploadImg" class="inputEle brandBg vCenter textCentered mrgBtmLg"> Upload
                            Image </label>
                        <input type="file" id="uploadImg" class="disNone" @change="processFile($event)">
                        <div class="uploadedPhotoDisplay mrgBtmLg">
                            <span v-if="form.image === ''" class="fontLarger grayColor hideAfterUpload">No photo</span>
                            <img :src="form.image" alt="">
                        </div>
                    </div>
                </div>
                <div class="myrow clearfix">
                    <div class="mycol-md-9 mycol-sm-6">
                        <div class="myrow clearfix">
                            <div class="mycol-md-6">
                                <div v-for="error in errors" :key="error">
                                    <h4 class="errors">
                                        {{error}}
                                    </h4>
                                    <br/>
                                </div>
                            </div>
                            <div class="mycol-md-6">
                                <div class="mrgBtmMd fontLarger">&nbsp;</div>
                                <div class="clearfix">
                                    <router-link to="/retailer/allitems" class="BF_btn">Cancel</router-link>
                                    <button type="submit" class="BF_btn sbmt"> {{ sending ? 'Loading...' : 'Submit' }}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
        <Loading v-if="loadig"/>
    </div>
</template>

<script>
    import {currency} from "./currency";
    import Loading from "@/components/Loading";
    import VueTagsInput from "@johmun/vue-tags-input";
    import {mapGetters} from "vuex";

    export default {
        name: "new-item",
        components: {
            Loading,
            VueTagsInput
        },
        data() {
            return {
                form: {
                    title: "",
                    description: "",
                    color: "",
                    category: "",
                    brand: "",
                    shop_url: "http://",
                    price: "",
                    sale_price: "",
                    size: "",
                    coverage: "",
                    sizeSystem: "",
                    currency: "",
                    image: ""
                },
                loadig: true,
                sending: false,
                errors: [],
                selectedSizes: [],
                selectedColors: [],
                size: "",
                currency
            };
        },
        computed: {
            ...mapGetters(["getColors", "getSizes", "mainCategories"]),
            getSizesFilterd() {
                return (
                    this.getSizes
                        .map(size => ({text: size || "none"}))
                        .filter(i => new RegExp(this.size, "i").test(i.text)) || []
                );
            }
        },
        created() {
            Promise.all([
                this.$store.dispatch("get_colors"),
                this.$store.dispatch("get_sizes"),
                this.$store.dispatch("get_categories")
            ]).then(() => {
                this.loadig = false;
            });
        },
        methods: {
            // process image and conver it to base64
            processFile(e) {
                let input = e.target;
                if (input.files && input.files[0]) {
                    let reader = new FileReader();
                    reader.onload = e => {
                        this.form.image = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            },
            submit() {
                this.sending = true;
                // check on image
                if (!this.form.image) {
                    this.sending = false;
                    this.errors.push("Item image is required");
                    return;
                }
                // arrays (selectedSizes and selectedColors) to comma separated
                this.form.size = this.selectedSizes.map(i => i.text).join(",");
                // this.form.color = this.selectedColors.map(c => c.id).join(",");

                // Send Form Data
                this.$store
                    .dispatch("add_new_item", this.form)
                    .then(() => {
                        this.sending = false;
                        this.$router.push("/retailer/allitems");
                        window.location.reload();
                    })
                    .catch(err => {
                        if (err.response && err.response.data.errors) {
                            this.errors = err.response.data.errors;
                            this.sending = false;
                        } else {
                            this.sending = false;
                            this.$router.push("/500");
                        }
                    });
            },
            colorBlockStyle(color) {
                return 'height:20px; width:30px; background:' + color + '; display:inline-block; margin:3px; border:1px solid #000; margin-top:10px';
            },
            toggleSelectColor(colorId) {

                //for multi color select
                // if(this.selectedColors.find(c => c.id == colorId)){
                // 	this.selectedColors = this.selectedColors.filter(c => c.id != colorId);
                // }else{
                // 	this.selectedColors.push(this.getColors.find( c => c.id == colorId));
                // }

                // ## to force selecting 1 color
                this.selectedColors = [];
                this.selectedColors.push(this.getColors.find(c => c.id == colorId));
            }
        }
    };
</script>

<style>
    .inputEle {
        background: #ffffff;
    }

    .errors {
        color: red;
    }

    .vue-tags-input {
        max-width: 100% !important;
    }

    .vue-tags-input .input {
        height: 40px !important;
        border: #fff !important;
    }

    .tag.valid {
        background: #2b2a2a !important;
        font-size: 1em;
    }

    .item.valid.selected-item {
        background-color: #000;
    }

    .retailer-dropdown {
        position: relative;
        width: 100%;
        display: inline-block;
    }

    .retailer-dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        padding: 12px 16px;
        z-index: 1;
    }

    .retailer-dropdown:hover .retailer-dropdown-content {
        display: block;
    }
</style>
