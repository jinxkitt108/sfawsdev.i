<style scoped>
.show-btns {
  color: rgba(255, 255, 255, 1) !important;
}
</style>

<template>
  <div class="container">
    <v-card-title class="mb-0">
      <h2 class="font-weight-bold basil--text">
        My Store
        <v-btn v-if="!store" outlined absolute right to="/buildstore">
          <v-icon class="mr-2">mdi-storefront</v-icon>Build a Store
        </v-btn>
        <v-btn v-else small outlined absolute right class="text-decoration-none" to="/marketplace">
          <v-icon class="mr-2">mdi-arrow-left-circle</v-icon>Go to SFAWS Market
        </v-btn>
      </h2>
    </v-card-title>
    <v-card tile v-if="store">
      <v-img height="100%" src="storage/store_photo/store_cover.png">
        <v-row align="end" class="fill-height">
          <v-col align-self="start" class="pa-4 ml-2" cols="12">
            <v-avatar class="profile" size="164" tile>
              <img class="avatar" :src="getStorePhoto()" />
              <input ref="fileupload" id="image" type="file" @change="setUpCroppie" hidden />
              <v-btn
                class="avatar"
                @click="browseImage"
                color="grey"
                fab
                small
                absolute
                bottom
                right
              >
                <v-icon>mdi-camera</v-icon>
              </v-btn>
              <div class="mt-2" v-show="croppie">
                <vue-croppie
                  ref="croppieRef"
                  :enableOrientation="true"
                  :viewport="{ width: 164, height: 164, type: 'square' }"
                  :boundary="{ width: 170, height: 170 }"
                  :enableResize="false"
                  :showZoomer="false"
                ></vue-croppie>
                <v-btn class="mt-1" small @click="cropSave">Change</v-btn>
                <v-btn class="mt-1" small @click="cancelCrop">Cancel</v-btn>
              </div>
            </v-avatar>
          </v-col>
          <v-col class="py-0">
            <v-list-item color="rgba(0, 0, 0, .4)" dark>
              <v-list-item-content>
                <v-list-item-title class="title">{{mystore.name}}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-col>
        </v-row>
      </v-img>
      <v-tabs centered v-model="tab">
        <v-tab>Catalog</v-tab>
        <v-tab>Products</v-tab>
        <v-tab>New Product</v-tab>
        <v-tab>Sales & Order</v-tab>
        <v-tab>Settings</v-tab>
      </v-tabs>
      <v-tabs-items v-model="tab">
        <v-tab-item>
          <v-container>
            <v-card flat>
              <v-card-text>{{mystore.description}}</v-card-text>
            </v-card>
          </v-container>
        </v-tab-item>
        <v-tab-item>
          <v-container>
            <v-row class="d-flex flex-row">
              <v-col v-for="product in products" :key="product.id" cols="6" md="3">
                <v-card class="mx-auto">
                  <img
                    class="img-thumbnail border-0"
                    :src="'storage/product_photo/' + product.photos[0].file"
                  />
                  <v-list-item three-line>
                    <v-list-item-content>
                      <v-list-item-title
                        class="font-weight-bold red--text"
                      >₱ {{product.price}}/{{product.unit}}</v-list-item-title>
                      <v-list-item-title>{{product.name}}</v-list-item-title>
                      <v-list-item-subtitle>
                        <v-rating
                          :value="4.5"
                          color="amber"
                          dense
                          half-increments
                          readonly
                          size="14"
                        ></v-rating>
                      </v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </v-card>
              </v-col>
            </v-row>
          </v-container>
        </v-tab-item>
        <v-tab-item>
          <v-card-text>
            <v-form ref="form" @submit.prevent="addProduct">
              <v-text-field
                :rules="[v => !!v || 'Name is required']"
                v-model="newProduct.name"
                label="Name"
                small
                required
              ></v-text-field>
              <div class="d-flex flex-row">
                <v-select
                  v-model="newProduct.category"
                  :items="category"
                  :rules="[v => !!v || 'Category is required']"
                  label="Category"
                  class="mr-3"
                  required
                ></v-select>
                <v-text-field
                  type="number"
                  :rules="[v => !!v || 'Stocks is required']"
                  v-model="newProduct.stocks"
                  label="Stocks"
                  class="mr-3"
                  required
                ></v-text-field>
                <v-text-field
                  :rules="[v => !!v || 'Unit is required']"
                  v-model="newProduct.unit"
                  label="Unit"
                  hint="Ex. kg, sack, box, or any applicable"
                  class="mr-3"
                  required
                ></v-text-field>
              </div>
              <div class="d-flex flex-row w-50">
                <v-text-field
                  :rules="[v => !!v || 'Price is required']"
                  v-model="newProduct.price"
                  label="Price"
                  prefix="₱"
                  :suffix="'/' + newProduct.unit"
                  class="mr-3"
                  required
                ></v-text-field>
              </div>
              <v-textarea
                v-model="newProduct.description"
                outlined
                name="input-7-4"
                label="Product Description"
              ></v-textarea>
              <v-file-input
                ref="uploadFileReference"
                @change="uploadFileReference"
                :rules="[v => !!v || 'Photo is required']"
                chips
                multiple
                accept="image/png, image/jpeg, image/bmp"
                placeholder="Pick photos"
                prepend-icon="mdi-camera"
                label="Product Photos"
              ></v-file-input>
              <v-flex flex-wrap>
                <template v-show="newProduct.files">
                  <div class="row">
                    <v-col
                      cols="3"
                      sm="2"
                      class="d-flex child-flex"
                      v-for="(file, index) in newProduct.files"
                      :key="index"
                    >
                      <v-hover v-slot:default="{ hover }">
                        <v-card
                          :elevation="hover ? 12 : 2"
                          :class="{ 'on-hover': hover }"
                          flat
                          tile
                          class="d-flex"
                        >
                          <v-img class="grey lighten-2" :src="file.url">
                            <div class="align-self-center">
                              <v-btn :class="{ 'show-btns': hover }" color="transparent" icon>
                                <v-icon
                                  :class="{ 'show-btns': hover }"
                                  color="transparent"
                                  @click="deletePhoto(file)"
                                >mdi-delete</v-icon>
                              </v-btn>
                            </div>
                          </v-img>
                        </v-card>
                      </v-hover>
                    </v-col>
                  </div>
                </template>
              </v-flex>
              <v-checkbox v-model="newProduct.public" label="Show to public?" required></v-checkbox>
              <v-btn color="primary" type="submit" class="mr-2">Save</v-btn>
              <v-btn color="error" @click="reset">Reset Form</v-btn>
            </v-form>
          </v-card-text>
        </v-tab-item>
        <v-tab-item></v-tab-item>
        <v-tab-item>
          <v-container>
            <v-form>
              <v-card-text class="mt-0 pt-0">
                <v-row>
                  <v-col cols="12" md="6">
                    <v-text-field v-model="mystore.name" label="Store Name"></v-text-field>
                  </v-col>
                </v-row>
                <div class="d-flex flex-row mb-2">
                  <div class="mr-2">
                    <label for="country">Country</label>
                    <country-select
                      id="country"
                      class="custom-select bg-transparent accent--text"
                      v-model="mystore.country"
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
                      v-model="mystore.region"
                    />
                  </div>
                </div>
                <v-text-field v-model="mystore.city" class="mb-0 pb-0" label="City" outlined dense></v-text-field>
                <v-text-field
                  v-model="mystore.street"
                  class="mt-0 pt-0"
                  label="Street Address"
                  outlined
                  dense
                ></v-text-field>
                <v-textarea
                  v-model="mystore.description"
                  outlined
                  name="input-7-4"
                  rows="3"
                  label="Description"
                ></v-textarea>
                <v-card-actions class="mt-0 pt-0">
                  <v-btn type="submit" @click.prevent="updateStore" color="success">Update</v-btn>
                </v-card-actions>
              </v-card-text>
            </v-form>
          </v-container>
        </v-tab-item>
      </v-tabs-items>
    </v-card>
    <v-container v-show="!store" class="text-sm-center mt-0" fluid>
      <v-row align="center" justify="center">
        <v-col sm="7">
          <v-avatar size="100">
            <img src="storage/app_photos/leaf-photos.png" />
          </v-avatar>
          <p class="h3 font-weight-bold mb-0">Selling made simple.</p>
          <p
            class="h5 pink--text mt-0"
          >Sell on the leading platform for farmers all around the world</p>

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
  </div>
</template>

<script>
export default {
  mounted() {},
  data() {
    return {
      products: {},
      tab: null,
      store: true,
      croppie: null,
      image: null,
      photo: null,
      mystore: new Form({
        name: "",
        country: "",
        region: "",
        city: "",
        street: "",
        description: "",
        photo: ""
      }),
      newProduct: new Form({
        name: "",
        category: "",
        unit: "",
        stocks: "",
        price: "",
        description: "",
        files: [],
        public: false
      }),
      category: ["Vegetables", "Fruits", "Livestock", "Supplies"]
    };
  },

  methods: {
    updateStore() {

    },
    loadProducts() {
      axios
        .get("api/product")
        .then(data => {
          this.products = data.data;
        })
        .catch(() => {});
    },
    deletePhoto(id) {
      let index = this.newProduct.files.indexOf(id);
      console.log(index);
      this.newProduct.files.splice(index, 1);
    },
    uploadFileReference(e) {
      if (!e.length) {
        this.newProduct.files = [];
      } else {
        this.length = e.length;
        this.showImage(e);
      }
    },
    showImage(files) {
      if (files.length > 1) {
        const test = Array.from(files).forEach((file, idx) => {
          const fileReader = new FileReader();
          const getResult = new Promise(resolve => {
            fileReader.onload = e => {
              this.newProduct.files.push({
                id: idx,
                url: e.target.result
              });
            };
          });

          fileReader.readAsDataURL(file);
          return getResult.then(file => {
            return file;
          });
        });
      } else {
        const fileReader = new FileReader();
        fileReader.onload = e => {
          this.newProduct.files.push({
            id: 1,
            url: e.target.result
          });
        };
        fileReader.readAsDataURL(files[0]);
      }
    },
    addProduct() {
      this.$Progress.start();
      this.newProduct
        .post("api/product")
        .then(data => {
          this.$Progress.finish();
          Swal.fire("Success!", "New product has been added.", "success");
          this.reset();
          this.loadProducts();
          console.log(data);
        })
        .catch(() => {
          this.$Progress.fail();
          Swal.fire({
            type: "error",
            title: "Oops...",
            text: "Upload failed."
          });
        });
    },
    reset() {
      this.$refs.form.reset();
      this.newProduct.public = false;
    },
    getStorePhoto() {
      let photo =
        this.mystore.photo.length > 200
          ? this.photo
          : "storage/store_photo/" + this.mystore.photo;
      return photo;
    },
    cancelCrop() {
      this.croppie = false;
      $(".avatar").show();
      this.$refs.fileupload.value = "";
    },
    cropSave() {
      let options = {
        format: "jpeg",
        type: "canvas",
        size: "viewport"
      };
      this.$refs.croppieRef
        .result(options, output => {
          this.photo = output;
        })
        .then(response => {
          this.photo = response;
          this.mystore.photo = this.photo;
          this.$Progress.start();
          this.mystore
            .put("api/storephoto")
            .then(() => {
              $(".avatar").show();
              this.croppie = false;
              Swal.fire("Updated!", "Store photo changed!", "success");
              this.$Progress.finish();
            })
            .catch(() => {
              this.$Progress.fail();
              Swal.fire({
                type: "error",
                title: "Oops...",
                text: "Upload failed."
              });
            });
        });
    },
    browseImage() {
      $("#image").click();
    },
    setUpCroppie(file) {
      $(".avatar").hide();
      this.croppie = true;
      let image = file.target.files[0];
      // To check if file is an image
      if (!image.name.match(/.(jpg|jpeg|png|gif)$/i)) {
        Swal.fire({
          type: "error",
          title: "Oops...",
          text: "That is not an image!"
        });
        file.target.value = null;
        $("#avatar").show();
        return;
      }
      let reader = new FileReader();
      reader.readAsDataURL(image);
      reader.onload = image => {
        this.image = reader.result;
        this.$refs.croppieRef.bind({
          url: this.image
        });
      };
    }
  },

  created() {
    axios.get("api/store").then(({ data }) => {
      this.mystore.fill(data);
      if (data) {
        this.store = true;
      } else {
        this.store = false;
      }
    });
    this.loadProducts();
  }
};
</script>
