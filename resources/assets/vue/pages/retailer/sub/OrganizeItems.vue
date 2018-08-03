<template>
	<div class="organizeItemsPage">

		<div class="gridContainer">
			<div class="myTable">

				<div class="tableContainer">
					<button :disabled="page === lastPage" class="topHeadBtn nextprev" @click.prevent="next">></button>
					<button :disabled="page === 0" class="topHeadBtn nextprev" @click.prevent="prev"><</button>
					<table>
						<tbody>
							<tr>
								<th>&nbsp;</th>
								<th>Status</th>
								<th>Name</th>
								<th>brand</th>
								<th>description</th>
								<th>category</th>
								<th>shop</th>
							</tr>
							<tr v-for="item in items" :key="item.id">
								<td>
									<div class="mrgBtmSm">
										<router-link :to="'/retailer/edit/'+item.id" class="brandColor">Edit</router-link>
									</div>
									<div>
										<a href="#" :ref="item.id" @click.prevent="deleteItem(item.id)" class="brandColor">delete</a>
									</div>
								</td>
								<td>{{item.status?'Confirmed':'Pending'}}</td>
								<td>{{item.title}}</td>
								<td>{{item.brand.title}}</td>
								<td>{{item.content}}</td>
								<td>{{item.categories[0].name}}</td>
								<td>
									<a :href="item.url">URL</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<Loading v-if="loading" />
	</div>
</template>

<script>
import Loading from "@/components/Loading";
export default {
  components: {
    Loading
  },
  data() {
    return { loading: true, page: 0, nextLoading: false, lastPage: null };
  },
  computed: {
    items() {
      let start = this.page * 8;
      return this.$store.getters.retailerItems.slice(start, start + 8);
    }
  },
  created() {
    this.loadItems();
  },
  methods: {
    next() {
      if (this.page !== this.lastPage) {
        this.page++;
        this.loadItems().then(() => {
          if (this.items.length < 8) {
            this.lastPage = this.page;
            if (this.items.length === 0) this.page--;
          }
        });
      }
    },
    prev() {
      if (this.page > 0) this.page--;
    },
    deleteItem(id) {
      this.$store
        .dispatch("delete_item", id)
        .then(() => {})
        .catch(err => {
          console.error(err);
          this.$router.push("/500");
        });
    },
    loadItems() {
      this.loading = true;
      return this.$store
        .dispatch("get_all_items")
        .then(() => (this.loading = false));
    }
  }
};
</script>

<style>
.nextprev{
	width: 50px;
	height: 60px;
	font-weight: 900;
	font-family: Geneva, Verdana, sans-serif;
}
.nextprev:disabled {
  background-color: #3b3b3b;
}
</style>
