<template>
  <v-container>
    <v-data-iterator :items="getAllExperts" :search="search">
      <template v-slot:header>
        <v-toolbar flat>
          <v-toolbar-title>Experts</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" clearable prepend-inner-icon="mdi-magnify" label="Search"></v-text-field>
          <v-spacer></v-spacer>
          <v-btn @click="dialog_new_expert = true" color="success">New Expert</v-btn>
        </v-toolbar>
      </template>

      <!-- Experts Informations -->
      <template v-slot:default="props">
        <v-row>
          <v-col cols="12" v-for="expert in props.items" :key="expert.id">
            <v-hover v-slot:default="{ hover }">
              <v-card :elevation="hover ? 9 : 1">
                <v-card-text>
                  <v-list-item>
                    <v-list-item-avatar size="100" color="teal">
                      <v-img :src="'/storage/profile_photo/' + expert.profile.photo">
                        <template v-slot:placeholder>
                          <v-row class="fill-height ma-0" align="center" justify="center">
                            <v-progress-circular indeterminate color="success lighten-5"></v-progress-circular>
                          </v-row>
                        </template>
                      </v-img>
                    </v-list-item-avatar>

                    <v-list-item-content>
                      <v-list-item-title>
                        <span class="font-weight-bold">{{expert.name}}</span>
                        <div class="overline">{{expert.type}}</div>
                      </v-list-item-title>
                      <v-list-item-title>
                        <p class="small">Member since {{expert.created_at | sinceDate}}</p>
                      </v-list-item-title>
                      <v-list-item-subtitle>
                        <v-icon left>mdi-account-multiple</v-icon>
                        {{expert.followers.length}}
                      </v-list-item-subtitle>
                      <v-list-item-subtitle>
                        <v-icon left>mdi-video</v-icon>15
                      </v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </v-card-text>
                <v-divider class="my-0 py-0"></v-divider>
                <v-card-actions class="ml-10">
                  <v-btn @click="openProfile(expert)" text small>Profile</v-btn>
                  <v-btn text small>Schedule</v-btn>
                  <v-btn text small>Videos</v-btn>
                </v-card-actions>
              </v-card>
            </v-hover>
          </v-col>
        </v-row>
      </template>
    </v-data-iterator>

    <!-- Dialog for Adding an Expert -->
    <v-dialog v-model="dialog_new_expert" fullscreen transition="dialog-bottom-transition">
      <v-card flat>
        <v-toolbar flat color="success" dark dense>
          <v-btn @click="close" icon>
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>New Expert</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn @click="close" text>Close</v-btn>
        </v-toolbar>

        <v-form>
          <v-card-text class="my-0">
            <v-stepper v-model="exForm" class="shadow-none">
              <v-stepper-header>
                <v-stepper-step :complete="exForm >= 2" :step="1" editable>Settings</v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step :complete="exForm >= 3" :step="2" editable>Experties</v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step :complete="exForm == 4" :step="3" editable>Career Information</v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step :step="4" editable>Achievements</v-stepper-step>
              </v-stepper-header>

              <v-stepper-items>
                <v-stepper-content :step="1">
                  <v-card flat width="400" class="mx-auto">
                    <v-card-title class="subtitle-1 font-weight-bold">App Credentials:</v-card-title>
                    <v-card-text class="flex-shrink-1">
                      <v-text-field v-model="expert_form.name" label="Name" dense></v-text-field>
                      <v-text-field v-model="expert_form.email" type="email" label="Email" dense></v-text-field>
                      <v-text-field
                        v-model="expert_form.website"
                        label="Website"
                        color="success"
                        hint="Leave empty if not available."
                        persistent-hint
                        outlined
                        rounded
                        dense
                      ></v-text-field>
                      <v-text-field
                        v-model="expert_form.username"
                        prepend-icon="mdi-account"
                        label="Username"
                        dense
                        outlined
                        rounded
                      ></v-text-field>
                      <v-text-field
                        v-model="expert_form.password"
                        prepend-icon="mdi-key"
                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="showPassword ? 'text' : 'password'"
                        @click:append="showPassword = !showPassword"
                        hint="At least 8 characters long."
                        label="Password"
                        dense
                        outlined
                        rounded
                      ></v-text-field>
                      <v-file-input
                        ref="avatar_input"
                        @change="setUpCroppie"
                        accept="image/*"
                        label="Photo"
                        color="success"
                        outlined
                        rounded
                        dense
                        :clearable="false"
                        prepend-icon="mdi-camera"
                      ></v-file-input>

                      <div v-if="avatar" class="text-center">
                        <v-avatar size="164">
                          <v-img :src="this.expert_form.photo"></v-img>
                        </v-avatar>
                        <v-btn icon color="success">
                          <v-icon>mdi-check</v-icon>
                        </v-btn>
                      </div>

                      <div v-show="croppie" class="text-center">
                        <vue-croppie
                          ref="croppieRef"
                          :enableOrientation="true"
                          :viewport="{ width: 164, height: 164, type: 'square'}"
                          :boundary="{ width: 164, height: 250}"
                          :enableResize="false"
                          :showZoomer="false"
                        ></vue-croppie>
                        <v-btn @click="saveCrop" small outlined rounded color="success">Crop</v-btn>
                      </div>
                    </v-card-text>
                  </v-card>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text color="success" @click="exForm = 2">
                      <v-icon left>mdi-arrow-right</v-icon>Next
                    </v-btn>
                  </v-card-actions>
                </v-stepper-content>

                <v-stepper-content :step="2">
                  <v-card flat>
                    <div
                      class="subtitle-2 font-weight-bold text-center"
                    >What are the area of experties covered?</div>
                    <v-card-text>
                      <div class="text-center">
                        <v-select
                          v-model="selected_categories"
                          :items="categories"
                          :menu-props="{ maxHeight: '150' }"
                          label="Select"
                          class="d-inline-flex"
                          multiple
                          hint="Pick categories"
                          item-text="name"
                          return-object
                          clearable
                          chips
                          outlined
                          rounded
                          dense
                          deletable-chips
                          persistent-hint
                        ></v-select>
                      </div>
                      <v-container>
                        <v-row dense justify="center">
                          <v-col
                            sm="12"
                            md="4"
                            v-for="(item, index) in selected_categories"
                            :key="index"
                          >
                            <v-subheader class="subtitle-1">{{item.name}}</v-subheader>
                            <v-divider class="pt-0 mt-0"></v-divider>
                            <v-flex v-for="(job, i) in item.jobs" :key="i">
                              <input type="checkbox" v-model="expert_form.jobs" :value="job" />
                              <span class="ml-1 subtitle-2">{{job}}</span>
                            </v-flex>
                          </v-col>
                        </v-row>
                      </v-container>
                    </v-card-text>
                  </v-card>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text color="success" @click="exForm = 1">
                      <v-icon left>mdi-arrow-left</v-icon>Back
                    </v-btn>
                    <v-btn text color="success" @click="exForm = 3">
                      <v-icon left>mdi-arrow-right</v-icon>Next
                    </v-btn>
                  </v-card-actions>
                </v-stepper-content>

                <v-stepper-content :step="3">
                  <v-card flat width="400" class="mx-auto">
                    <v-card-title class="subtitle-1 font-weight-bold">Career Info</v-card-title>
                    <v-card-text>
                      <v-text-field v-model="expert_form.work" label="Work" outlined rounded dense></v-text-field>
                      <v-text-field
                        v-model="expert_form.company_name"
                        label="Company Name"
                        outlined
                        rounded
                        dense
                      ></v-text-field>
                      <v-text-field
                        v-model="expert_form.position"
                        label="Position"
                        outlined
                        rounded
                        dense
                      ></v-text-field>
                      <v-textarea
                        rows="4"
                        v-model="expert_form.work_description"
                        label="Work Description"
                        outlined
                        dense
                      ></v-textarea>
                    </v-card-text>
                  </v-card>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text color="success" @click="exForm = 2">
                      <v-icon left>mdi-arrow-left</v-icon>Back
                    </v-btn>
                    <v-btn text color="success" @click="exForm = 4">
                      <v-icon left>mdi-arrow-right</v-icon>Next
                    </v-btn>
                  </v-card-actions>
                </v-stepper-content>

                <v-stepper-content :step="4">
                  <v-card flat width="400" class="mx-auto">
                    <v-card-title class="subtitle-1 font-weight-bold">Achievements:</v-card-title>
                    <v-card-text>
                      <v-subheader class="subtitle-1">Education</v-subheader>
                      <v-text-field v-model="expert_form.school" label="School" outlined dense></v-text-field>
                      <v-select
                        v-model="expert_form.degree"
                        label="Degree"
                        :items="['Associate Degree', 'Bachelor\'s Degree', 'Master\s Degree', 'Doctoral Degree']"
                        outlined
                        rounded
                        dense
                      ></v-select>
                      <v-text-field
                        v-model="training"
                        @keyup.enter="addTraining"
                        append-outer-icon="mdi-plus"
                        @click:append-outer="addTraining"
                        label="Trainings"
                        outlined
                        rounded
                        dense
                      ></v-text-field>
                      <v-chip
                        v-show="expert_form.trainings"
                        v-for="(t, i) in expert_form.trainings"
                        :key="i"
                        close
                        @click:close="removeTraining(i)"
                        class="ma-1"
                      >{{t}}</v-chip>
                      <v-file-input
                        ref="uploadFileReference"
                        @change="uploadFileReference"
                        chips
                        multiple
                        accept="image/png, image/jpeg, image/bmp"
                        placeholder="Pick photos"
                        prepend-icon="mdi-camera"
                        label="Attach Photos"
                        class="mt-2"
                      ></v-file-input>

                      <!-- Image Previews -->
                      <v-container fluid>
                        <v-row>
                          <v-col
                            v-for="(file, idx) in expert_form.attachments"
                            :key="idx"
                            class="d-flex child-flex"
                            cols="4"
                          >
                            <v-card flat tile class="d-flex">
                              <v-btn icon small @click="removeFile(file)">
                                <v-icon>mdi-close</v-icon>
                              </v-btn>
                              <v-img :src="file.url" aspect-ratio="1" class="grey lighten-2">
                                <template v-slot:placeholder>
                                  <v-row class="fill-height ma-0" align="center" justify="center">
                                    <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                                  </v-row>
                                </template>
                              </v-img>
                            </v-card>
                          </v-col>
                        </v-row>
                      </v-container>
                    </v-card-text>
                  </v-card>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text color="success" @click="exForm = 3">
                      <v-icon left>mdi-arrow-left</v-icon>Back
                    </v-btn>
                    <v-btn text color="success" @click="saveNewExpert">Finish</v-btn>
                  </v-card-actions>
                </v-stepper-content>
              </v-stepper-items>
            </v-stepper>
          </v-card-text>
        </v-form>
      </v-card>
    </v-dialog>

    <!-- Dialog for viewing Expert Profile -->
    <v-dialog v-model="dialog_view_expert" transition="dialog-bottom-transition" fullscreen>
      <v-card flat>
        <v-toolbar flat dark color="success">
          <v-btn @click="close" icon>
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>Expert Profile</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn @click="close" text>Close</v-btn>
        </v-toolbar>

        <v-card v-if="loaded_expert" flat>
          <v-card-text>
            <v-row dense justify="center">
              <v-col cols="12" sm="12" md="2">
                <v-avatar tile size="150">
                  <v-img :src="'storage/profile_photo/' + getExpert.profile.photo"></v-img>
                </v-avatar>
              </v-col>
              <v-col cols="12" sm="12" md="6">
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title class="headline font-weight-bold">{{getExpert.name}}</v-list-item-title>
                    <v-list-item-subtitle>{{getExpert.type}}</v-list-item-subtitle>
                    <v-list-item-subtitle>
                      <span
                        class="success--text"
                        @click="openWebsite(getExpert.expert_info.website)"
                      >{{getExpert.expert_info.website}}</span>
                    </v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
              </v-col>
            </v-row>

            <v-row dense justify="center">
              <v-subheader class="my-0">AREA OF EXPERTIES</v-subheader>
              <v-col cols="12" sm="12" md="2">
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title class="body-1 font-weight-bold">Categories</v-list-item-title>
                    <v-list-item-subtitle
                      class="body-2"
                      v-for="(ctgory, index) in getExpert.expert_info.categories"
                      :key="index"
                    >{{ctgory}}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
              </v-col>

              <v-col cols="12" sm="12" md="2">
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title class="body-1 font-weight-bold">Jobs Covered</v-list-item-title>
                    <v-list-item-subtitle
                      class="body-2"
                      v-for="(job, index) in getExpert.expert_info.jobs"
                      :key="index"
                    >{{job}}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
              </v-col>
            </v-row>
            <v-container>
              <v-row dense justify="center">
                <v-subheader class="my-0">CAREER INFO</v-subheader>
                <v-col cols="12" sm="12" md="4">
                  <v-text-field
                    label="Company Name"
                    :value="getExpert.expert_info.company_name"
                    outlined
                    rounded
                    dense
                    readonly
                  ></v-text-field>
                  <v-text-field label="Work" :value="getExpert.expert_info.work" dense readonly></v-text-field>
                  <v-text-field
                    label="Position"
                    :value="getExpert.expert_info.position"
                    dense
                    readonly
                  ></v-text-field>
                  <v-textarea
                    label="Work Description"
                    :value="getExpert.expert_info.work_description"
                    outlined
                    dense
                    readonly
                  ></v-textarea>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
        </v-card>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  created() {
    this.fetchAllExperts();
  },

  computed: {
    ...mapGetters(["getAllExperts", "getExpert"])
  },

  data() {
    return {
      search: null,
      dialog_new_expert: false,
      dialog_view_expert: false,
      loaded_expert: false,
      showPassword: false,
      training: "",
      croppie: false,
      cropped: false,
      avatar: false,

      //Steppers
      exForm: 1,

      //   Autocomoplete
      categories: [
        {
          name: "Agriculture Mechanics",
          jobs: [
            "Assembly Technician",
            "Automation Coordinator/Technician",
            "Electrician/Electronics Technician",
            "Hydraulics Technician",
            "Maintenance/Service Technician",
            "Mechanical Engineer",
            "Parts Manager",
            "Precision Agriculture Specialist",
            "Service Writer"
          ]
        },
        {
          name: "Agriculture Engineers",
          jobs: [
            "Bioprocessing Engineer",
            "Design Engineer or Machine Design Engineer",
            "Electrical Engineer",
            "Food Engineer or Structural Engineer",
            "Geospatial Analytics Scientist",
            "Irrigation Engineer",
            "Sanitary/Waste Handling/Environmental Engineer",
            "Software Engineers and Software Architects"
          ]
        },
        {
          name: "Agribusiness",
          jobs: [
            "Agriculture Financial Service Representative",
            "Advisor Government/Stakeholder Relations",
            "Agriculture Lawyer",
            "Agricultural Lobbyist",
            "Cotton Gin and Warehouse Manager",
            "Crop Adjuster",
            "Farm Business Manager/Accountant",
            "Farm Production Manager",
            "Feed Mill Manager",
            "Grain Buyer",
            "Grain Elevator Manager"
          ]
        },
        {
          name: "Agricultural Communications",
          jobs: [
            "Account Manager",
            "Brand Manager",
            "Market News or Farm News Journalist/Reporter",
            "Public Relations Representative",
            "Regional Sales Manager"
          ]
        },
        {
          name: "Agricultural Economics",
          jobs: [
            "Agricultural Lender",
            "Agricultural Policy Analyst",
            "Crop or Agriculture Insurance Agent",
            "Economist Consultant",
            "Food Distribution Manager",
            "Sales Representative for Equipment and Ag Systems"
          ]
        },
        {
          name: "Agricultural Agronomy",
          jobs: [
            "Environmental Specialist",
            "Greenhouse Manager",
            "Soil Scientist/Soil Conservationist",
            "Seed Production Agronomist",
            "Viticulturist",
            "Weed Scientist"
          ]
        },
        {
          name: "Animal Sciences",
          jobs: [
            "Animal Geneticist",
            "Apiary Worker/Beekeeper",
            "Artificial Insemination Technician",
            "Feed Sales/Management",
            "Livestock Feedlot Operator",
            "Livestock Hauler",
            "Livestock Insurance Representative",
            "Livestock Procurement/Buyer",
            "Livestock Production Manager",
            "Ranch/Farm Hand",
            "Rendering Truck Driver/Rendering Maintenance Technician",
            "Veterinarian"
          ]
        },
        {
          name: "Food Sciences",
          jobs: [
            "Food Product Research and Development",
            "Quality Assurance",
            "Food Chemist/Microbiologist/Researcher",
            "Food Manufacturing"
          ]
        }
      ],
      selected_categories: [],

      //New Expert Form
      expert_form: new Form({
        //Settings
        name: "",
        email: "",
        website: "",
        username: "",
        password: "",
        photo: "",

        //Experties
        categories: [],
        jobs: [],

        //Career
        work: "",
        company_name: "",
        position: "",
        work_description: "",

        //Achievements
        school: "",
        degree: "",
        trainings: [],
        attachments: []
      })
    };
  },

  methods: {
    ...mapActions(["fetchAllExperts", "addExpert", "fetchExpert"]),

    openWebsite(url) {
      window.open(url, "_blank");
    },

    openProfile(expert) {
      this.fetchExpert(expert.id).then(() => {
        this.dialog_view_expert = true;
        this.loaded_expert = true;
      });
    },

    saveCrop() {
      // Saving Croppie Result
      let options = {
        format: "jpeg",
        type: "canvas",
        size: "viewport"
      };

      this.$refs.croppieRef
        .result(options, output => {
          this.expert_form.photo = output;
        })
        .then(() => {
          this.cropped = true;
          this.croppie = false;
          this.avatar = true;
          this.$refs.avatar_input.value = "";
        });
    },

    setUpCroppie(file) {
      this.croppie = true;
      this.cropped = false;
      let image = file;
      console.log(image);
      // To check if file is an image
      if (!image.name.match(/.(jpg|jpeg|png|gif)$/i)) {
        Swal.fire({
          type: "error",
          title: "Oops...",
          text: "That is not an image!"
        });
        file = null;
        return;
      }

      let reader = new FileReader();
      reader.readAsDataURL(image);
      reader.onload = image => {
        this.$refs.croppieRef.bind({
          url: reader.result
        });
      };
    },

    removeFile(file) {
      let index = this.expert_form.attachments.findIndex(
        item => item.url == file.url
      );
      if (index !== -1) {
        this.expert_form.attachments.splice(index, 1);
      }
    },

    uploadFileReference(files) {
      console.log(files);

      if (files.length > 1) {
        const test = Array.from(files).forEach((file, idx) => {
          const fileReader = new FileReader();
          const getResult = new Promise(resolve => {
            fileReader.onload = e => {
              this.expert_form.attachments.push({
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
          this.expert_form.attachments.push({
            url: e.target.result
          });
        };
        fileReader.readAsDataURL(files[0]);
      }
    },

    addTraining() {
      this.expert_form.trainings.push(this.training);
      this.training = "";
    },

    removeTraining(index) {
      this.expert_form.trainings.splice(index, 1);
    },

    close() {
      this.dialog_new_expert = false;
      this.dialog_view_expert = false;
      this.expert_form.reset();
    },

    saveNewExpert() {
      let ctgory = this.selected_categories;
      ctgory.forEach((item, index) => {
        this.expert_form.categories.push(item.name);
      });
      //Then Sending http request saving expert into DB
      this.addExpert(this.expert_form).then(() => {
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Expert successfuly added!",
          showConfirmButton: false,
          timer: 1500
        });
        this.close();
      });
    }
  }
};
</script>
