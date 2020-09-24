<template>
    <div>
        {{ di.location }}
    </div>
</template>

<script>
    export default {

    data() {
        return {
            di: []
        }
    },

    created() {
        this.getDIData();

        Echo.channel('item-updated-channel').listen(".di-updated", e => {
            console.log('Event received');
            this.getDIData();
        });

    },

    methods: {
        getDIData() {
            console.log('Fetching DI data');
            axios.get(`/di/get`)
                .then(response => {
                    this.di = response.data;
                });
        }
    }
};
</script>
