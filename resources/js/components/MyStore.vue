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
                <v-list-item-subtitle>Network Engineer</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-col>
        </v-row>
      </v-img>
      <v-card-text>
        <v-btn small color="primary">
          <v-icon class>mdi-plus</v-icon>Add Product
        </v-btn>
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
              hint="Ex. kilo, sack, box, or any applicable"
              class="mr-3"
              required
            ></v-text-field>
          </div>
          <div class="d-flex flex-row w-50">
            <v-text-field
              :rules="[v => !!v || 'Price is required']"
              v-model="newProduct.price"
              label="Price"
              prefix="$"
              class="mr-3"
              required
            ></v-text-field>
            <v-text-field
              label="Measure"
              class="mr-3"
              hint="Ex. kilo, sack, box, or any applicable"
              prefix="/"
            ></v-text-field>
          </div>
          <v-textarea outlined name="input-7-4" label="Product Description"></v-textarea>
          <!-- <v-file-input
            :rules="[v => !!v || 'Photos are required']"
            small-chips
            multiple
            accept="image/png, image/jpeg, image/bmp"
            placeholder="Pick product images"
            prepend-icon="mdi-camera"
            label="Photos"
            @change="productPhotos"
          ></v-file-input>-->
          <uploader
            class="pt-0 mt-0 font-weight-bold subtitle-1"
            v-model="newProduct.photos"
            title="Photos"
            size="small"
            :autoUpload="false"
          ></uploader>
          <v-checkbox v-model="newProduct.privacy" label="Show to public?" required></v-checkbox>
          <v-btn color="primary" type="submit" class="mr-2">Save</v-btn>
          <v-btn color="error" @click="reset">Reset Form</v-btn>
        </v-form>
      </v-card-text>
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
import Uploader from "vux-uploader-component";

export default {
  components: {
    Uploader
  },
  mounted() {},
  data() {
    return {
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
        price: "",
        measure: "",
        unit: "",
        stocks: "",
        description: "",
        photos: []
      }),
      category: ["Vegetables", "Fruits", "Livestock", "Supplies"],
      unit: ["Mass", "Weight", "Volume"]
    };
  },

  methods: {
    productPhotos(e) {
      if (!e.length) {
        return false;
      }
      for (let i = 0; i < e.length; i++) {
        (function(file) {
          if (file.type.indexOf("image") == 0) {
            let reader = new FileReader();
            reader.onload = function(f) {
              $("![]()", {
                src: f.target.result,
                width: 200,
                height: 200,
                title: file.name
              }).appendTo("#preview");
              this.newProduct.photos.push({ image: f.target.result });
            };
            reader.readAsDataURL(file);
          }
        })(e[i]);
      }
    },
    addProduct() {
      this.$Progress.start();
      this.newProduct.post("api/product")
        .then((data) => {
            console.log(data)
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
  }
};
</script>
