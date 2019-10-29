<template>
  <v-container>
    <v-card tile>
      <v-img height="100%" src="storage/cover_photo/sample.jpg">
        <v-row align="end">
          <v-col align-self="start" class="pa-4 ml-2" cols="12">
            <v-avatar class="profile" size="164" tile>
              <img class="avatar" :src="getProfilePhoto()" />
              <input ref="fileupload" id="image" type="file" @change="setUpCroppie" hidden />
              <v-btn class="avatar" @click="browseImage" color="grey" fab small absolute bottom right>
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
                <v-list-item-title class="title">{{userForm.name | capitalize}}</v-list-item-title>
                <v-list-item-subtitle>{{userForm.type}}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-col>
        </v-row>
      </v-img>
      <v-tabs right color="accent" v-model="tab">
        <v-tab>Settings</v-tab>
        <v-tab>Activity</v-tab>
        <v-tab>Gallery</v-tab>
      </v-tabs>
      <v-tabs-items v-model="tab">
        <v-tab-item>
          <v-card-title class="title">Basic Info</v-card-title>
          <form class="pl-5 pr-5 pb-3">
            <v-container class="border rounded pa-3">
              <v-row>
                <v-col cols="12" md="6">
                  <v-card-text>
                    <v-text-field v-model="userForm.name" label="Name" height="40px"></v-text-field>
                    <v-text-field v-model="userForm.username" label="Username"></v-text-field>
                    <v-text-field v-model="userForm.email" label="E-mail" height="40px"></v-text-field>
                    <v-text-field
                      v-model="userForm.password"
                      label="Password (leave empty if not changing)"
                    ></v-text-field>
                  </v-card-text>
                </v-col>
                <v-col cols="12" md="6">
                  <v-textarea outlined name="input-7-4" label="Bio" v-model="userForm.profile.bio"></v-textarea>
                </v-col>
              </v-row>
              <v-card-actions>
                <v-btn color="primary" @click.prevent="updateInfo" type="submit">Update</v-btn>
              </v-card-actions>
            </v-container>
          </form>
        </v-tab-item>
        <v-tab-item>asadasdsad</v-tab-item>
        <v-tab-item>xxxxxxxx</v-tab-item>
      </v-tabs-items>
    </v-card>
  </v-container>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
  created() {
    axios.get("api/profile").then(({ data }) => this.userForm.fill(data));
  },
  data: () => {
    return {
      tab: null,
      photo: "",
      image: null,
      croppie: false,
      userForm: new Form({
        name: "",
        type: "",
        username: "",
        email: "",
        password: "",
        profile: {
          bio: "",
          photo: ""
        }
      })
    };
  },
  methods: {
    cancelCrop() {
      this.croppie = false;
      $(".avatar").show();
      file.target.value = null;
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
          this.userForm.profile.photo = this.photo;
          this.$Progress.start();
          this.userForm
            .put("api/photo")
            .then(() => {
              $(".avatar").show();
              this.croppie = false;
              Fire.$emit("updateProfile");
              Swal.fire("Updated!", "Profile photo changed!", "success");
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
    },
    getProfilePhoto() {
      let photo =
        this.userForm.profile.photo.length > 200
          ? this.photo
          : "storage/profile_photo/" + this.userForm.profile.photo;
      return photo;
    },
    updateInfo() {
      this.$Progress.start();
      if (this.userForm.password == "") {
        this.userForm.password = undefined;
      }
      this.userForm
        .put("api/profile")
        .then(() => {
          Fire.$emit("updateProfile");
          Swal.fire(
            "Updated!",
            "User information has been updated.",
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
    }
  }
};
</script>
