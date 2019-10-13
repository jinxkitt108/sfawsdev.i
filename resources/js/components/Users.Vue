<template>
  <v-card>
    <v-card-title>Admin Panel</v-card-title>
    <v-card-text>
      <v-data-table 
        :headers="headers"
        :items="users.data"
        :items-per-page="5"
        sort-by="created_at"
        sort-desc
        class="elevation-1 "
        >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Users Table</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-toolbar-title class="subtitle">Total Users: {{count}}</v-toolbar-title>

            <div class="flex-grow-1"></div>
            <v-dialog v-model="dialog" max-width="600px">
              <template v-slot:activator="{ on }">
                <v-btn @click="newUser()" color="primary" dark class="mb-2">New User</v-btn>
              </template>
              <!-- DIALOG FOR ADD/EDIT -->
              <v-card>
                <v-card-title>
                  <span class="headline">{{ formTitle }}</span>
                </v-card-title>
                <v-card-text>
                  <v-container>
                    <form @submit.prevent="editMode ? updateUser() : createUser()">
                      <v-row>
                        <v-col cols="12" sm="8" md="8">
                          <v-text-field
                            v-model="userForm.name"
                            label="Name"
                            :error-messages="nameErrors"
                            required
                            @input="$v.userForm.name.$touch()"
                            @blur="$v.userForm.name.$touch()"
                            outlined
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="4" md="4">
                          <v-select
                            v-model="userForm.type"
                            :items="['Farmer', 'Supplier', 'Expert', 'Admin']"
                            label="Type"
                            :error-messages="typeErrors"
                            required
                            @input="$v.userForm.type.$touch()"
                            @blur="$v.userForm.type.$touch()"
                            aria-required
                            outlined
                          ></v-select>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="6">
                          <v-text-field
                            v-model="userForm.username"
                            label="Username"
                            name="username"
                            counter
                            :error-messages="usernameErrors"
                            required
                            @input="$v.userForm.username.$touch()"
                            @blur="$v.userForm.username.$touch()"
                            outlined
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-text-field
                            v-model="userForm.email"
                            label="E-mail"
                            name="email"
                            :error-messages="emailErrors"
                            required
                            @input="$v.userForm.email.$touch()"
                            @blur="$v.userForm.email.$touch()"
                            outlined
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-text-field
                        v-model="userForm.password"
                        label="Password"
                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="showPassword ? 'text' : 'password'"
                        counter
                        :error-messages="editMode === false ? passwordErrors : ''"
                        
                        @input="$v.userForm.password.$touch()"
                        @blur="$v.userForm.password.$touch()"
                        outlined
                        @click:append="showPassword = !showPassword"
                      ></v-text-field>
                      <v-btn type="submit" color="primary" class="mr-4">submit</v-btn>
                      <v-btn @click="close">Cancel</v-btn>
                    </form>
                  </v-container>
                </v-card-text>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:item.name="{ item }">
          {{ item.name | capitalize }}
        </template>
        <template v-slot:item.created_at="{ item }">
          {{ item.created_at | createdDate }}
        </template>
        <template v-slot:item.action="{ item }">
            <v-icon  class="mr-2 green--text" @click="editUser(item)">mdi-pen</v-icon>
            <v-icon   class="red--text" @click="deleteUser(item)">mdi-delete</v-icon>
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>
<script>
import { validationMixin } from "vuelidate";
import {
  required,
  maxLength,
  minLength,
  email
} from "vuelidate/lib/validators";

export default {
  mixins: [validationMixin],
  validations: {
    userForm: {
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
  data() {
    return {
      showPassword: false,
      userForm: new Form({
        id:"",
        name: "",
        type: "",
        username: "",
        email: "",
        password: ""
      }),
      dialog: false,
      users: {},
      count: null,
      headers: [
        {
          text: 'Id',
          align: 'left',
          class: 'secondary white--text subtitle-2',
          //sortable: false,
          value: 'id',
        },
        { text: 'Name', value: 'name', class: 'secondary white--text subtitle-2', },
        { text: 'Type', value: 'type', class: 'secondary white--text subtitle-2', },
        { text: 'Date Registered', value: 'created_at', class: 'secondary white--text subtitle-2',},
        { text: 'Actions', value: 'action', sortable: false , class: 'secondary white--text subtitle-2',},
      ],
      editMode: false
    };
  },
  computed: {
    formTitle() {
      return this.editMode === false ? "New User" : "Edit User";
    },
    // ERRORS FOR INPUTS
    nameErrors() {
      const errors = [];
      if (!this.$v.userForm.name.$dirty) return errors;
      !this.$v.userForm.name.required && errors.push("Name is required.");
      return errors;
    },
    typeErrors() {
      const errors = [];
      if (!this.$v.userForm.type.$dirty) return errors;
      !this.$v.userForm.type.required && errors.push("Type is required");
      return errors;
    },
    usernameErrors() {
      const errors = [];
      if (!this.$v.userForm.username.$dirty) return errors;
      !this.$v.userForm.username.minLength &&
        errors.push("Username must be at least 4 characters");
      !this.$v.userForm.username.maxLength &&
        errors.push("Username must be less than 10 characters");
      !this.$v.userForm.username.required &&
        errors.push("Username is required");
      return errors;
    },
    emailErrors() {
      const errors = [];
      if (!this.$v.userForm.email.$dirty) return errors;
      !this.$v.userForm.email.required && errors.push("E-mail is required");
      !this.$v.userForm.email.email && errors.push("Must be valid e-mail");
      //!this.$v.userForm.email.isUnique && errors.push("Email already taken!");
      return errors;
    },
    passwordErrors() {
      const errors = [];
      if (!this.$v.userForm.password.$dirty) return errors;
      !this.$v.userForm.password.required &&
        errors.push("Password is required");
      !this.$v.userForm.password.minLength &&
        errors.push("Must be 8 characters long");
      return errors;
    }
  },
  methods: {
      //New User Button Function
      newUser() {
          this.editmode = false;
          this.userForm.reset();
          this.dialog = true
      },
      editUser(item){
          this.editMode = true
          this.userForm.fill(item);
          this.dialog = true
      },
    //Update User
     updateUser() {
      this.$v.$touch();
      this.$Progress.start();
      if (this.userForm.password == "") {
        this.userForm.password = undefined;
      }
      this.userForm
        .put("api/user/" + this.userForm.id)
        .then(() => {
          $("#userModal").modal("hide");
          Swal.fire(
            "Updated!",
            "User information has been updated.",
            "success"
          );
          this.$Progress.finish();
          Fire.$emit("updateProfile");
          this.close()
          this.loadUsers();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    //Delete User
    deleteUser(user) {
      let id = user['id'];
      console.log(id);
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
          this.userForm
            .delete("api/user/" + id)
            .then(() => {
              Swal.fire("Deleted!", "User has been deleted.", "success");
              this.loadUsers();
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
    loadUsers() {
      axios.get("api/user").then(({ data }) => {
        this.users = data
        this.count = data.data.length
        this.$store.commit('loadUsers', this.users)
      });
    },
    createUser() {
      this.$v.$touch();
      this.userForm
        .post("api/user")
        .then(() => {
          this.loadUsers();
          Swal.fire("Success!", "User has been added.", "success");
          this.$Progress.finish();
          this.close()
        })
        .catch(() => {
          Swal.fire({
            type: "error",
            title: "Oops...",
            text: "Something went wrong!"
          });
        });
    },
    close () {
        this.dialog = false
        this.clear()
        setTimeout(() => {
          this.editMode = false
        }, 300)
      },
    clear() {
      this.$v.$reset();
      this.userForm.reset();
    }
  },
  created() {
    this.loadUsers();
  }
};
</script>