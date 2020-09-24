<template>
    <div>
        <strong>DOMAIN INVASION</strong>
        <span v-if="di.event == 'soon'">
            Starting soon
        </span>
        <span v-if="di.event == 'very_soon'">
            Scouting party
        </span>
        <span v-if="di.event == 'boss_spawn'">
            <span class="at-o">&laquo;</span>Fighting right now!<span class="at-c">&raquo;</span>
        </span>
        <span v-if="di.event == 'boss_dead'">
            Just killed
        </span>
        &raquo; {{ di.zone }} &laquo;
        Killstreak: {{ di.killstreak }} /
        Total kills: {{ di.total_kills }}
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
