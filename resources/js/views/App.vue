<style scoped>
.v-list-item {
  text-decoration: none;
}
</style>

<template>
  <v-app id="inspire">
    <v-navigation-drawer v-model="drawer" app clipped>
      <v-list dense>
        <v-list-item>
          <v-list-item-avatar>
            <img :src="getProfilePhoto()" alt="Avatar">
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title>{{user.name | capitalize}}</v-list-item-title>
            <v-list-item-subtitle>{{user.type}}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
        <v-divider></v-divider>

        <v-list-item to="/home">
          <v-list-item-icon>
            <v-icon>mdi-home</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Home</v-list-item-title>
        </v-list-item>

        <v-list-item to="/profile">
          <v-list-item-icon>
            <v-icon>mdi-account</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Profile</v-list-item-title>
        </v-list-item>

        <v-list-group dark prepend-icon="fas fa-cogs">
          <template v-slot:activator>
            <v-list-item-title>Management</v-list-item-title>
          </template>
          <v-list-item to="/users">
            <v-list-item-title>Users</v-list-item-title>
            <v-list-item-icon>
              <v-icon>fas fa-users</v-icon>
            </v-list-item-icon>
          </v-list-item>
        </v-list-group>

        <v-list-item v-if="this.$vuetify.theme.dark" @click="themeLight">
          <v-list-item-icon>
            <v-icon>fas fa-tint</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Light Theme</v-list-item-title>
        </v-list-item>

        <v-list-item v-else @click="themeDark">
          <v-list-item-icon>
            <v-icon>fas fa-tint</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Dark Theme</v-list-item-title>
        </v-list-item>

        <v-list-item @click="logout">
          <v-list-item-icon>
            <v-icon>fas fa-times</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Log Out</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-navigation-drawer
      v-model="drawerRight"
      width="200px"
      app
      clipped
      right
    >
      <v-list dense>
        <v-list-item @click.stop="right = !right">
          <v-list-item-action>
            <v-icon>mdi-chat</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Chat Box</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <!-- <v-navigation-drawer >
      <template v-slot:prepend>
        <v-list-item two-line>
          <v-list-item-avatar>
            <img src="images/profile_photo/profile.png">
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title>Jane Smith</v-list-item-title>
            <v-list-item-subtitle>Logged In</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </template>

      <v-divider></v-divider>

      <v-list dense>
        <v-list-item>
          <v-list-item-icon>
            <v-icon>mdi-notification</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>Notification</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer> -->

    <v-app-bar app clipped-right clipped-left  color="secondary">
      <v-app-bar-nav-icon class="white--text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>
        <a href="/" class="brand-link text-white">
          <i class="fas fa-seedling text-white"></i>SFAWS
        </a>
      </v-toolbar-title>
      <div class="flex-grow-1"></div>
      <v-app-bar-nav-icon class="white--text" @click.stop="drawerRight = !drawerRight"><v-icon class="text--accent">mdi-bell</v-icon></v-app-bar-nav-icon>
    </v-app-bar>

    <!-- Sizes your content based upon application components -->
    <v-content>
      <!-- Provides the application the proper gutter -->
      <v-container fluid>
        <!-- If using vue-router -->
        <router-view></router-view>
      </v-container>
    </v-content>

    <v-footer app>
      <span>&copy; 2020</span>
    </v-footer>
  </v-app>
</template>

<script>

export default {
  props: {
    source: String
  },
  data: () => ({
    drawer: null,
    drawerRight: null,
    right: false,
    user: new Form ({
        name: '',
        type: '',
        username: '',
        email: '',
        password: '',
        profile : {
            bio: '',
          photo: '',
        }
    })
  }),
  mounted (){
     
},
  methods: {
    getProfilePhoto() {
      let photo = "storage/profile_photo/" + this.user.profile.photo;
      return photo;
    },
    logout() {
      axios.post("logout").then(response => {
        document.location.href = "login";
      });
    },
    themeLight() {
        this.$vuetify.theme.dark = false;  
         axios.put("api/theme", {
        theme: false
      })
    },
    themeDark() {
      this.$vuetify.theme.dark = true;  
       axios.put("api/theme", {
        theme: true
      })
    }
  },
  created() {
     Fire.$on('updateProfile',() => {
       axios.get("api/profile").then(({ data }) => {
            this.user.fill(data) 
          }); 
     });
        axios.get("api/profile").then(({ data }) => {
            this.user.fill(data)
            this.$vuetify.theme.dark = this.user.profile.theme
        }); 
  }
};
</script>