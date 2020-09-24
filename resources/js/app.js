require('./bootstrap');

Vue.component('single-item-tracker', require('./components/SingleItemTracker.vue').default);

Vue.component('di-location', require('./components/DILocation.vue').default);

const app = new Vue({
        el: '#app',
 });
