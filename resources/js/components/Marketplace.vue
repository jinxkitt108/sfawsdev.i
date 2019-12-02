<template>
  <div class="container">
    <v-card flat>
      <v-card-title>
        <h2 class="font-weight-bold basil--text">
          SFAWS Marketplace
          <v-btn small outlined absolute right class="text-decoration-none" to="/buildStore">
            <v-icon class="mr-2">mdi-storefront</v-icon>Build a Store
          </v-btn>
        </h2>
      </v-card-title>
      <v-card-title class="font-weight-bold">Top Stores</v-card-title>
      <carousel autoplay loop mouseDrag centerMode :per-page="1">
        <slide v-for="store in stores.data" :key="store.id">
          <v-card class="mx-auto" tile>
            <v-img
              max-height="262"
              :aspect-ratio="16/9"
              :src="'storage/store_cover/' + store.cover"
            >
              <v-row>
                <v-col align-self="start" class="pa-0" cols="12">
                  <v-avatar class="profile" color="grey" size="164" tile>
                    <v-img :src="'storage/store_photo/' + store.photo"></v-img>
                  </v-avatar>
                </v-col>
                <v-col class="py-0">
                  <v-list-item three-line color="rgba(0, 0, 0, .4)" dark>
                    <v-list-item-content>
                      <div class="overline mb-4">{{store.name}}</div>
                      <v-list-item-title class="headline mb-1">{{store.name}}</v-list-item-title>
                      <v-list-item-subtitle>{{store.street + ' | ' + store.city + ', ' + store.country}}</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </v-col>
              </v-row>
            </v-img>
          </v-card>
        </slide>
      </carousel>
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
          <v-chip dark color="red darken-4" pill>
            <v-avatar left>
              <v-img src="storage/icons/arables.png"></v-img>
            </v-avatar>Arables
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
          <v-card flat tile color="secondary">
            <v-card-title class="font-weight-bold">New Products</v-card-title>

            <v-container>
              <v-row>
                <v-col v-for="newItem in latest" :key="newItem.id" cols="6" md="3">
                  <v-card>
                    <img
                      class="img-thumbnail border-0"
                      :src="'storage/product_photo/' + newItem.photos[0].file"
                    />
                    <v-list-item three-line>
                      <v-list-item-content>
                        <v-list-item-title
                          class="font-weight-bold red--text"
                        >₱ {{newItem.price}}/{{newItem.unit}}</v-list-item-title>
                        <v-list-item-title>{{newItem.name}}</v-list-item-title>
                        <v-list-item-subtitle>Stocks ({{newItem.stocks + ' ' + newItem.unit}})</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                  </v-card>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
        </v-tab-item>
        <v-tab-item>
          <v-card flat tile color="secondary">
            <v-card-title class="font-weight-bold">Vegetables</v-card-title>
            <v-container>
              <v-row>
                <v-col v-for="item in vegetables.data" :key="item.id" cols="6" md="3">
                  <v-card>
                    <img
                      class="img-thumbnail border-0"
                      :src="'storage/product_photo/' + item.photos[0].file"
                    />
                    <v-list-item three-line>
                      <v-list-item-content>
                        <v-list-item-title
                          class="font-weight-bold red--text"
                        >₱ {{item.price}}/{{item.unit}}</v-list-item-title>
                        <v-list-item-title>{{item.name}}</v-list-item-title>
                        <v-list-item-subtitle>Stocks ({{item.stocks + ' ' + item.unit}})</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                  </v-card>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
        </v-tab-item>
        <v-tab-item>
          <v-card flat tile color="secondary">
            <v-card-title class="font-weight-bold">Fruits</v-card-title>
            <v-container>
              <v-row>
                <v-col v-for="item in fruits.data" :key="item.id" cols="6" md="3">
                  <v-card>
                    <img
                      class="img-thumbnail border-0"
                      :src="'storage/product_photo/' + item.photos[0].file"
                    />
                    <v-list-item three-line>
                      <v-list-item-content>
                        <v-list-item-title
                          class="font-weight-bold red--text"
                        >₱ {{item.price}}/{{item.unit}}</v-list-item-title>
                        <v-list-item-title>{{item.name}}</v-list-item-title>
                        <v-list-item-subtitle>Stocks ({{item.stocks + ' ' + item.unit}})</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                  </v-card>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
        </v-tab-item>
        <v-tab-item>xxxx</v-tab-item>
        <v-tab-item>xxx</v-tab-item>
      </v-tabs-items>
    </v-card>
  </div>
</template>

<script>
export default {
  created() {
    this.loadProducts();
    this.loadVegetables();
    this.loadFruits();
    this.latestProducts();
    this.loadStores();
  },

  data() {
    return {
      products: {},
      vegetables: {},
      fruits: {},
      latest: {},
      stores: {},
      tab: null
    };
  },

  methods: {
    loadStores() {
      axios
        .get("api/allStores")
        .then(data => {
          this.stores = data.data;
        })
        .catch(() => {});
    },
    loadProducts() {
      axios
        .get("api/allProducts")
        .then(data => {
          this.products = data.data;
        })
        .catch(() => {});
    },
    loadVegetables() {
      axios
        .get("api/vegetables")
        .then(data => {
          this.vegetables = data.data;
        })
        .catch(() => {});
    },
    loadFruits() {
      axios
        .get("api/fruits")
        .then(data => {
          this.fruits = data.data;
        })
        .catch(() => {});
    },
    latestProducts() {
      axios
        .get("api/latestProducts")
        .then(data => {
          this.latest = data.data;
        })
        .catch(() => {});
    }
  }
};
</script>
