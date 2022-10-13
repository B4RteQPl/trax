import Vue from 'vue';

require('./bootstrap');

import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import VueRouter from 'vue-router'
import App from './App.vue'
import routes from './routes';

// Components
import TripsView from './components/partials/TripsView.vue';
import CarsView from './components/partials/CarsView.vue';
import CarView from './components/partials/CarView.vue';
import NewCarView from './components/partials/NewCarView.vue';
import NewTripView from './components/partials/NewTripView.vue';

Vue.use(VueRouter)
Vue.use(Vuetify)

Vue.component('trips-view', TripsView);
Vue.component('cars-view', CarsView);
Vue.component('car-view', CarView);
Vue.component('new-car-view', NewCarView);
Vue.component('new-trip-view', NewTripView);

// Create Router
const router = new VueRouter({
  routes: routes(Vue),
})

new Vue({
  el: '#app',
  router,
  render: h => h(App),
});
