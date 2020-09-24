<template>
    <div>
        {{ item_data.name }}
        x{{ item_data.qty }}
    </div>
</template>

<script>
    export default {
        props: ['item_name'],

    data() {
        return {
            item_data: []
        }
    },

    created() {
        this.getItemData();

        Echo.channel('item-updated-channel').listen(".item-updated", e => {
            this.getItemData();
        });

    },

    methods: {
        getItemData() {
            axios.get(`/items/` + this.item_name)
                .then(response => {
                    this.item_data = response.data;
                });
        }
    }
};
</script>
