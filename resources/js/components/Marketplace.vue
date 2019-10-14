<template>
  <div class="container">
    <v-toolbar>
      <v-toolbar-title class="font-weight-black">Marketplace</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-toolbar-items>
        <v-btn text>Arables</v-btn>
        <v-btn text>Crops</v-btn>
        <v-btn text>Mixed</v-btn>
        <v-btn text>Sell on SFAWS</v-btn>
      </v-toolbar-items>
    </v-toolbar>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  }
};
</script>
