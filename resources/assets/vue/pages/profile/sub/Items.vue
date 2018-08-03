<template>
	<div class="gridContainer">
		<WrapperCardList>
			<div v-for="(item,index) in Items" :key='index' class="mycol-lg-3 mycol-sm-6">
                <ItemCard
                    :image="item.image"
                    :price="item.price"
                    :title="item.title"
                    :link="item.link"
                    :like="item.like"
                    :comment="item.comment"
                />
			</div>
		</WrapperCardList>
	</div>
</template>

<script>
import ItemCard from "@/components/ItemCard";
import WrapperCardList from "@/wrappers/WrapperCardList";

export default {
  components: {
    ItemCard,
    WrapperCardList
  },
  computed: {
    Items() {
      let arr = [];
      const Items = [
        {
          url: "",
          title: "VELVET PUMPS WITH BEJEWELED HEELS",
          image: "images/4.jpg",
          price: 520,
          url: "",
          like: 10,
          comment: 10,
          link: "dolcegabbana.com",
          by: "Modasti retail - Modasti"
        },
        {
          url: "",
          title: "VELVET PUMPS WITH BEJEWELED HEELS",
          image: "images/3.jpg",
          price: 320,
          url: "",
          like: 20,
          comment: 11,
          link: "dolcegabbana.com",
          by: "Modasti retail - Modasti"
        },
        {
          url: "",
          title: "VELVET PUMPS WITH BEJEWELED HEELS",
          image: "images/1.jpg",
          price: 120,
          url: "",
          like: 5,
          comment: 2,
          link: "dolcegabbana.com",
          by: "Modasti retail - Modasti"
        }
      ];

      for (let i = 0; i < 8; i++) {
        arr.push(Items[Math.floor(Math.random() * 3)]);
      }
      return arr;
    }
  }
};
</script>

