<template>
  <div class="gridContainer">
    <form action="#">
      <div class="one" :class="{'opened':filter==='colors'}">
        <a href="#" class="theTab" @click.prevent="showOptions('colors')">
          <span>Colors</span>
          <i class="fa fa-angle-down"></i>
        </a>
        <div class="thesubTab">
            <a v-for="val in vals" :key="val.id" @click.prevent="toggleFilter(val.id)" :class="{'filteri':true ,'selected' : filters[filter][val.id]&&filters[filter][val.id].isSelected }" href="#">{{val.name}}</a>
        </div>
      </div>
      <div class="one" :class="{'opened':filter==='coverage'}">
        <a href="#" class="theTab" @click.prevent="showOptions('coverage')">
          <span>coverage</span>
          <i class="fa fa-angle-down"></i>
        </a>
        <div class="thesubTab">
          <a v-for="val in vals" :key="val.id" @click.prevent="toggleFilter(val.id)" class="filteri" :class="{'selected' : filters[filter][val.id]&&filters[filter][val.id].isSelected }" href="#">{{filter==='sizes'?val.id:val.title}}</a>
        </div>
      </div>
      <div class="one" :class="{'opened':filter==='sizes'}">
        <a href="#" class="theTab" @click.prevent="showOptions('sizes')">
          <span>sizes</span>
          <i class="fa fa-angle-down"></i>
        </a>
        <div class="thesubTab">
          <a v-for="val in vals" :key="val.id" @click.prevent="toggleFilter(val.id)" class="filteri" :class="{'selected' : filters[filter][val.id]&&filters[filter][val.id].isSelected }" href="#">{{filter==='sizes'?val.id:val.title}}</a>
        </div>
      </div>
      <div class="one" :class="{'opened':filter==='priceOrder'}">
        <a href="#" class="theTab" @click.prevent="showOptions('priceOrder')">
          <span>price</span>
          <i class="fa fa-angle-down"></i>
        </a>
        <div class="thesubTab">
          <a v-for="val in vals" :key="val.id" @click.prevent="toggleFilter(val.id)" class="filteri" :class="{'selected' : filters[filter][val.id]&&filters[filter][val.id].isSelected }" href="#">{{filter==='sizes'?val.id:val.title}}</a>
        </div>
      </div>
      <div class="one" :class="{'opened':filter==='brands'}">
        <a href="#" class="theTab" @click.prevent="showOptions('brands')">
          <span>Brand</span>
          <i class="fa fa-angle-down"></i>
        </a>
        <div class="thesubTab">
          <a v-for="val in vals" :key="val.id" @click.prevent="toggleFilter(val.id)" class="filteri" :class="{'selected' : filters[filter][val.id]&&filters[filter][val.id].isSelected }" href="#">{{filter==='sizes'?val.id:val.title}}</a>
        </div>
      </div>
      <slot/>
    </form>
  </div>
</template>



<script>
import ClothingFilterMobile from "@/components/ClothingFilterMobile";
import { mapGetters } from "vuex";
export default {
  components: {
    ClothingFilterMobile
  },
  data() {
    return {
      filter: "colors",
      mfilter: false
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
    }
  }
};
</script>

<style scoped>
.filteri.selected {
  color: #181717;
  opacity: 1;
  border-width: 2px !important;
}
.filteri {
  opacity: 0.6;
  width: auto;
  border: 1px solid #000;
  padding: 10px;
  margin: 12px;
  margin-left: 0;
  display: inline-block;
  min-width: 70px;
  text-align: center;
}
.one {
  margin-top: 35px;
}
.fa {
  font-size: 1.4em;
}
</style>
