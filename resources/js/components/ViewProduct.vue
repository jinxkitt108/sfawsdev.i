<template>
  <v-container v-if="loaded">
    <v-row>
      <v-col cols="12" md="6">
        <v-card flat tile>
          <img
            width="400"
            class="img-responsive"
            :src="'/storage/product_photo/' + getProduct.photos[0].file"
            alt
          />
        </v-card>
      </v-col>
      <v-col cols="12" md="6">
        <v-card flat tile>
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title class="headline font-weight-bold">{{getProduct.name}}</v-list-item-title>
              <v-list-item-title class="subtitle-1 red--text">
                Php
                <span class="ml-1">{{getProduct.price + '/' + getProduct.unit}}</span>
              </v-list-item-title>
              <v-list-item-subtitle class="mt-2">
                Stocks
                <span
                  class="ml-3 font-weight-bold"
                >{{getProduct.stocks + '/' + getProduct.unit}}</span>
              </v-list-item-subtitle>
              <v-list-item-subtitle>
                Rating
                <span class="ml-3 font-weight-bold">{{getProduct.stocks}}</span>
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

          <v-card-actions class="mt-3">
            <v-btn color="success" outlined class="mr-2">
              <v-icon left>mdi-cart</v-icon>Add to cart
            </v-btn>
            <v-dialog
              v-model="dialog"
              hide-overlay
              transition="dialog-bottom-transition"
            >
              <template v-slot:activator="{ on }">
                <v-btn v-on="on" color="success">Order</v-btn>
              </template>
              <v-card>
                <v-toolbar color="teal darken-4">
                  <v-btn icon dark @click="dialog = false">
                      <v-icon>mdi-close</v-icon>
                  </v-btn>
                </v-toolbar>
              </v-card>
            </v-dialog>
          </v-card-actions>

          <v-list-item>
            <v-list-item-content>
              <v-list-item-title class="mt-3 mb-2 font-weight-bold">Description</v-list-item-title>
              <v-list-item-subtitle>{{getProduct.description}}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-card>
      </v-col>
    </v-row>
    <v-card flat tile>
      <v-card-text>
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title class="font-weight-bold">Seller</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-avatar size="55" class="mr-1">
          <v-img :src="'/storage/store_photo/' + getProduct.store.photo"></v-img>
        </v-avatar>
        {{ getProduct.store.name}}
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title class="font-weight-bold">Rating and Reviews</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
  mounted() {
    console.log(this.$route.params.id);
  },

  computed: {
    ...mapGetters(["getProduct"])
  },

  data() {
    return {
      loaded: false,
      dialog: false
    };
  },

  methods: {
    ...mapActions(["fetchProduct"])
  },

  created() {
    this.fetchProduct(this.$route.params.id).then(() => {
      this.loaded = true;
    });
  }
};
</script>

<style>
</style>