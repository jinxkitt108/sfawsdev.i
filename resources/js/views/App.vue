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
      <v-tabs centered v-model="tab" icons-and-text>
        <v-tab>
          <span class="small">Users</span>
          <v-icon>mdi-account-box</v-icon>
        </v-tab>
        <v-tab>
          <span class="small">Messages</span>
          <v-icon>mdi-mail</v-icon>
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tab">
        <v-tab-item>
          <v-list dense>
            <v-list-item-group>
              <v-list-item v-for="user in allFollowingUsers" :key="user.id" @click="userChat(user)">
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
        <v-tab-item></v-tab-item>
      </v-tabs-items>
    </v-navigation-drawer>

    <v-app-bar app clipped-left clipped-right dark color="teal darken-4">
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

    <v-dialog hide-overlay eager max-width="370" transition="slide-y-reverse-transition" v-model="isChatOpen">
      <template v-slot:activator="{ on }">
        <v-btn @click="startChat" dark color="green" fixed bottom right fab>
          <v-icon>mdi-chat</v-icon>
        </v-btn>
      </template>
        <div class="card card-danger direct-chat direct-chat-danger">
          <div class="card-header">
            <h3 class="card-title">Direct Chat</h3>
            <div class="card-tools">
              <span data-toggle="tooltip" title="3 New Messages" class="badge badge-light">3</span>
              <button type="button" class="btn btn-tool" data-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button
                type="button"
                class="btn btn-tool"
                data-toggle="tooltip"
                title="Contacts"
                data-widget="chat-pane-toggle"
              >
                <i class="fas fa-comments"></i>
              </button>
              <button type="button" class="btn btn-tool" data-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <!-- Conversations are loaded here -->
            <div class="direct-chat-messages">
              <!-- Message. Default to the left -->
              <div class="direct-chat-msg">
                <div class="direct-chat-infos clearfix">
                  <span class="direct-chat-name float-left">Alexander Pierce</span>
                  <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                </div>
                <!-- /.direct-chat-infos -->
                <img
                  class="direct-chat-img"
                  src="/docs/3.0/assets/img/user1-128x128.jpg"
                  alt="message user image"
                />
                <!-- /.direct-chat-img -->
                <div class="direct-chat-text">Is this template really for free? That's unbelievable!</div>
                <!-- /.direct-chat-text -->
              </div>
              <!-- /.direct-chat-msg -->
              <!-- Message to the right -->
              <div class="direct-chat-msg right">
                <div class="direct-chat-infos clearfix">
                  <span class="direct-chat-name float-right">Sarah Bullock</span>
                  <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                </div>
                <!-- /.direct-chat-infos -->
                <img
                  class="direct-chat-img"
                  src="/docs/3.0/assets/img/user3-128x128.jpg"
                  alt="message user image"
                />
                <!-- /.direct-chat-img -->
                <div class="direct-chat-text">You better believe it!</div>
                <!-- /.direct-chat-text -->
              </div>
              <!-- /.direct-chat-msg -->
              <!-- Message. Default to the left -->
              <div class="direct-chat-msg">
                <div class="direct-chat-infos clearfix">
                  <span class="direct-chat-name float-left">Alexander Pierce</span>
                  <span class="direct-chat-timestamp float-right">23 Jan 5:37 pm</span>
                </div>
                <!-- /.direct-chat-infos -->
                <img
                  class="direct-chat-img"
                  src="/docs/3.0/assets/img/user1-128x128.jpg"
                  alt="message user image"
                />
                <!-- /.direct-chat-img -->
                <div class="direct-chat-text">Working with AdminLTE on a great new app! Wanna join?</div>
                <!-- /.direct-chat-text -->
              </div>
              <!-- /.direct-chat-msg -->
              <!-- Message to the right -->
              <div class="direct-chat-msg right">
                <div class="direct-chat-infos clearfix">
                  <span class="direct-chat-name float-right">Sarah Bullock</span>
                  <span class="direct-chat-timestamp float-left">23 Jan 6:10 pm</span>
                </div>
                <!-- /.direct-chat-infos -->
                <img
                  class="direct-chat-img"
                  src="/docs/3.0/assets/img/user3-128x128.jpg"
                  alt="message user image"
                />
                <!-- /.direct-chat-img -->
                <div class="direct-chat-text">I would love to.</div>
                <!-- /.direct-chat-text -->
              </div>
              <!-- /.direct-chat-msg -->
            </div>
            <!--/.direct-chat-messages-->
            <!-- Contacts are loaded here -->
            <div class="direct-chat-contacts">
              <ul class="contacts-list">
                <li>
                  <a href="#">
                    <img class="contacts-list-img" src="/docs/3.0/assets/img/user1-128x128.jpg" />
                    <div class="contacts-list-info">
                      <span class="contacts-list-name">
                        Count Dracula
                        <small class="contacts-list-date float-right">2/28/2015</small>
                      </span>
                      <span class="contacts-list-msg">How have you been? I was...</span>
                    </div>
                    <!-- /.contacts-list-info -->
                  </a>
                </li>
                <!-- End Contact Item -->
                <li>
                  <a href="#">
                    <img class="contacts-list-img" src="/docs/3.0/assets/img/user7-128x128.jpg" />
                    <div class="contacts-list-info">
                      <span class="contacts-list-name">
                        Sarah Doe
                        <small class="contacts-list-date float-right">2/23/2015</small>
                      </span>
                      <span class="contacts-list-msg">I will be waiting for...</span>
                    </div>
                    <!-- /.contacts-list-info -->
                  </a>
                </li>
                <!-- End Contact Item -->
                <li>
                  <a href="#">
                    <img class="contacts-list-img" src="/docs/3.0/assets/img/user3-128x128.jpg" />
                    <div class="contacts-list-info">
                      <span class="contacts-list-name">
                        Nadia Jolie
                        <small class="contacts-list-date float-right">2/20/2015</small>
                      </span>
                      <span class="contacts-list-msg">I'll call you back at...</span>
                    </div>
                    <!-- /.contacts-list-info -->
                  </a>
                </li>
                <!-- End Contact Item -->
                <li>
                  <a href="#">
                    <img class="contacts-list-img" src="/docs/3.0/assets/img/user5-128x128.jpg" />
                    <div class="contacts-list-info">
                      <span class="contacts-list-name">
                        Nora S. Vans
                        <small class="contacts-list-date float-right">2/10/2015</small>
                      </span>
                      <span class="contacts-list-msg">Where is your new...</span>
                    </div>
                    <!-- /.contacts-list-info -->
                  </a>
                </li>
                <!-- End Contact Item -->
                <li>
                  <a href="#">
                    <img class="contacts-list-img" src="/docs/3.0/assets/img/user6-128x128.jpg" />
                    <div class="contacts-list-info">
                      <span class="contacts-list-name">
                        John K.
                        <small class="contacts-list-date float-right">1/27/2015</small>
                      </span>
                      <span class="contacts-list-msg">Can I take a look at...</span>
                    </div>
                    <!-- /.contacts-list-info -->
                  </a>
                </li>
                <!-- End Contact Item -->
                <li>
                  <a href="#">
                    <img class="contacts-list-img" src="/docs/3.0/assets/img/user8-128x128.jpg" />
                    <div class="contacts-list-info">
                      <span class="contacts-list-name">
                        Kenneth M.
                        <small class="contacts-list-date float-right">1/4/2015</small>
                      </span>
                      <span class="contacts-list-msg">Never mind I found...</span>
                    </div>
                    <!-- /.contacts-list-info -->
                  </a>
                </li>
                <!-- End Contact Item -->
              </ul>
              <!-- /.contacts-list -->
            </div>
            <!-- /.direct-chat-pane -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <form action="#" method="post">
              <div class="input-group">
                <input type="text" name="message" placeholder="Type Message ..." class="form-control" />
                <span class="input-group-append">
                  <button type="button" class="btn btn-primary">Send</button>
                </span>
              </div>
            </form>
          </div>
          <!-- /.card-footer-->
        </div>
    </v-dialog>

    <v-footer app>
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
      }
    })
  }),

  computed: mapGetters([
    "allParticipants",
    "allFollowingUsers",
    "userAvatar",
    "userName"
  ]),

  methods: {
    ...mapActions(["fetchMessages", "fetchFollowingUsers", "directChat"]),
    
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
    this.fetchMessages();
    this.fetchFollowingUsers();
  },
  mounted() {
    axios.get("api/profile").then(({ data }) => {
      this.user.fill(data);
      this.$vuetify.theme.dark = this.user.profile.theme;
    });
  }
};
</script>