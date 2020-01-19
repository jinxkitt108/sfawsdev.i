<template>
  <v-container class="text-sm-center mt-0" fluid>
    <h2 class="font-weight-bold basil--text">
      <v-btn outlined absolute right to="/buildstore">
        <v-icon class="mr-2">mdi-storefront</v-icon>Build a Store
      </v-btn>
    </h2>
    <v-row align="center" justify="center">
      <v-col sm="7">
        <v-avatar size="100">
          <img src="storage/app_photos/leaf-photos.png" />
        </v-avatar>
        <p class="h3 font-weight-bold mb-0">Selling made simple.</p>
        <p class="h5 pink--text mt-0">Sell on the leading platform for farmers all around the world</p>

        <v-card class="text-sm-left" style="border: 5px solid teal; border-radius: 20px">
          <v-alert class="mb-0" icon="mdi-brightness-percent">Sell with 0% commision.</v-alert>
          <v-alert
            class="mt-0 mb-0"
            icon="mdi-account-group"
          >Anyone can sign-up as either individual or corporate seller.</v-alert>
          <v-alert class="mt-0 mb-0" icon="mdi-tools">Free access to seller tools and campaigns.</v-alert>
          <v-alert class="mt-0" icon="mdi-rocket">Boosted product search ranking.</v-alert>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {};
</script>

<style>
</style>