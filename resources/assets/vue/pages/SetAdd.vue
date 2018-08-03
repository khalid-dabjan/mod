<template>
  <div class="gridContainer">
    <div class="createSetPage secPaddLg clearfix">
      <div class="leftArea">
        <div class="areaToDrop">
          <div class="intialText" v-if="itemsCounter == 0">Drag sets or items here</div>
          <div @drop="drop" ref="droparea" @dragover.prevent="nothing" id="droparea" style="background:#fff; height:100%; width:100%;"></div>
        </div>
        <div class="actionBtns">
          <a href="#" @click.prevent="publish" class="publishBtn">Publish</a>
          <div class="otherBtns">
            <div class="oneBtn">
              <a @click.prevent="forward" href="#">
                <i class="fa fa-arrow-circle-up"></i>
              </a>
            </div>
            <div class="oneBtn">
              <a @click.prevent="backward" href="#">
                <i class="fa fa-arrow-circle-down"></i>
              </a>
            </div>
            <div class="oneBtn">
              <a @click.prevent="flip" href="#">
                <i class="icon-flip"></i>
              </a>
            </div>
            <div class="oneBtn">
              <a @click.prevent="copy" href="#">
                <i class="icon-changeindex"></i>
              </a>
            </div>
            <div class="oneBtn">
              <a @click.prevent="remove" href="#">
                <i class="fa fa-trash"></i>
              </a>
            </div>
            <div class="oneBtn colorPi">
              <a @click.prevent="newBackground" href="#">
                <i class="fa fa-paint-brush"></i>
              </a>
              <Chrome :value="'#fff'" @input="backgroundChange" />
            </div>
          </div>
        </div>
      </div>
      <div class="rightArea">
        <div class="theTabs">
          <CategoriesDropdown id="set-select-categories" v-model="category" @change="changeCategory" :options="[{id:'liked_items',label:'Liked item'}]"></CategoriesDropdown>
          <input id="set-item-search" type="search" col="50" @input="searchItems" v-model.trim="query" placeholder="Search for item ..." />
        </div>

        <div class="theProducts">
          <div class="myrow clearfix">

            <div v-if="!loading&&(category!==0||query!=='')">
              <div v-for="(item) of items" :key="item.id" class="mycol-sm-4">
                <div @dragstart="dragStart" draggable="true" :src="item['photos'][0]['photo_name']" :data-id="item.id" class="one">
                  <div class="avatar">
                    <div class="verticalCentered">
                      <div class="theCell"><img :src="item['photos'][0]['photo_name']" :data-id="item.id" alt=""></div>
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
                    <div class="avatar"  draggable="false">
                      <div class="verticalCentered" draggable="false">
                        <div class="theCell"><img draggable="false" :src="item['photo']"></div>
                      </div>
                    </div>
                    <div class="name nameS">{{item.name}}</div>
                  </div>
                </a>
              </div>
            </div>

            <div v-if="loading" class="set-loading"><img src="images/loading.gif" width="50px" alt="loading"></div>
            <div v-if="items&&items.length==0&&!loading" class="set-no-found">No found items</div>

          </div>
        </div>
      </div>
    </div>
    <transition name="popups" enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
      <WrapperPopups v-if="$route.query.popup && $store.getters.isAuth">
        <SetCollectionAddPopup v-if="$route.query.popup=='create_set'" submit-type="set" :me="me" :base64-img="base64Img" :items="drawedItems" :background="background" />
      </WrapperPopups>
    </transition>
    <!--<Loading v-if="loading" />-->
  </div>
</template>
<script>
import Knova from "konva";
import Loading from "@/components/Loading";
import CategoriesDropdown from "@/components/CategoriesDropdown";
import ColorDropdown from "@/components/ColorDropdown";
import WrapperPopups from "@/wrappers/WrapperPopups";
import SetCollectionAddPopup from "@/layout/popups/SetCollectionAddPopup";
import { Chrome } from "vue-color";
import _ from "lodash";
import { mapGetters } from "vuex";

// vue Components
var $vm = null;

export default {
  components: {
    Loading,
    SetCollectionAddPopup,
    WrapperPopups,
    CategoriesDropdown,
    ColorDropdown,
    Chrome
  },

  data() {
    return {
      me: this,
      stage: {},
      layer: {},
      selected: null,
      itemsCounter: 0,
      flipBit: -1,
      base64Img: "",
      setItems: [],
      loading: true,
      background: "#fff",
      loadMoreLoading: false,
      query: "",
      category: 0,
      color: 0,
      drawedItems: []
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
    var width = this.$refs.droparea.offsetWidth - 20;
    var height = this.$refs.droparea.offsetHeight - 20;
    this.stage = new Konva.Stage({
      container: "droparea",
      width: width,
      height: height
    });
    this.stageRect = new Konva.Rect({
      x: 0,
      y: 0,
      width: width,
      height: height,
      fill: "#fff",
      draggable: false,
      listen: false
    });
    this.layer = new Konva.Layer();
    this.stage.add(this.layer);
    this.layer.add(this.stageRect);

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
    nothing() {},
    dragStart(event) {
      event.dataTransfer.setData(
        "item",
        JSON.stringify({
          src: event.target.src,
          id: event.target.getAttribute("data-id")
        })
      );
    },
    searchItems: _.debounce(event => {
        $vm.loading = true;
      console.log($vm.category);
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
    drop(event) {
      event.preventDefault();
      let item = JSON.parse(event.dataTransfer.getData("item"));
      this.itemsCounter++;
      this.setItems.indexOf(item.id) === -1
        ? this.setItems.push(item.id)
        : null;
      let img = new Image();
      img.onload = () => {
        this.drawImage(img, item.id, event.offsetX, event.offsetY);
      };
      img.src = item.src;
      img.dataset.itemId = item.id;
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
        height: imageObj.height / 4,
        itemId: imageObj.dataset.itemId
      });
      // add cursor styling
      darthVaderImg.on("mouseover", function() {
        document.body.style.cursor = "pointer";
      });
      darthVaderImg.on("mouseout", function() {
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
      let index = this.setItems.indexOf(this.selected.getId());
      this.setItems.splice(index, 1);
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
      this.$router.push({ query: { popup: "create_set" } });
      this.drawedItems = this.stage.find("Image").map(function(image) {
        return {
          item_id: image.attrs.itemId,
          x: image.attrs.x,
          y: image.attrs.y,
          height: image.attrs.height*image.attrs.scaleY,
          width: image.attrs.width*image.attrs.scaleX,
            rotation: image.attrs.rotation,
        };
      });
      console.log(this.drawedItems);
      this.base64Img = this.stage.toDataURL();
    },
    backgroundChange(color) {
      this.stageRect.setFill(color.hex || "#fff");
      this.layer.draw();
      this.background = color.hex;
    }
  }
};
</script>

<style scoped>
.createSetPage .leftArea .actionBtns .otherBtns .oneBtn {
  width: 16.66666666%;
}
.vc-chrome {
  display: none;
  position: absolute;
  z-index: 200;
}
.colorPi:hover .vc-chrome {
  display: block;
}

.vc-chrome-alpha-wrapper {
  display: none;
}

.avatar{
  height: 210px !important;
}

.nameS{
  color: #4646fc;
  font-size: 1.2em;
  margin-top: -20px;
}
</style>
