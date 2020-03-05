<template>
  <v-container>
    <v-card flat>
      <v-toolbar flat>
        <v-dialog fullscreen v-model="dialog">
          <template v-slot:activator="{ on }">
            <v-btn v-on="on" color="success" rounded class="ml-4">Create Session</v-btn>
          </template>

          <v-card>
            <v-toolbar dark flat color="success">
              <v-btn @click="close" icon>
                <v-icon>mdi-close</v-icon>
              </v-btn>
              <v-toolbar-title>New Session</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-toolbar-items>
                <v-btn @click="close" text>Close</v-btn>
              </v-toolbar-items>
            </v-toolbar>

            <v-card-text>
              <v-card flat width="400" class="mx-auto">
                <v-card-text>
                  <v-select
                    v-model="session_form.user_id"
                    label="Select Expert"
                    :items="getAllExperts"
                    item-text="name"
                    item-value="id"
                    outlined
                    rounded
                    dense
                  ></v-select>
                  <v-text-field v-model="session_form.title" label="Session Title" outlined rounded></v-text-field>
                  <v-textarea v-model="session_form.description" label="Description" outlined dense></v-textarea>
                  <!-- Date Picker -->
                  <v-menu v-model="menu_date" :close-on-content-click="false" max-width="290">
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        :value="computedDate"
                        clearable
                        label="Scheduled Date"
                        readonly
                        prepend-icon="mdi-calendar"
                        v-on="on"
                        @click:clear="session_form.date = null"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="session_form.date"
                      @change="menu_date = false"
                      scrollable
                    ></v-date-picker>
                  </v-menu>
                  <!-- Time Picker -->
                  <v-dialog
                    ref="dialog"
                    v-model="dialog_time"
                    :return-value.sync="session_form.time"
                    persistent
                    width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        :value="computedTime"
                        label="Time Start"
                        prepend-icon="mdi-clock"
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-time-picker
                      v-if="dialog_time"
                      v-model="session_form.time"
                      scrollable
                      full-width
                    >
                      <v-spacer></v-spacer>
                      <v-btn text color="primary" @click="modal2 = false">Cancel</v-btn>
                      <v-btn text color="primary" @click="$refs.dialog.save(session_form.time)">OK</v-btn>
                    </v-time-picker>
                  </v-dialog>
                  <!-- Cover Photo -->
                  <v-file-input
                    ref="fileInput"
                    @change="onFileChange"
                    accept="image/*"
                    label="Cover Photo"
                    color="success"
                    outlined
                    rounded
                    dense
                    :clearable="false"
                    prepend-icon="mdi-camera"
                  ></v-file-input>
                  <v-avatar v-if="session_form.photo" size="250" tile>
                    <v-img aspect-ratio="2.3" :src="session_form.photo"></v-img>
                  </v-avatar>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn @click="close" text>Cancel</v-btn>
                  <v-btn @click="saveNewSession" text>Save</v-btn>
                </v-card-actions>
              </v-card>
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </v-card>
  </v-container>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  created() {
    this.fetchAllExperts();
  },

  computed: {
    ...mapGetters(["getAllExperts"]),
    computedDate() {
      return this.session_form.date
        ? moment(this.session_form.date).format("dddd, MMMM D YYYY")
        : "";
    },
    computedTime() {
      return this.session_form.time
        ? moment("2020-01-01 " + this.session_form.time).format("LT")
        : "";
    }
  },

  data() {
    return {
      dialog: false,
      menu_date: false,
      dialog_time: false,

      //Session Form
      session_form: new Form({
        user_id: "",
        title: "",
        description: "",
        date: "",
        time: "",
        photo: ""
      })
    };
  },

  methods: {
    ...mapActions(["fetchAllExperts", "addSession"]),

    saveNewSession() {
      // this.session_form.date = moment(this.date).format("YYYY-MM-DD")
      // this.session_form.time = moment(this.time).format("HH-mm-ss")
    },

    onFileChange(file) {
      if (file !== undefined) {
        let reader = new FileReader();
        reader.onloadend = file => {
          this.session_form.photo = reader.result;
        };
        reader.readAsDataURL(file);
      }
    },

    close() {
      this.dialog = false;
      this.session_form.reset();
      this.$refs.fileInput.reset();
    }
  }
};
</script>
