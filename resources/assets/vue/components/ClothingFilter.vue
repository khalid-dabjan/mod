<template>
  <div>
    <div class="sectionTitle clearfix">
      <router-link :to="$route.params.subCat ? '/category/'+$route.params.name : ''">
        <h2 class="theName">{{$route.params.subCat ? 'Back to ':''}}{{$route.params.name}}</h2>
      </router-link>
      <div class="filters">
        <span @click="mfilter=true" class="icon">
          <i class="fa fa-sliders"></i>
        </span>
        <div class="in">
          <div class="one">
            <div @click="showOptions('colors')" class="title dropdownTitle">Colors
              <i class="fa fa-angle-down"></i>
            </div>
          </div>
          <div class="one">
            <div @click="showOptions('coverage')" class="title dropdownTitle">Coverage
              <i class="fa fa-angle-down"></i>
            </div>
          </div>
          <div class="one">
            <div @click="showOptions('priceOrder')" class="title dropdownTitle">Price
              <i class="fa fa-angle-down"></i>
            </div>
          </div>
          <div class="one">
            <div @click="showOptions('sizes')" class="title dropdownTitle">Size
              <i class="fa fa-angle-down"></i>
            </div>
          </div>
          <div class="one">
            <div @click="showOptions('brands')" class="title dropdownTitle">Brand
              <i class="fa fa-angle-down"></i>
            </div>
          </div>
          <a href="#" @click.prevent="reset" class="theBtn" style="background:#000; color : #fff" >Reset</a>
          <a href="#" @click.prevent="apply" class="theBtn">Apply</a>
        </div>
      </div>
    </div>
    <transition name="filter-ops">
      <div v-if="filter" class="topCategories whiteBg filterops">
        <div class="gridContainer">
          <div v-if="filter==='colors'">
            <!-- <div v-for="val in vals" :key="val.id" @click.prevent="toggleFilter(val.id)" :style="colorBlockStyle(val.id)"></div> -->
            <a v-for="val in vals" :key="val.id" @click.prevent="toggleFilter(val.id)" :class="{'filteri':true ,'selected' : filters[filter][val.id]&&filters[filter][val.id].isSelected }" href="#">{{val.name}}</a>
          
          </div>
          <div v-else>
            <a v-for="val in vals" :key="val.id" @click.prevent="toggleFilter(val.id)" :class="{'filteri':true ,'selected' : filters[filter][val.id]&&filters[filter][val.id].isSelected }" href="#">{{filter==='sizes'?val.id:val.title}}</a>
          </div>
        </div>
      </div>
    </transition>
    <div v-if="mfilter" class="filtersMobileMenu">
      <ClothingFilterMobile>
        <div class="submitDiv">
          <input @click.prevent="apply" type="submit" value="apply">
          <a href="#" @click.prevent="reset" type="submit" style="background:#000; color : #fff" >Reset</a>
          <input @click.prevent="mfilter =false" type="submit" value="Cancel" style="background:#000;color:#fff;">
        </div>
      </ClothingFilterMobile>
    </div>
    <Loading v-if="loading" />
  </div>
</template>

<script>
import ClothingFilterMobile from "@/components/ClothingFilterMobile";
import Loading from "@/components/Loading";
import { mapGetters } from "vuex";
export default {
  components: {
    ClothingFilterMobile,
    Loading
  },
  data() {
    return {
      filter: "",
      mfilter: false,
      loading: false
    };
  },
  computed: {
    ...mapGetters([
      "filters",
      "colors",
      "sizes",
      "brands",
      "coverage",
      "priceOrder"
    ]),
    vals() {
      return this[this.filter];
    }
  },
  methods: {
    showOptions(filter) {
      if (this.filter == filter) this.filter = "";
      else this.filter = filter;
    },
    toggleFilter(id) {
      if (this.filter === "priceOrder") {
        if (this.filters[this.filter][id].isSelected) {
          this.remove(id);
        } else {
          this.add(id);
          this.remove(id == 1 ? 2 : 1);
        }
      } else if (
        this.filters[this.filter][id] &&
        this.filters[this.filter][id].isSelected
      )
        this.remove(id);
      else this.add(id);
    },
    add(id) {
      this.$store.commit("ADD_FILTER", {
        filter: this.filter,
        id
      });
    },
    remove(id) {
      this.$store.commit("REMOVE_FILTER", {
        filter: this.filter,
        id
      });
    },
    colorBlockStyle(id) {
      return (
        "height:20px; width:30px; background:" +
        this.filters[this.filter][id].value +
        "; display:inline-block; margin:3px; margin-top:10px;" +
        (this.filters[this.filter][id].isSelected
          ? "border:2px solid #000;"
          : "border:1px solid #b3b3b3;")
      );
    },
    apply() {
      this.loading = true;
      this.mfilter = false;
      this.$store.dispatch("applyFilters");
      this.$store.dispatch("get_category_items").then(() => {
        this.loading = false;
      });
    },
    reset(){
      this.loading = true;      
      this.$store.commit("RESET_FILTERS");
      this.$store.dispatch("map_filters");
      this.$store.dispatch("applyFilters");
      this.$store.dispatch("get_category_items").then(() => {
        this.loading = false;
      });
    }
  }
};
</script>

<style scoped>
.filter-ops-enter,
.filter-ops-leave-to {
  max-height: 0px;
}

.filter-ops-leave,
.filter-ops-enter-to {
  max-height: 800px;
}

.filter-ops-enter-active {
  transition: 800ms cubic-bezier(0.17, 0.04, 0.03, 0.94);
}
.filter-ops-leave-active {
  transition: 300ms cubic-bezier(0.17, 0.14, 0.73, 0.94);
}

.selected {
  color: #181717;
  opacity: 1;
  border-width: 2px !important;
}
.filteri {
  width: auto;
  border: 1px solid #000;
  padding: 4px;
  min-width: 60px;
  text-align: center;
}
</style>
