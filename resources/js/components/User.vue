<template>
  <v-container>
    <v-data-table :headers="headers_user" :items="getAllUsers" :search="search">
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title class="font-weight-bold">Users List</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search message.."
            single-line
            hide-details
          ></v-text-field>
          <v-btn @click="dialog_add_user = true" color="success" class="ml-2" outlined dark>
            <v-icon left>mdi-plus</v-icon>Add
          </v-btn>
        </v-toolbar>
      </template>

      <!-- Custom Column Content -->
      <template v-slot:item.created_at="{ item }">{{item.created_at | createdDate}}</template>
      <template v-slot:item.actions="{ item }">
        <v-btn @click="deletingUser(item.id)" icon small>
          <v-icon>mdi-delete</v-icon>
        </v-btn>|
        <v-btn @click="editingUser(item)" icon small>
          <v-icon>mdi-pen</v-icon>
        </v-btn>
      </template>
    </v-data-table>

    <!-- New/Edit User Dialog -->
    <v-dialog v-model="dialog_add_user" width="450">
      <v-card>
        <v-toolbar flat color="success" dark>
          <v-btn @click="close" icon>
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>{{edit_mode ? 'Edit User' : 'New User'}}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn @click="close" text dark>Close</v-btn>
        </v-toolbar>

        <v-form @submit.prevent="edit_mode? updatingUser() : saveNewUser()" lazy-validation>
          <v-card-text>
            <v-row>
              <v-col cols="12" sm="8" md="8">
                <v-text-field
                  v-model="user_form.name"
                  label="Name"
                  :error-messages="nameErrors"
                  required
                  @input="$v.user_form.name.$touch()"
                  @blur="$v.user_form.name.$touch()"
                  outlined
                  dense
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="4" md="4">
                <v-select
                  v-model="user_form.type"
                  :items="['Farmer', 'Supplier', 'Admin']"
                  label="Type"
                  :error-messages="typeErrors"
                  required
                  @input="$v.user_form.type.$touch()"
                  @blur="$v.user_form.type.$touch()"
                  aria-required
                  outlined
                  dense
                ></v-select>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="6" md="6">
                <v-text-field
                  v-model="user_form.username"
                  label="Username"
                  name="username"
                  counter
                  :error-messages="usernameErrors"
                  required
                  @input="$v.user_form.username.$touch()"
                  @blur="$v.user_form.username.$touch()"
                  outlined
                  dense
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-text-field
                  v-model="user_form.email"
                  label="E-mail"
                  name="email"
                  :error-messages="emailErrors"
                  required
                  @input="$v.user_form.email.$touch()"
                  @blur="$v.user_form.email.$touch()"
                  outlined
                  dense
                ></v-text-field>
              </v-col>
            </v-row>
            <v-text-field
              v-model="user_form.password"
              label="Password"
              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              :type="showPassword ? 'text' : 'password'"
              counter
              :error-messages="edit_mode === false ? passwordErrors : ''"
              @input="$v.user_form.password.$touch()"
              @blur="$v.user_form.password.$touch()"
              outlined
              dense
              @click:append="showPassword = !showPassword"
            ></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn @click="close" text>Cancel</v-btn>
            <v-btn type="submit" text>Save</v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import {
  required,
  maxLength,
  minLength,
  email
} from "vuelidate/lib/validators";

export default {
  created() {
    this.fetchAllUsers();
  },

  validations: {
    user_form: {
      name: { required },
      type: { required },
      username: { required, minLength: minLength(4), maxLength: maxLength(10) },
      email: {
        required,
        email
      },
      password: { required, minLength: minLength(8) }
    }
  },

  computed: {
    ...mapGetters(["getAllUsers"]),

    // ERRORS FOR INPUTS
    nameErrors() {
      const errors = [];
      if (!this.$v.user_form.name.$dirty) return errors;
      !this.$v.user_form.name.required && errors.push("Name is required.");
      return errors;
    },
    typeErrors() {
      const errors = [];
      if (!this.$v.user_form.type.$dirty) return errors;
      !this.$v.user_form.type.required && errors.push("Type is required");
      return errors;
    },
    usernameErrors() {
      const errors = [];
      if (!this.$v.user_form.username.$dirty) return errors;
      !this.$v.user_form.username.minLength &&
        errors.push("Username must be at least 4 characters");
      !this.$v.user_form.username.maxLength &&
        errors.push("Username must be less than 10 characters");
      !this.$v.user_form.username.required &&
        errors.push("Username is required");
      return errors;
    },
    emailErrors() {
      const errors = [];
      if (!this.$v.user_form.email.$dirty) return errors;
      !this.$v.user_form.email.required && errors.push("E-mail is required");
      !this.$v.user_form.email.email && errors.push("Must be valid e-mail");
      //!this.$v.userForm.email.isUnique && errors.push("Email already taken!");
      return errors;
    },
    passwordErrors() {
      const errors = [];
      if (!this.$v.user_form.password.$dirty) return errors;
      !this.$v.user_form.password.required &&
        errors.push("Password is required");
      !this.$v.user_form.password.minLength &&
        errors.push("Must be 8 characters long");
      return errors;
    }
  },

  watch: {
    dialog_add_user(val) {
      !val && this.close();
    }
  },

  data() {
    return {
      dialog_add_user: false,
      edit_mode: false,
      showPassword: false,

      //Users Table
      search: null,
      headers_user: [
        { text: "Name", value: "name", filterable: "true", align: "center" },
        { text: "Type", value: "type", align: "center" },
        { text: "Email", value: "email", align: "center" },
        {
          text: "Registered",
          value: "created_at",
          sortable: "false",
          align: "center"
        },
        {
          text: "Actions",
          value: "actions",
          sortable: "false",
          align: "center"
        }
      ],

      user_form: new Form({
        id: "",
        name: "",
        type: "",
        username: "",
        email: "",
        password: ""
      })
    };
  },

  methods: {
    ...mapActions(["fetchAllUsers", "addUser", "deleteUser", "updateUser"]),

    // Update User
    editingUser(user) {
      this.edit_mode = true;
      this.user_form.fill(user);
      this.dialog_add_user = true;
    },

    updatingUser() {
      this.$v.$touch();
      this.updateUser(this.user_form).then(() => {
        this.close();
        Toast.fire({
          type: "success",
          title: "New user added!"
        });
      });
    },

    //Delete User
    deletingUser(id) {
      swalWithBootstrapButtons
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "No, cancel!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            this.deleteUser(id).then(() => {
              swalWithBootstrapButtons.fire(
                "Deleted!",
                "User has been deleted.",
                "success"
              );
            });
          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire(
              "Cancelled",
              "User is safe :)",
              "error"
            );
          }
        });
    },

    // Add User
    saveNewUser() {
      this.$v.$touch();
      this.addUser(this.user_form).then(() => {
        this.close();
        Toast.fire({
          type: "success",
          title: "New user added!"
        });
      });
    },
    close() {
      this.dialog_add_user = false;
      this.user_form.reset();
      this.$v.$reset();
      this.edit_mode = false;
    }
  }
};
</script>
