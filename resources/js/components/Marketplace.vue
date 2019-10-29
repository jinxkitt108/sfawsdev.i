<template>
  <div class="container">
    <v-card color="basil">
      <v-card-title>
        <h1 class="font-weight-bold basil--text">Shop
        <v-btn small outlined absolute right class="text-decoration-none" to="/buildStore">
          <v-icon class="mr-2">mdi-storefront</v-icon>Build a Store
        </v-btn>
        </h1>
      </v-card-title>
      <v-tabs class="mt-4" background-color="transparent" fixed-tabs slider-color="white">
        <v-chip class="ma-3 pa-4" :href="'#tab-' + item" outlined pill>
          <v-avatar left>
            <img class="direct-chat-img" src="storage/icons/mixed.png" alt="Message User Image" />
          </v-avatar>Mixed
        </v-chip>
        <v-hover v-slot:default="{ hover }">
          <v-chip
            class="ma-3 pa-4"
            :href="'#tab-' + item"
            color="success"
            :elevation="hover ? 12 : 2"
            outlined
            pill
          >
            <v-avatar left>
              <img class="direct-chat-img" src="storage/icons/vege.png" alt="Message User Image" />
            </v-avatar>Vegetables
          </v-chip>
        </v-hover>
        <v-chip class="ma-3 pa-4" :href="'#tab-' + item" color="indigo" outlined pill>
          <v-avatar left>
            <img class="direct-chat-img" src="storage/icons/fruits.png" alt="Message User Image" />
          </v-avatar>Fruits
        </v-chip>
        <v-chip class="ma-3 pa-4" :href="'#tab-' + item" color="orange" outlined pill>
          <v-avatar left>
            <img class="direct-chat-img" src="storage/icons/arables.png" alt="Message User Image" />
          </v-avatar>Arables
        </v-chip>
        <v-chip class="ma-3 pa-4" :href="'#tab-' + item" color="orange" outlined pill>
          <v-avatar left>
            <img class="direct-chat-img" src="storage/icons/animals.png" alt="Message User Image" />
          </v-avatar>Animals
        </v-chip>
        <v-chip class="ma-3 pa-4" :href="'#tab-' + item" color="brown" outlined pill>
          <v-avatar left>
            <img
              class="direct-chat-img"
              src="storage/icons/fertilizer.png"
              alt="Message User Image"
            />
          </v-avatar>Supplies
        </v-chip>
        <v-menu v-if="more.length" bottom left>
          <template v-slot:activator="{ on }">
            <v-btn text class="align-self-center mr-4" v-on="on">
              more
              <v-icon right>mdi-menu-down</v-icon>
            </v-btn>
          </template>

          <v-list class="grey lighten-3">
            <v-list-item v-for="item in more" :key="item" @click="addItem(item)">{{ item }}</v-list-item>
          </v-list>
        </v-menu>
      </v-tabs>
      <v-card-text>
        <v-card class="border-0"> 
          <v-card-title>
            Featured Products
          </v-card-title>
        </v-card>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
  data: () => ({
    currentItem: "tab-Web",
    items: ["Crops", "Veges", "Fruits", "Supplies"],
    more: ["News", "Maps", "Books", "Flights", "Apps"],
    text:
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat."
  }),

  methods: {
    addItem(item) {
      const removed = this.items.splice(0, 1);
      this.items.push(...this.more.splice(this.more.indexOf(item), 1));
      this.more.push(...removed);
      this.$nextTick(() => {
        this.currentItem = "tab-" + item;
      });
    }
  }
};
</script>
