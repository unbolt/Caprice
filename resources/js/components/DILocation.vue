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
        console.log('This has been created!');

        this.getDIData();

        Echo.channel('item-updated-channel').listen(".di-updated", e => {
            this.getDIData();
        });

    },

    methods: {
        getDIData() {
            axios.get(`/di/get`)
                .then(response => {
                    this.di = response.data;
                });
        }
    }
};
</script>
