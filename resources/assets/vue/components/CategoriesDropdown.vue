<template>
    <select :id="id?id:''" :value="value" @change="$emit('change', $event.target.value)">
        <option v-if="loading" selected>Loading ....</option>
        <option value="0">Select category</option>
        <option v-if="!loading" v-for="item of items" :value="item.id" :key="item.id" v-html="item.label"></option>
    </select>
</template>
<script>
    export default {
        model: {prop: 'value', event: 'change'},
        props: ['id', 'value', 'options'],
        data() {
            return {
                items: [],
                loading: false,
            }
        },
        created() {
            this.loading = true;
            this.$store.dispatch("get_categories").then((data) => {
                const options = this.options || [];
                var newItems = [...options];
                for (let category in data) {
                    newItems.push({
                        id: data[category].id,
                        label: '&nbsp;' + data[category].title.trim()
                    })
                    const subCategories = data[category].subcategories || [];
                    for (let sub in subCategories) {
                        newItems.push({
                            id: subCategories[sub].id,
                            label: '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + subCategories[sub].title.trim()
                        })
                    }
                }
                this.items = newItems;
                this.loading = false;
            });
        }
    }
</script>