<style scoped>
.show-btns {
  color: rgba(255, 255, 255, 1) !important;
}
</style>

<template>
  <div class="container">
    <v-card-title>
      <v-btn small outlined absolute right class="text-decoration-none" to="/marketplace">
        <v-icon class="mr-2">mdi-arrow-left-circle</v-icon>Marketplace
      </v-btn>
    </v-card-title>
    <v-card flat tile class="mt-2
    ">
      <div v-show="coverCroppie">
        <vue-croppie
          ref="coverCroppieRef"
          :enableOrientation="true"
          :viewport="{ width: 819, height: 262, type: 'square' }"
          :boundary="{ width: 819, height: 262 }"
          :enableResize="false"
          :showZoomer="false"
        ></vue-croppie>
        <div class="text-center">
          <v-btn class="mt-1" color="success" small @click="coverCropSave">Change</v-btn>
          <v-btn class="mt-1" color="danger" dark small @click="cancelCrop">Cancel</v-btn>
        </div>
      </div>
      <v-img v-if="getMyStore.cover" class="cover" :src="'storage/store_cover/' + getMyStore.cover">
        <template v-slot:placeholder>
          <v-row class="fill-height ma-0" align="center" justify="center">
            <v-progress-circular indeterminate color="success lighten-5"></v-progress-circular>
          </v-row>
        </template>
        <input ref="coverUpload" id="cover" type="file" @change="setUpCover" hidden />
        <div class="text-center mt-5">
          <v-avatar class="avatar" size="164" tile>
            <template v-slot:placeholder>
              <v-row class="fill-height ma-0" align="center" justify="center">
                <v-progress-circular indeterminate color="success lighten-5"></v-progress-circular>
              </v-row>
            </template>
            <v-img v-if="getMyStore.photo" :src="'storage/store_photo/' + getMyStore.photo"></v-img>
            <input ref="fileupload" id="image" type="file" @change="setUpCroppie" hidden />
          </v-avatar>
          <div class="avatar mt-1">
            <v-btn @click="browseImage" color="grey lighten-1" small rounded>
              <v-icon left>mdi-camera</v-icon>Change
            </v-btn>
          </div>
        </div>
        <div v-show="croppie" class="text-center mt-5">
          <vue-croppie
            ref="croppieRef"
            :enableOrientation="true"
            :viewport="{ width: 164, height: 164, type: 'square' }"
            :boundary="{ width: 170, height: 170 }"
            :enableResize="false"
            :showZoomer="false"
          ></vue-croppie>
          <div class="mt-1">
            <v-btn class="mt-1" color="success" dark small @click="cropSave">Change</v-btn>
            <v-btn class="mt-1" color="danger" dark small @click="cancelCrop">Cancel</v-btn>
          </div>
        </div>
        <v-list-item two-line dark>
          <v-list-item-content>
            <v-list-item-title class="headline font-weight-black">{{getMyStore.name}}</v-list-item-title>
            <v-list-item-subtitle>
              {{ getMyStore.street + '|'}}
              <span
                class="small"
              >{{ getMyStore.city + ', ' + getMyStore.country}}</span>
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
        <v-btn
          class="cover"
          rounded
          color="grey lighten-1"
          @click="browseCover"
          absolute
          bottom
          right
        >
          <v-icon>mdi-camera</v-icon>
        </v-btn>
      </v-img>
      <v-tabs show-arrows centered v-model="tab">
        <v-tabs-slider></v-tabs-slider>
        <v-tab>Store Info</v-tab>
        <v-tab>
          Products (
          <span>{{products.length}}</span>)
        </v-tab>
        <v-tab>Management</v-tab>
        <v-tab>
          <v-icon>mdi-settings</v-icon>
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tab">
        <v-tab-item>
          <v-container>
            <v-card flat>
              <v-card-title class="font-weight-black subtitle-1">Store Address</v-card-title>
              <v-card-text>
                {{ getMyStore.street + '|'}}
                <span
                  class="small"
                >{{ getMyStore.city + ', ' + getMyStore.country}}</span>
              </v-card-text>
              <v-card-title class="font-weight-black subtitle-1">Description</v-card-title>
              <v-card-text>{{getMyStore.description}}</v-card-text>
              <v-card-title class="font-weight-black subtitle-1">Featured Products</v-card-title>
              <v-card-text></v-card-text>
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
                      <v-list-item-subtitle>Stocks ({{product.stocks + ' ' + product.unit}})</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </v-card>
              </v-col>
            </v-row>
          </v-container>
        </v-tab-item>

        <v-tab-item>
          <v-container>
            <v-data-table
              :loading="products.data"
              loading-text="Loading.. Please wait"
              :headers="headers"
              :items="products"
              item-key="name"
              :search="search"
              class="elevation-1"
            >
              <template v-slot:top>
                <v-toolbar flat>
                  <v-toolbar-title class="subtitle-1">Product List</v-toolbar-title>
                  <v-divider class="mx-4" inset vertical></v-divider>
                  <v-spacer></v-spacer>

                  <!-- Add/Edit Product Dialog -->
                  <v-dialog v-model="dialog" max-width="700px">
                    <template v-slot:activator="{ on }">
                      <v-btn small outlined color="primary" class="mb-2" v-on="on">
                        <v-icon left>mdi-plus</v-icon>New Product
                      </v-btn>
                    </template>
                    <v-card>
                      <v-card-title>{{ editMode ? 'Edit Product Details' : 'New Product Details'}}</v-card-title>
                      <v-card-text>
                        <v-form
                          ref="form"
                          @submit.prevent=" editMode ? updateProduct() : addProduct()"
                        >
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
                            <template>
                              <div class="row">
                                <v-col
                                  v-if="!photo.remove"
                                  cols="3"
                                  md="2"
                                  class="d-flex child-flex"
                                  v-for="(photo, index) in newProduct.photos"
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
                                      <v-img
                                        class="grey lighten-2"
                                        :src="photo.url ? photo.url : 'storage/product_photo/' + photo.file"
                                      >
                                        <div class="align-self-center">
                                          <v-btn
                                            :class="{ 'show-btns': hover }"
                                            color="transparent"
                                            icon
                                          >
                                            <v-icon
                                              :class="{ 'show-btns': hover }"
                                              color="transparent"
                                              @click="deletePhoto(photo)"
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
                    </v-card>
                  </v-dialog>
                </v-toolbar>
                <v-toolbar flat>
                  <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                  ></v-text-field>
                </v-toolbar>
              </template>

              <!-- Product Table Contents -->
              <template v-slot:item.photos="{ item }">
                <v-avatar tile>
                  <v-img :src="'storage/product_photo/' + item.photos[0].file"></v-img>
                </v-avatar>
              </template>
              <template v-slot:item.stocks="{ item }">
                <v-chip color="primary">
                  {{item.stocks}}
                  <span class="small">({{item.unit}})</span>
                </v-chip>
              </template>
              <template v-slot:item.public="{ item }">
                <v-checkbox loading small :input-value="item.public" @change="togglePublic(item)"></v-checkbox>
              </template>
              <template v-slot:item.created_at="{ item }">
                <p class="small">{{item.created_at | postDate}}</p>
              </template>
              <template v-slot:item.action="{ item }">
                <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
                <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
              </template>
            </v-data-table>
          </v-container>
        </v-tab-item>

        <v-tab-item>
          <v-container>
            <v-form>
              <v-card-text class="mt-0 pt-0">
                <v-row>
                  <v-col cols="12" md="6">
                    <v-text-field v-model="getMyStore.name" label="Store Name"></v-text-field>
                  </v-col>
                </v-row>
                <div class="d-flex flex-row mb-2">
                  <div class="mr-2">
                    <label for="country">Country</label>
                    <country-select
                      id="country"
                      :country="getMyStore.country"
                      class="custom-select bg-transparent accent--text"
                      v-model="getMyStore.country"
                    />
                  </div>
                  <div class="mr-2">
                    <label for="region">
                      Region
                      <span class="text-muted small">(Leave if not available)</span>
                    </label>
                    <region-select
                      id="region"
                      :country="getMyStore.country"
                      :region="getMyStore.region"
                      class="custom-select bg-transparent accent--text"
                      v-model="getMyStore.region"
                    />
                  </div>
                </div>
                <v-text-field
                  v-model="getMyStore.city"
                  class="mb-0 pb-0"
                  label="City"
                  outlined
                  dense
                ></v-text-field>
                <v-text-field
                  v-model="getMyStore.street"
                  class="mt-0 pt-0"
                  label="Street Address"
                  outlined
                  dense
                ></v-text-field>
                <v-textarea
                  v-model="getMyStore.description"
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
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  mounted() {},

  watch: {
    dialog(val) {
      !val && this.close();
    }
  },

  data() {
    return {
      editMode: false,
      dialog: false,
      search: "",
      products: {},
      tab: null,
      store: true,
      croppie: false,
      coverCroppie: null,
      image: null,
      photo: null,
      cover: null,
      headers: [
        {
          text: "Name",
          align: "left",
          value: "name"
        },
        { text: "Image", sortable: false, value: "photos" },
        { text: "Stocks", value: "stocks" },
        { text: "Ratings", value: "ratings" },
        { text: "Public", align: "center", value: "public" },
        { text: "Date Added", value: "created_at" },
        { text: "Actions", sortable: false, align: "center", value: "action" }
      ],

      setPhoto: new Form({
        photo: ""
      }),

      newProduct: new Form({
        id: "",
        name: "",
        category: "",
        unit: "",
        stocks: "",
        price: "",
        description: "",
        photos: [],
        public: false
      }),

      category: ["Vegetables", "Fruits", "Livestock", "Supplies"]
    };
  },

  computed: mapGetters(["getMyStore"]),

  methods: {
    ...mapActions(["fetchMyStore"]),

    coverCropSave() {
      let options = {
        format: "jpeg",
        type: "canvas",
        size: "viewport"
      };
      this.$refs.coverCroppieRef
        .result(options, output => {
          this.cover = output;
        })
        .then(response => {
          this.setPhoto.photo = response;
          this.$Progress.start();
          this.setPhoto
            .put("api/storeCover")
            .then(() => {
              this.fetchMyStore();
              $(".cover").show();
              this.coverCroppie = false;
              Swal.fire("Updated!", "Store cover photo changed!", "success");
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

    browseCover() {
      $("#cover").click();
    },

    setUpCover(file) {
      $(".cover").hide();
      this.coverCroppie = true;
      let image = file.target.files[0];
      // To check if file is an image
      if (!image.name.match(/.(jpg|jpeg|png|gif)$/i)) {
        Swal.fire({
          type: "error",
          title: "Oops...",
          text: "That is not an image!"
        });
        file.target.value = null;
        $("#cover").show();
        return;
      }
      let reader = new FileReader();
      reader.readAsDataURL(image);
      reader.onload = image => {
        this.image = reader.result;
        this.$refs.coverCroppieRef.bind({
          url: this.image
        });
      };
    },

    togglePublic(item) {
      axios
        .put("api/publicProducts", {
          id: item.id,
          public: item.public
        })
        .then(() => {
          this.loadProducts();
          Toast.fire({
            type: "success",
            title: "Product privacy changed!"
          });
        })
        .catch(() => {
          Swal.fire({
            type: "error",
            title: "Oops...",
            text: "Something went wrong!"
          });
        });
    },

    deleteItem(item) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "teal",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        // Send request to the server
        if (result.value) {
          this.newProduct
            .delete("api/product/" + item.id)
            .then(() => {
              Swal.fire("Deleted!", "Product has been deleted.", "success");
              this.loadProducts();
            })
            .catch(() => {
              Swal.fire({
                type: "error",
                title: "Oops...",
                text: "Something went wrong!"
              });
            });
        }
      });
    },

    updateProduct() {
      this.$Progress.start();
      this.newProduct
        .put("api/product/" + this.newProduct.id)
        .then(() => {
          this.$Progress.finish();
          this.close();
          Swal.fire({
            position: "top-end",
            type: "success",
            title: "Product updated!",
            showConfirmButton: false,
            timer: 1500
          });
          this.loadProducts();
        })
        .catch(() => {
          this.$Progress.fail();
          Swal.fire({
            type: "error",
            title: "Oops...",
            text: "You are uploading a large file."
          });
        });
    },

    editItem(item) {
      this.dialog = true;
      this.editMode = true;
      this.newProduct.fill(item);
    },

    updateStore() {
      this.$Progress.start();
      axios
        .put("api/storeInfo", this.getMyStore)
        .then(() => {
          Swal.fire(
            "Updated!",
            "Store information has been updated.",
            "success"
          );
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
          Swal.fire({
            type: "error",
            title: "Oops...",
            text: "You are uploading a large file."
          });
        });
    },

    loadProducts() {
      axios
        .get("api/product")
        .then(data => {
          this.products = data.data;
        })
        .catch(() => {});
    },

    deletePhoto(photo) {
      let index = this.newProduct.photos.indexOf(photo);
      this.newProduct.photos.push({ remove: photo.file });
      this.newProduct.photos.splice(index, 1);
    },

    uploadFileReference(e) {
      if (!e.length) {
        this.newProduct.photos = [];
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
              this.newProduct.photos.push({
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
          this.newProduct.photos.push({
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
          this.dialog = false;
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

    close() {
      this.dialog = false;
      this.$refs.form.reset();
      this.newProduct.public = false;
      this.editMode = false;
      this.loadProducts();
    },

    reset() {
      this.$refs.form.reset();
      this.newProduct.public = false;
    },

    cancelCrop() {
      this.croppie = false;
      this.coverCroppie = false;
      $(".avatar").show();
      $(".cover").show();
      this.$refs.fileupload.value = "";
      this.$refs.coverUpload.value = "";
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
          this.setPhoto.photo = response;
          this.$Progress.start();
          this.setPhoto
            .put("api/storephoto")
            .then(() => {
              $(".avatar").show();
              this.fetchMyStore();
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
    this.fetchMyStore();
    this.loadProducts();
  }
};
</script>
