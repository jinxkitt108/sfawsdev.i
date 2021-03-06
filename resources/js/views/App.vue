<style scoped>
.v-list-item {
  text-decoration: none;
}
.v-dialog__content {
  flex-direction: row-reverse !important;
  bottom: 0 !important;
  right: 0 !important;
}

.scroll-area {
  position: relative;
  margin: auto;
  height: 355px;
}

.scroll-message {
  position: relative;
  margin: auto;
  height: 330px;
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

        <!-- Admin Managmente Links -->
        <v-list-group v-if="$gate.isAdmin()" dark prepend-icon="mdi-cogs">
          <template v-slot:activator>
            <v-list-item-title>Management</v-list-item-title>
          </template>
          <v-list-item to="/users">
            <v-list-item-title class="ml-10">Users</v-list-item-title>
            <v-list-item-icon>
              <v-icon>mdi-account-group</v-icon>
            </v-list-item-icon>
          </v-list-item>
          <v-list-item to="/experts">
            <v-list-item-title class="ml-10">Experts</v-list-item-title>
            <v-list-item-icon>
              <v-icon>mdi-account-tie</v-icon>
            </v-list-item-icon>
          </v-list-item>
        </v-list-group>

        <v-list-item to="/home">
          <v-list-item-icon>
            <v-icon>mdi-home</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Home</v-list-item-title>
        </v-list-item>

        <v-list-item :to="$gate.isAdmin() ? '/admin_session' : '/session'">
          <v-list-item-icon>
            <v-icon>mdi-video-input-antenna</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Sessions</v-list-item-title>
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
            <v-chip
              v-show="getAllNotifications.length > 0"
              class="ml-2"
              x-small
              dark
              color="red"
            >{{getAllNotifications.length}} Unread</v-chip>
          </v-list-item-title>
        </v-list-item>

        <v-list-item to="/discussion">
          <v-list-item-icon>
            <v-icon>mdi-forum</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Discussions</v-list-item-title>
        </v-list-item>

        <v-list-item v-if="$gate.isFarmerOrSupplier()" :to="getCurrentUser.store ? '/mystore' : '/thestore'">
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
                      <v-icon>{{item.data[0].type === 'comment' ? 'mdi-comment-multiple' : 'mdi-email-send'}}</v-icon>
                    </v-avatar>
                  </template>

                  <v-avatar color="grey" class="mr-3">
                    <v-img :src="'storage/profile_photo/' + item.data[0].sender.profile.photo"></v-img>
                  </v-avatar>
                </v-badge>

                <v-list-item-content @click="viewNotification(item)">
                  <v-list-item-title
                    class="small font-weight-bold success--text"
                  >{{item.data[0].notification}}</v-list-item-title>
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
      <v-spacer></v-spacer>
      <v-menu
        v-model="fab_search"
        :close-on-content-click="false"
        nudge-left="200"
        :nudge-top="8"
        transition="slide-x-transition"
        fixed
      >
        <template v-slot:activator="{ on }">
          <v-btn icon v-on="on">
            <v-icon class="text--accent">mdi-magnify</v-icon>
          </v-btn>
        </template>
        <v-text-field
          v-model="search"
          @click="searchMode"
          @keyup.enter="searchit"
          hide-no-data
          hide-details
          prepend-inner-icon="mdi-magnify"
          label="Search..."
          solo
          dense
          rounded
        ></v-text-field>
      </v-menu>

      <v-app-bar-nav-icon class="white--text mr-3" @click.stop="drawerRight = !drawerRight">
        <v-badge
          :value="getAllNotifications.length"
          color="red"
          :content="getAllNotifications.length"
          overlap
        >
          <v-icon class="text--accent">mdi-bell</v-icon>
        </v-badge>
      </v-app-bar-nav-icon>
    </v-app-bar>

    <!-- Sizes your content based upon application components -->
    <v-content>
      <!-- If using vue-router -->
      <router-view></router-view>
    </v-content>

    <!-- CHAT BOX SECTION -->
    <v-menu
      min-width="350"
      v-model="chat"
      top
      :close-on-click="false"
      :close-on-content-click="false"
      :nudge-top="66"
      transition="slide-y-reverse-transition"
      offset-x
    >
      <template v-slot:activator="{ on }">
        <v-fab-transition>
          <v-btn dark fixed bottom right fab v-on="on" @click="openChatBox" color="green">
            <v-badge
              :value="getAllUnreadChatCount"
              color="red"
              :content="getAllUnreadChatCount"
              overlap
            >
              <v-icon>mdi-chat</v-icon>
            </v-badge>
          </v-btn>
        </v-fab-transition>
      </template>

      <!-- Conversation List -->
      <v-card v-if="!direct_chat">
        <v-toolbar dense dark color="primary">
          <v-toolbar-title>Sfaws Chat</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon @click="chat = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-toolbar>

        <v-card flat height="410">
          <v-tabs v-model="tab_chat" fixed-tabs centered hide-slider>
            <v-tab @click="openChatBox">
              Chats
              <v-icon>mdi-wechat</v-icon>
            </v-tab>
            <v-tab @click="fetchFollowingUsers">
              People
              <v-icon>mdi-account-multiple</v-icon>
            </v-tab>
          </v-tabs>

          <v-tabs-items v-model="tab_chat">
            <v-tab-item>
              <v-card flat>
                <!-- LOADER LIST -->
                <v-row v-if="!loaded_convo" justify="center">
                  <v-overlay :opacity="0.1" :value="!loaded_convo">
                    <v-progress-circular :size="64" :width="7" color="green" indeterminate></v-progress-circular>
                  </v-overlay>
                </v-row>
                <vue-custom-scrollbar class="scroll-area" settings="30">
                  <v-list dense>
                    <v-list-item-group v-for="item in getAllConversations" :key="item.id">
                      <v-list-item
                        v-show="user.messageable.id !== getCurrentUser.id"
                        v-for="user in item.conversation.participants"
                        :key="user.id"
                        @click="openChat(user.messageable)"
                      >
                        <v-list-item-avatar size="36">
                          <v-badge
                            :value="user.messageable.status"
                            color="green lighten-1"
                            overlap
                            bottom
                            bordered
                            dot
                            offset-x="10"
                          >
                            <img
                              :src="'storage/profile_photo/' + user.messageable.profile.photo"
                              alt
                            />
                          </v-badge>
                        </v-list-item-avatar>
                        <v-list-item-content>
                          <v-list-item-title
                            :class="item.conversation.last_message.is_seen ? '' : 'success--text font-weight-bold'"
                            v-text="user.messageable.name"
                          ></v-list-item-title>
                          <v-list-item-subtitle
                            class="caption"
                            :class="item.conversation.last_message.is_seen ? '' : 'success--text font-weight-bold'"
                          >
                            <span
                              v-show="item.conversation.last_message.is_sender"
                              class="mr-1"
                            >You:</span>
                            {{limitStr(item.conversation.last_message.body)}}
                          </v-list-item-subtitle>
                          <v-list-item-subtitle class="caption">
                            <span
                              class="small"
                            >{{item.conversation.last_message.created_at | dateAtTime}}</span>
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list-item-group>
                  </v-list>
                </vue-custom-scrollbar>
              </v-card>
            </v-tab-item>

            <v-tab-item>
              <v-card flat>
                <vue-custom-scrollbar class="scroll-area" settings="30">
                  <v-list dense>
                    <v-list-item-group>
                      <v-list-item
                        @click="openChat(user)"
                        v-for="user in allFollowingUsers"
                        :key="user.id"
                      >
                        <v-badge
                          :value="user.status"
                          color="green lighten-1"
                          overlap
                          bottom
                          bordered
                          dot
                          offset-x="10"
                        >
                          <v-avatar size="36">
                            <img :src="'storage/profile_photo/' + user.profile.photo" alt />
                          </v-avatar>
                        </v-badge>
                        <v-list-item-content>
                          <v-list-item-title v-text="user.name"></v-list-item-title>
                          <v-list-item-subtitle v-text="user.type" class="overline"></v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list-item-group>
                  </v-list>
                </vue-custom-scrollbar>
              </v-card>
            </v-tab-item>
          </v-tabs-items>
        </v-card>
      </v-card>

      <!-- Direct Chat -->
      <v-card v-else>
        <v-toolbar dense dark color="primary">
          <v-avatar size="40" class="mr-2">
            <v-img :src="'storage/profile_photo/' + chatForm.user.profile.photo"></v-img>
          </v-avatar>
          <v-toolbar-title class="subtitle-1 font-weight-bold">{{chatForm.user.name}}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon @click="closeChat">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-toolbar>

        <v-card max-height="420" flat tile class="direct-chat direct-chat-success">
          <!-- LOADER CHAT -->
          <v-row v-if="!loaded_chat" justify="center">
            <v-overlay :opacity="0.1" :value="!loaded_chat">
              <v-progress-circular :size="64" :width="7" color="green" indeterminate></v-progress-circular>
            </v-overlay>
          </v-row>
          <vue-custom-scrollbar
            id="chat_box"
            class="direct-chat-messages scroll-message"
            settings="30"
          >
            <div
              class="mb-4"
              :class="!message.is_sender ? 'direct-chat-msg right ml-5' : 'direct-chat-msg mr-5'"
              v-for="(message, index) in getConversation"
              :key="index"
            >
              <v-avatar size="35" class="direct-chat-img">
                <v-img :src="'storage/profile_photo/' + message.sender.profile.photo"></v-img>
              </v-avatar>
              <div class="direct-chat-text body-1">{{message.body}}</div>
              <div class="caption">
                <span class="direct-chat-timestamp small mr-3">{{message.created_at | dateAtTime}}</span>
                <span
                  v-if="index == getConversation.length - 1"
                  v-show="!message.is_sender && message.is_seen"
                  class="font-weight-bold"
                >Seen</span>
              </div>
            </div>

            <v-progress-circular
              v-if="loading_new_message"
              class="float-right"
              :width="3"
              size="28"
              indeterminate
              color="green"
            ></v-progress-circular>
          </vue-custom-scrollbar>
        </v-card>

        <!-- CHAT INPUT FIELD -->
        <v-card-actions>
          <v-text-field
            ref="chat_field"
            @keyup.enter="prepareSending"
            @click:append-outer="prepareSending"
            id="chat_input"
            label="Message"
            append-outer-icon="mdi-send"
            rounded
            solo
            dense
            required
          ></v-text-field>
        </v-card-actions>
      </v-card>
    </v-menu>

    <v-overlay :value="overlay">
      <v-progress-circular indeterminate color="green" :size="100" :width="15"></v-progress-circular>
    </v-overlay>

    <v-footer dark app>
      <span>&copy; 2020</span>
    </v-footer>
  </v-app>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  props: {
    source: String
  },
  data: () => ({
    direct_chat: false,
    fab: false,
    fab_search: false,
    chat: false,
    show_search: false,
    // Loaders
    loaded: false,
    loaded_convo: false,
    loaded_chat: false,
    loading_new_message: false,
    overlay: false,

    search: "",
    tab: null,
    tab_chat: null,
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
    }),

    chatForm: new Form({
      user: "",
      body: ""
    })
  }),

  computed: mapGetters([
    "getAllNotifications",
    "getCurrentUser",
    "allFollowingUsers",
    "getConversation",
    "getAllConversations",
    "getAllUnreadChatCount"
  ]),

  methods: {
    ...mapActions([
      "fetchAllNotifications",
      "fetchCurrentUser",
      "fetchFollowingUsers",
      "fetchMessage",
      "fetchViewPost",
      "addChatMessage",
      "fetchAllConversations",
      "fetchConversation",
      "resetConversation",
      "fetchAllUnreadChatCount",
      "markReadNotification"
    ]),

    openChatBox() {
      this.loaded_convo = false;
      this.fetchAllConversations().then(() => {
        this.loaded_convo = true;
      });
      this.direct_chat = false;
      this.loaded_chat = false;
    },

    closeChat() {
      this.openChatBox();
      this.resetConversation();
      // Reload Unread Messages Count
      this.fetchAllUnreadChatCount();
    },

    scrollChat() {
      var container = document.getElementById("chat_box");
      if (typeof container != "undefined" && container != null) {
        container.scrollTop = container.scrollHeight;
      }
    },

    openChat(user) {
      this.chatForm.user = user;
      this.direct_chat = true;
      this.fetchConversation(user).then(() => {
        this.loaded_chat = true;
        this.scrollChat();
        // Reload Unread Messages Count
        this.fetchAllUnreadChatCount();
      });
    },

    sendMessage() {
      setInterval(() => {
        this.scrollChat();
      }, 500);

      this.$refs.chat_field.reset();
      this.addChatMessage(this.chatForm).then(() => {
        this.loading_new_message = false;
        this.chatForm.body = "";
      });
    },

    prepareSending(e) {
      this.loading_new_message = true;
      const body = e.target.value;
      this.chatForm.body = body;
      this.sendMessage();
    },

    viewNotification(item) {
      if (item.data[0].type === "message") {
        this.overlay = true;
        this.fetchMessage(item.data[0].data.id).then(() => {
          this.overlay = false;
          this.$router.push("/view_message");

          this.markReadNotification(item).then(() => {
            this.fetchAllNotifications();
          });
        });
      } else if (item.data[0].type === "comment") {
        this.overlay = true;
        this.fetchViewPost(item.data[0].data.id).then(() => {
          this.overlay = false;
          this.$router.push("/view_post");

          this.markReadNotification(item).then(() => {
            this.fetchAllNotifications();
          });
        });
      }
      //   this.fetchMessage(item.message_id).then(() => {
      //     this.$router.push("/view-message");
      //   });
    },

    limitStr(text) {
      let str = text;
      let limit = 20;
      if (typeof str === "string" && str.length > limit) {
        str = str.slice(0, limit) + "...";
      }
      return str;
    },

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
    this.fetchAllUnreadChatCount();
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
