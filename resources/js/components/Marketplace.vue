<template>
  <div class="container">
    <v-card flat>
      <v-btn small outlined absolute right class="text-decoration-none" to="/buildstore">
        <v-icon class="mr-2">mdi-storefront</v-icon>Build a Store
      </v-btn>
      <v-card-title class="font-weight-bold">Top Stores</v-card-title>
      <v-carousel height="220" cycle continuous hide-delimiters show-arrows-on-hover>
        <v-carousel-item
          v-for="(item,i) in getAllStores.data"
          :key="i"
          :src="'storage/store_cover/' + item.cover"
        >
          <div class="pt-2 pl-2">
            <v-avatar size="150" tile>
              <v-img v-if="getAllStores" :src="'storage/store_photo/' + item.photo"></v-img>
            </v-avatar>
          </div>
          <v-list-item color="rgba(0, 0, 0, .4)" dark>
            <v-list-item-content>
              <v-list-item-title class="headline mb-1">{{item.name}}</v-list-item-title>
              <v-list-item-subtitle>{{item.street + ' | ' + item.city + ', ' + item.country}}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-carousel-item>
      </v-carousel>

      <v-card flat>
        <v-card-title class="font-weight-bold">Featured</v-card-title>
        <v-row>
          <v-col cols="6" md="3">
            <v-card flat tile class="d-flex">
              <v-img src="storage/app_photos/featured-supplies.jpg" class="grey lighten-2">
                <template v-slot:placeholder>
                  <v-row class="fill-height ma-0" align="center" justify="center">
                    <v-progress-circular indeterminate color="success"></v-progress-circular>
                  </v-row>
                </template>
              </v-img>
            </v-card>
            <v-card-title class="subtitle-2 mx-auto">Farming Supplies</v-card-title>
          </v-col>
          <v-col cols="6" md="3">
            <v-card flat tile class="d-flex mx-auto">
              <v-img
                src="storage/app_photos/featured-agriculture.jpg"
                class="grey lighten-2"
                aspect-ratio="1"
              >
                <template v-slot:placeholder>
                  <v-row class="fill-height ma-0" align="center" justify="center">
                    <v-progress-circular indeterminate color="success"></v-progress-circular>
                  </v-row>
                </template>
              </v-img>
            </v-card>
            <v-card-title class="subtitle-2 mx-auto">Agriculture</v-card-title>
          </v-col>
          <v-col cols="6" md="3">
            <v-card flat tile class="d-flex">
              <v-img src="storage/app_photos/featured-aquaculture.png" class="grey lighten-2">
                <template v-slot:placeholder>
                  <v-row class="fill-height ma-0" align="center" justify="center">
                    <v-progress-circular indeterminate color="success"></v-progress-circular>
                  </v-row>
                </template>
              </v-img>
            </v-card>
            <v-card-title class="subtitle-2 mx-auto">Aquaculture</v-card-title>
          </v-col>
          <v-col cols="6" md="3">
            <v-card flat tile class="d-flex">
              <v-img
                src="storage/app_photos/featured-livestocks.jpg"
                class="grey lighten-2"
                aspect-ratio="1"
              >
                <template v-slot:placeholder>
                  <v-row class="fill-height ma-0" align="center" justify="center">
                    <v-progress-circular indeterminate color="success"></v-progress-circular>
                  </v-row>
                </template>
              </v-img>
            </v-card>
            <v-card-title class="subtitle-2">Livestocks</v-card-title>
          </v-col>
        </v-row>
      </v-card>
      <v-tabs
        v-model="tab"
        show-arrows
        centered
        center-active
        class="mt-4"
        background-color="transparent"
        fixed-tabs
        slider-color="white"
      >
        <v-tab>
          <v-chip class="ma-3 pa-4" pill>Mixed</v-chip>
        </v-tab>
        <v-tab>
          <v-chip color="success" pill>
            <v-avatar left>
              <v-img src="storage/icons/vege.png"></v-img>
            </v-avatar>Vegetables
          </v-chip>
        </v-tab>
        <v-tab>
          <v-chip color="purple" dark pill>
            <v-avatar left>
              <v-img src="storage/icons/fruits.png"></v-img>
            </v-avatar>Fruits
          </v-chip>
        </v-tab>
        <v-tab>
          <v-chip dark color="cyan darken-2" pill>
            <v-avatar left>
              <v-img src="storage/icons/animals.png" alt="Message User Image"></v-img>
            </v-avatar>Livestocks
          </v-chip>
        </v-tab>
        <v-tab>
          <v-chip dark color="brown" pill>
            <v-avatar left>
              <v-img src="storage/icons/fertilizer.png"></v-img>
            </v-avatar>Chemicals
          </v-chip>
        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab">

        <v-tab-item>
          <v-data-iterator :items="getLatestProducts" :server-items-length="getLatestProducts.length">
            <template v-slot:default="props">
              <v-row dense>
                <v-col cols="6" md="3" v-for="item in props.items" :key="item.id" class="mb-2">
                  <v-hover v-slot:default="{ hover }">
                    <v-card :elevation="hover ? 5 : 0" flat v-ripple class="mr-2">
                      <router-link :to="'/view-product/' + item.id" class="text-decoration-none">
                        <v-img :src="'storage/product_photo/' + item.photos[0].file"></v-img>
                        <v-list-item three-line>
                          <v-list-item-content>
                            <v-list-item-title
                              class="font-weight-bold red--text"
                            >₱ {{item.price}}/{{item.unit}}</v-list-item-title>
                            <v-list-item-title>{{item.name}}</v-list-item-title>
                            <v-list-item-subtitle>Stocks ({{item.stocks + ' ' + item.unit}})</v-list-item-subtitle>
                          </v-list-item-content>
                        </v-list-item>
                      </router-link>
                    </v-card>
                  </v-hover>
                </v-col>
              </v-row>
            </template>
          </v-data-iterator>
        </v-tab-item>

        <v-tab-item>
          <v-data-iterator :items="getLatestVegetables">
            <template v-slot:default="props">
              <v-row dense>
                <v-col cols="6" md="3" v-for="item in props.items" :key="item.id" class="mb-2">
                  <v-hover v-slot:default="{ hover }">
                    <v-card :elevation="hover ? 5 : 0" flat v-ripple class="mr-2">
                      <router-link :to="'/view-product/' + item.id" class="text-decoration-none">
                        <v-img :src="'storage/product_photo/' + item.photos[0].file"></v-img>
                        <v-list-item three-line>
                          <v-list-item-content>
                            <v-list-item-title
                              class="font-weight-bold red--text"
                            >₱ {{item.price}}/{{item.unit}}</v-list-item-title>
                            <v-list-item-title>{{item.name}}</v-list-item-title>
                            <v-list-item-subtitle>Stocks ({{item.stocks + ' ' + item.unit}})</v-list-item-subtitle>
                          </v-list-item-content>
                        </v-list-item>
                      </router-link>
                    </v-card>
                  </v-hover>
                </v-col>
              </v-row>
            </template>
          </v-data-iterator>
        </v-tab-item>

        <v-tab-item>
          <v-data-iterator :items="getLatestFruits">
            <template v-slot:default="props">
              <v-row dense>
                <v-col cols="6" md="3" v-for="item in props.items" :key="item.id" class="mb-2">
                  <v-hover v-slot:default="{ hover }">
                    <v-card :elevation="hover ? 5 : 0" flat v-ripple class="mr-2">
                      <router-link :to="'/view-product/' + item.id" class="text-decoration-none">
                        <v-img :src="'storage/product_photo/' + item.photos[0].file"></v-img>
                        <v-list-item three-line>
                          <v-list-item-content>
                            <v-list-item-title
                              class="font-weight-bold red--text"
                            >₱ {{item.price}}/{{item.unit}}</v-list-item-title>
                            <v-list-item-title>{{item.name}}</v-list-item-title>
                            <v-list-item-subtitle>Stocks ({{item.stocks + ' ' + item.unit}})</v-list-item-subtitle>
                          </v-list-item-content>
                        </v-list-item>
                      </router-link>
                    </v-card>
                  </v-hover>
                </v-col>
              </v-row>
            </template>
          </v-data-iterator>
        </v-tab-item>

        <v-tab-item>xxxx</v-tab-item>
        <v-tab-item>xxx</v-tab-item>
      </v-tabs-items>
    </v-card>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  created() {
    this.fetchAllStores();
    this.fetchLatestProducts();
    this.fetchLatestVegetables();
    this.fetchLatestFruits();
  },

  data() {
    return {
      products: {},
      tab: null
    };
  },

  computed: {
    ...mapGetters(["getAllStores", "getLatestProducts", "getLatestVegetables", "getLatestFruits"])
  },

  methods: {
    ...mapActions(["fetchAllStores", "fetchLatestProducts", "fetchLatestVegetables", "fetchLatestFruits"])
  }
};
</script>
