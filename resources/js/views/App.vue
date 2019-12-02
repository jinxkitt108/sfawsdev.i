<style scoped>
.v-list-item {
  text-decoration: none;
}
</style>

<template>
  <v-app id="inspire">
    <v-navigation-drawer v-model="drawer" width="250" app clipped>
      <v-list dense>
        <v-list-item>
          <v-list-item-avatar size="50">
            <v-img
              class="img-circle img-bordered border-success"
              :src="'storage/profile_photo/' + user.profile.photo"
              alt="Avatar"
            ></v-img>
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title>
              <span>{{user.name}}</span>
              <div class="overline">{{user.type}}</div>
            </v-list-item-title>
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

        <v-list-item to="/mystore">
          <v-list-item-icon>
            <v-icon>mdi-storefront</v-icon>
          </v-list-item-icon>
          <v-list-item-title>My Store</v-list-item-title>
        </v-list-item>

        <v-list-item to="/marketplace">
          <v-list-item-icon>
            <v-icon>mdi-store</v-icon>
          </v-list-item-icon>
          <v-list-item-title>SFAWS Market</v-list-item-title>
        </v-list-item>

        <v-list-group dark prepend-icon="mdi-cogs">
          <template v-slot:activator>
            <v-list-item-title>Management</v-list-item-title>
          </template>
          <v-list-item to="/users">
            <v-list-item-title class="ml-10">Users</v-list-item-title>
            <v-list-item-icon>
              <v-icon>mdi-account-group</v-icon>
            </v-list-item-icon>
          </v-list-item>
        </v-list-group>

        <v-list-item v-if="this.$vuetify.theme.dark" @click="themeLight">
          <v-list-item-icon>
            <v-icon>mdi-compare</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Light Theme</v-list-item-title>
        </v-list-item>

        <v-list-item v-else @click="themeDark">
          <v-list-item-icon>
            <v-icon>mdi-compare</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Dark Theme</v-list-item-title>
        </v-list-item>

        <v-list-item @click="logout">
          <v-list-item-icon>
            <v-icon class="red--text">mdi-logout</v-icon>
          </v-list-item-icon>
          <v-list-item-title class="red--text">Log Out</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-navigation-drawer v-model="drawerRight" width="250px" app clipped right>
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

    <v-app-bar app clipped-right clipped-left dark color="teal darken-4">
      <v-app-bar-nav-icon class="white--text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title class="ml-0 pl-0">
        <a href="/" class="brand-link text-white">
          <i class="fas fa-seedling text-white"></i>SFAWS
        </a>
      </v-toolbar-title>
      <div class="flex-grow-1"></div>
      <v-text-field
        v-model="search"
        @click="searchMode"
        @keyup.enter="searchit"
        cache-items
        hide-no-data
        hide-details
        flat
        label="What are you looking for?"
        append-icon="mdi-magnify"
        solo-inverted
      ></v-text-field>
      <div class="flex-grow-1"></div>
      <v-app-bar-nav-icon class="white--text" @click.stop="drawerRight = !drawerRight">
        <v-icon class="text--accent">mdi-bell</v-icon>
      </v-app-bar-nav-icon>
    </v-app-bar>

    <!-- Sizes your content based upon application components -->
    <v-content>
      <!-- If using vue-router -->
      <router-view></router-view>
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
    search: "",
    drawer: null,
    drawerRight: null,
    right: false,
    user: new Form({
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
  }),
  methods: {
    searchMode() {
      this.$router.push("/search");
      Fire.$emit("searchMode", this.search);
    },
    searchit() {
      Fire.$emit("searching", this.search);
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
      });
    },
    themeDark() {
      this.$vuetify.theme.dark = true;
      axios.put("api/theme", {
        theme: true
      });
    }
  },
  created() {
    Fire.$on("updateProfile", () => {
      axios.get("api/profile").then(({ data }) => {
        this.user.fill(data);
      });
    });
  },
  mounted() {
    axios.get("api/profile").then(({ data }) => {
      this.user.fill(data);
      this.$vuetify.theme.dark = this.user.profile.theme;
    });
  }
};
</script>