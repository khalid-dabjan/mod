<template>
    <select :id="id?id:''" :value="value" @change="$emit('change', $event.target.value)">
        <option v-if="loading" selected>Loading ....</option>
        <option value="0">Select color</option>
        <option  v-if="!loading" v-for="item of items" :value="item.id" :key="item.id" v-html="item.label"></option>
    </select>
</template>
<script>
    export default {
        model: {prop: 'value', event: 'change'},
        props: ['id', 'value','options'],
        data() {
            return {
                items: [],
                loading: false,
            }
        },
        created() {
            this.loading = true;
            this.$store.dispatch("get_colors").then((data) => {
                const options=this.options || [];
                var newItems=[ ...options];
                for(let color of data){
                    newItems.push({
                        id:color.id,
                        label:'&nbsp;'+color.name.trim()
                    })

                }
                this.items=newItems;
                this.loading = false;
            });
        }
    }
</script>