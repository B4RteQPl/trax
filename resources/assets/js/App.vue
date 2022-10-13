<template>
  <v-app>
    <v-navigation-drawer
      app
      :clipped="true"
    >
      <v-list dense>
        <v-list-tile
          @click="goTo('/trips')"
        >
          <v-list-tile-action>
            <v-icon>
              view_list
            </v-icon>
          </v-list-tile-action>

          <v-list-tile-content>
            <v-list-tile-title>
              Trips
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile
          @click="goTo('/cars')"
        >
          <v-list-tile-action>
            <v-icon>
              directions_car
            </v-icon>
          </v-list-tile-action>

          <v-list-tile-content>
            <v-list-tile-title>
              Cars
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>

    </v-navigation-drawer>
    <v-toolbar
      app
      fixed
      :clippedLeft="true"
    >
      <v-toolbar-title>
        <span class="hidden-sm-and-down">Trax</span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <span class="name">{{ name }}</span>
      <v-btn
        @click="goTo('/logout')"
      >Log Out
      </v-btn>
    </v-toolbar>

    <v-content>
      <v-container
        fluid
      >
        <router-view></router-view>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
import TripsView from './components/partials/TripsView.vue';

export default {
  components: {
    TripsView,
  },
  props: [],
  mounted() {
    this.fetchUserName();
  },
  data() {
    return {
      name: null
    }
  },
  methods: {
    fetchUserName() {
      axios.get('/api/user/')
        .then(response => {
          this.name = response.data.name;
        }).catch(e => {
        console.log(e);
      })
    },
    goTo(url) {
      if (url === '/logout') {
        axios.post(url, {})
          .then(() => {
            window.location.replace(location.protocol + '//' + location.host);
          })
          .catch(() => {
            window.location.replace(location.protocol + '//' + location.host);
          })
      } else {
        this.$router.push(url);
      }
    },
  },
}
</script>

<style scoped lang="scss">
.name {
  padding-right: 10px;
}
</style>
