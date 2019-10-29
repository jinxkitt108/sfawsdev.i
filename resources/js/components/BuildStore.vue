<template>
  <div class="container">
    <v-card-title>
      <h2 class="font-weight-bold basil--text">
        Build A Store
        <v-btn small outlined absolute right class="text-decoration-none" to="/marketplace">
          <v-icon class="mr-2">mdi-arrow-left-circle</v-icon>Go to SFAWS Market
        </v-btn>
      </h2>
    </v-card-title>
    <v-form>
      <v-card-text class="mt-0 pt-0">
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field v-model="storeForm.name" label="Store Name"></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-file-input
              :rules="rules"
              accept="image/png, image/jpeg, image/bmp"
              placeholder="Pick a cover photo."
              prepend-icon="mdi-camera"
              label="Photo"
            ></v-file-input>
          </v-col>
        </v-row>
        <div class="d-flex flex-row mb-2">
          <div class="mr-2">
            <label for="country">Country</label>
            <country-select
              id="country"
              class="custom-select bg-transparent accent--text"
              v-model="storeForm.country"
            />
          </div>
          <div class="mr-2">
            <label for="region">
              Region
              <span class="text-muted small">(Leave if not available)</span>
            </label>
            <region-select
              id="region"
              class="custom-select bg-transparent accent--text"
              v-model="storeForm.region"
            />
          </div>
        </div>
        <v-text-field v-model="storeForm.city" class="mb-0 pb-0" label="City" outlined dense></v-text-field>
        <v-text-field v-model="storeForm.street" class="mt-0 pt-0" label="Street Address" outlined dense></v-text-field>
        <v-textarea v-model="storeForm.description" outlined name="input-7-4" rows="3" label="Description"></v-textarea>
        <v-card-actions class="mt-0 pt-0">
          <v-btn type="submit" @click.prevent="createStore" color="success">Sign Up</v-btn>
        </v-card-actions>
      </v-card-text>
    </v-form>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Build a store.");
  },

  data() {
    return {
      rules: [
        value =>
          !value ||
          value.size < 6000000 ||
          "Image size should be less than 6 MB!"
      ],
      storeForm: new Form({
        name: "",
        country: "",
        region: "",
        city: "",
        street: "",
        description: "",
        photo: ""
      })
    };
  },

  methods: {
    createStore() {
      this.$Progress.start();
      this.storeForm
        .post("api/store")
        .then(() => {
          Swal.fire("Success!", "Store has been created.", "success");
          this.$Progress.finish();
          this.$router.push("/mystore");
        })
        .catch(() => {
          Swal.fire({
            type: "error",
            title: "Oops...",
            text: "Something went wrong!"
          });
        });
    }
  }

  
};
</script>
