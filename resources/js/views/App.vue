<style scoped>
.v-list-item {
  text-decoration: none;
}
.v-dialog__content {
  flex-direction: row-reverse !important;
  bottom: 0 !important;
  right: 0 !important;
}
</style>

<template>
  <v-app id="inspire">
    <v-navigation-drawer v-model="drawer" width="250" app clipped>
      <v-list dense>
        <v-list-item>
          <v-list-item-avatar size="50">
            <img
              v-if="loaded"
              class="img-circle img-bordered border-success"
              :src="'storage/profile_photo/' + getCurrentUser.profile.photo"
              alt="Avatar"
            />
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title>
              <span>{{getCurrentUser.name}}</span>
              <div class="overline">{{getCurrentUser.type}}</div>
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
          <v-list-item-title>My Account</v-list-item-title>
        </v-list-item>

        <v-list-item to="/mailbox">
          <v-list-item-icon>
            <v-icon>mdi-email</v-icon>
          </v-list-item-icon>
          <v-list-item-title>
            Mailbox
            <v-chip class="ml-2" x-small dark color="red">{{getAllNotifications.length}} Unread</v-chip>
          </v-list-item-title>
        </v-list-item>

        <v-list-item to="/discussion">
          <v-list-item-icon>
            <v-icon>mdi-forum</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Discussions</v-list-item-title>
        </v-list-item>

        <v-list-item :to="getCurrentUser.store ? '/mystore' : '/thestore'">
          <v-list-item-icon>
            <v-icon>mdi-storefront</v-icon>
          </v-list-item-icon>
          <v-list-item-title>My Store</v-list-item-title>
        </v-list-item>

        <v-list-item to="/marketplace">
          <v-list-item-icon>
            <v-icon>mdi-store</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Marketplace</v-list-item-title>
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

    <v-navigation-drawer v-model="drawerRight" width="300px" app clipped right>
      <v-tabs centered v-model="tab" icons-and-text>
        <v-tab>
          <span class="small">Updates</span>
          <v-icon>mdi-mail</v-icon>
        </v-tab>
        <v-tab>
          <span class="small">Users</span>
          <v-icon>mdi-account-box</v-icon>
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tab">
        <v-tab-item>
          <v-list>
            <template v-for="item in getAllNotifications">
              <v-list-item v-if="item" :key="item.id" style="cursor:pointer">
                <v-badge offset-x="23" offset-y="20" avatar bordered overlap bottom>
                  <template v-slot:badge>
                    <v-avatar>
                      <v-icon>{{item.data[0].type === 'comment' ? 'mdi-chat' : 'mdi-email-send'}}</v-icon>
                    </v-avatar>
                  </template>

                  <v-avatar color="grey" class="mr-3">
                    <v-img :src="'storage/profile_photo/' + item.data[0].sender.profile.photo"></v-img>
                  </v-avatar>
                </v-badge>

                <v-list-item-content @click="viewNotification(item.data[0])">
                  <v-list-item-title class="small font-weight-black">{{item.data[0].notification}}</v-list-item-title>
                  <v-list-item-subtitle class="small">{{item.created_at | sinceDate}}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </template>
          </v-list>
        </v-tab-item>
        <v-tab-item>
          <v-list dense>
            <v-list-item-group>
              <v-list-item v-for="user in allFollowingUsers" :key="user.id">
                <v-list-item-avatar size="36">
                  <img :src="'storage/profile_photo/' + user.profile.photo" alt />
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title v-text="user.name"></v-list-item-title>
                  <v-list-item-subtitle v-text="user.type" class="overline"></v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-tab-item>
      </v-tabs-items>
    </v-navigation-drawer>

    <v-app-bar app clipped-left clipped-right dark color="teal darken-4">
      <v-app-bar-nav-icon class="white--text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title class="ml-0 pl-0">
        <a href="/" class="brand-link text-white">
          <i class="fas fa-seedling text-white"></i>
          <span class="subtitle-2">SFAWS</span>
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
        class="rounded-pill"
      ></v-text-field>

      <v-app-bar-nav-icon class="white--text" @click.stop="drawerRight = !drawerRight">
        <v-badge color="red" :content="getAllNotifications.length" overlap bordered>
          <v-icon class="text--accent">mdi-bell</v-icon>
        </v-badge>
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

    <!-- Chat component -->
    <!-- <v-btn absolute fab text>
      <beautiful-chat
        :participants="getParticipants"
        :messageList="getMessageList"
        :icons="icons"
        :isOpen="isChatOpen"
        :open="openChat"
        :close="closeChat"
        :onMessageWasSent="onMessageWasSent"
      />
    </v-btn>-->
  </v-app>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

// Chat Icons
import CloseIcon from "vue-beautiful-chat/src/assets/close-icon.png";
import OpenIcon from "vue-beautiful-chat/src/assets/logo-no-bg.svg";
import FileIcon from "vue-beautiful-chat/src/assets/file.svg";
import CloseIconSvg from "vue-beautiful-chat/src/assets/close.svg";

export default {
  props: {
    source: String
  },
  data: () => ({
    //Chat
    // isChatOpen: false,
    // icons: {
    //   open: {
    //     img: OpenIcon,
    //     name: "default"
    //   },
    //   close: {
    //     img: CloseIcon,
    //     name: "default"
    //   },
    //   file: {
    //     img: FileIcon,
    //     name: "default"
    //   },
    //   closeSvg: {
    //     img: CloseIconSvg,
    //     name: "default"
    //   }
    // },
    show_search: false,
    loaded: false,
    search: "",
    tab: null,
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
      },
      store: {}
    })
  }),

  computed: mapGetters([
    "getAllNotifications",
    "getCurrentUser",
    "allFollowingUsers"
  ]),

  methods: {
    ...mapActions([
      "fetchAllNotifications",
      "fetchCurrentUser",
      "fetchFollowingUsers",
      "fetchMessage"
    ]),

    viewNotification(item) {
      if (item.type === "message") {
        this.fetchMessage(item.data.id).then(() => {
          this.$router.push("/view-message");
        });
      } else if (item.type === "comment") {
        this.$router.push("/viewpost/" + item.data.id);
      }
      //   this.fetchMessage(item.message_id).then(() => {
      //     this.$router.push("/view-message");
      //   });
    },

    // onMessageWasSent(message) {
    //   let data = {
    //     'id' : this.getConversation.id,
    //     'message' : message.data.text
    //   }
    //   this.addMessage(data);
    // },

    // openChat(user) {
    //   this.fetchChat(user);
    //   this.isChatOpen = true;
    // },

    // closeChat() {
    //   this.isChatOpen = false;
    // },

    searchMode() {
      this.$router.push("/search");
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
    this.fetchCurrentUser().then(() => {
      this.loaded = true;
    });
    this.fetchFollowingUsers();
    this.fetchAllNotifications();
  },

  mounted() {
    // axios.get("api/profile").then(({ data }) => {
    //   this.user.fill(data);
    //   this.$vuetify.theme.dark = this.user.profile.theme;
    // });
  }
};
</script>
