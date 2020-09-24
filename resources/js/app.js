require('./bootstrap');

Vue.component('single-item-tracker', require('./components/SingleItemTracker.vue').default);

const app = new Vue({
        el: '#app',
 });
