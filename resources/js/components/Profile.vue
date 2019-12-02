<template>
  <v-container>
    <v-tabs show-arrows centered v-model="tab">
      <v-tabs-slider></v-tabs-slider>
      <v-tab>Overview</v-tab>
      <v-tab>Post(12)</v-tab>
      <v-tab>Videos</v-tab>
      <v-tab>Achievements</v-tab>
      <v-tab>
        <v-icon>mdi-settings</v-icon>
      </v-tab>
    </v-tabs>
    <v-tabs-items v-model="tab">
      <v-tab-item>
        <v-container>
          <v-row style="background-image: linear-gradient(#00ACC1, #00838F, #006064)">
            <v-col cols="12" md="3" class="text-center">
              <v-avatar class="avatar" color="cyan" size="164">
                <v-img
                  :src="'/storage/profile_photo/' + userForm.profile.photo"
                  class="img-bordered border-light"
                >
                  <template v-slot:placeholder>
                    <v-row class="fill-height ma-0" align="center" justify="center">
                      <v-progress-circular indeterminate color="success lighten-5"></v-progress-circular>
                    </v-row>
                  </template>
                </v-img>
                <v-btn
                  class="avatar mb-4"
                  @click="browseImage"
                  color="grey lighten-1"
                  fab
                  small
                  absolute
                  bottom
                  right
                >
                  <v-icon>mdi-camera</v-icon>
                </v-btn>
                <input ref="fileupload" id="image" type="file" @change="setUpCroppie" hidden />
              </v-avatar>
              <v-avatar size="164" v-show="croppie">
                <vue-croppie
                  ref="croppieRef"
                  :enableOrientation="true"
                  :viewport="{ width: 164, height: 164, type: 'circle'}"
                  :boundary="{ width: 164, height: 164, type: 'circle'}"
                  :enableResize="false"
                  :showZoomer="false"
                ></vue-croppie>
                <v-btn small @click="cancelCrop" fab absolute bottom left dark color="danger">
                  <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-btn small @click="cropSave" fab absolute bottom right color="success">
                  <v-icon>mdi-check</v-icon>
                </v-btn>
              </v-avatar>
            </v-col>
            <v-col cols="12" md="4">
              <v-card dark flat shaped style="background-color: #10383738">
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>
                      <span class="font-weight-bold headline">{{userForm.name}}</span>
                      <div class="overline">{{userForm.type}}</div>
                    </v-list-item-title>
                    <v-list-item-title>
                      <p class="small">Member since {{userForm.created_at | sinceDate}}</p>
                    </v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-col>
            <v-col cols="12" md="5">
              <v-row no-gutters class="justify-content-center">
                <v-col cols="4" md="4" class="pr-2">
                  <v-card dark flat shaped style="background-color: #10383738" class="text-center">
                    <v-avatar align="center" size="64" tile>
                      <v-img src="/storage/app_photos/account.png"></v-img>
                    </v-avatar>
                    <v-list-item class="text-center">
                      <v-list-item-content>
                        <v-list-item-title class="font-weight-bold headline">{{userForm.followers}}</v-list-item-title>
                        <p class="small">Followers</p>
                      </v-list-item-content>
                    </v-list-item>
                  </v-card>
                </v-col>
                <v-col cols="4" md="4" class="pr-2">
                  <v-card dark flat shaped style="background-color: #10383738" class="text-center">
                    <v-avatar class="mx-auto" size="64" tile>
                      <v-img src="/storage/app_photos/remission.png"></v-img>
                    </v-avatar>
                    <v-list-item class="text-center">
                      <v-list-item-content>
                        <v-list-item-title class="font-weight-bold headline">{{userForm.followings}}</v-list-item-title>
                        <p class="small">Followings</p>
                      </v-list-item-content>
                    </v-list-item>
                  </v-card>
                </v-col>
                <v-col cols="4" md="4">
                  <v-card dark flat shaped style="background-color: #10383738" class="text-center">
                    <v-avatar class="mx-auto" size="64" tile>
                      <v-img src="/storage/app_photos/trophy.png"></v-img>
                    </v-avatar>
                    <v-list-item class="text-center">
                      <v-list-item-content>
                        <v-list-item-title class="font-weight-bold headline">243</v-list-item-title>
                        <p class="small">Experience</p>
                      </v-list-item-content>
                    </v-list-item>
                  </v-card>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-container>
      </v-tab-item>
      <v-tab-item></v-tab-item>
      <v-tab-item></v-tab-item>
      <v-tab-item></v-tab-item>
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
    </v-tabs-items>
  </v-container>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
  created() {
    this.loadUser();
  },
  data: () => {
    return {
      tab: null,
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
        },
        followers: null,
        followings: null
      })
    };
  },
  methods: {
    loadUser() {
      axios.get("api/profile").then(({ data }) => this.userForm.fill(data));
    },
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
          this.userForm.profile.photo = response;
          this.$Progress.start();
          this.userForm
            .put("api/photo")
            .then(() => {
              this.loadUser();
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
