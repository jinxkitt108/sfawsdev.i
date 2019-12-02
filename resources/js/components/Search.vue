<template>
  <v-container fluid>
    <v-card tile flat>
      <v-tabs
        v-if="searchMode"
        v-model="tab"
        show-arrows
        center-active
        background-color="transparent"
        fixed-tabs
      >
        <v-tab>
          <v-chip class="ma-3 pa-4" pill>
            <v-avatar left>
              <v-icon>mdi-account-group</v-icon>
            </v-avatar>People
          </v-chip>
        </v-tab>
        <v-tab>
          <v-chip class="ma-3 pa-4" pill>
            <v-avatar left>
              <v-icon>mdi-post</v-icon>
            </v-avatar>Post
          </v-chip>
        </v-tab>
        <v-tab>
          <v-chip class="ma-3 pa-4" pill>
            <v-avatar left>
              <v-icon>mdi-storefront</v-icon>
            </v-avatar>Stores
          </v-chip>
        </v-tab>
        <v-tab>
          <v-chip class="ma-3 pa-4" pill>
            <v-avatar left>
              <v-icon>mdi-leaf</v-icon>
            </v-avatar>Products
          </v-chip>
        </v-tab>
        <v-tab>
          <v-chip class="ma-3 pa-4" pill>
            <v-avatar left>
              <v-icon>mdi-video</v-icon>
            </v-avatar>Videos
          </v-chip>
        </v-tab>
        <v-tabs-items class="mt-3" v-model="tab">
          <v-tab-item>
            <v-card flat width="700" class="mx-auto">
              <v-card-title class="font-weight-bold">People</v-card-title>
              <div v-for="user in users.data" :key="user.id">
                <v-card flat class="pt-3 pb-3 border-bottom" tile v-ripple>
                  <v-list-item>
                    <router-link :to="'/viewuser/' + user.id" class="text-decoration-none">
                      <v-list-item-avatar tile size="120" color="grey">
                        <v-img :src="'storage/profile_photo/' + user.profile.photo">
                          <template v-slot:placeholder>
                            <v-row class="fill-height ma-0" align="center" justify="center">
                              <v-progress-circular indeterminate color="success lighten-5"></v-progress-circular>
                            </v-row>
                          </template>
                        </v-img>
                      </v-list-item-avatar>
                    </router-link>
                    <router-link :to="'/viewuser/' + user.id" class="text-decoration-none">
                      <v-list-item-content>
                        <v-list-item-title class="font-weight-bold">
                          {{user.name}}
                          <div class="overline">{{user.type}}</div>
                        </v-list-item-title>
                        <v-list-item-subtitle class="caption">Followers {{user.followers}}</v-list-item-subtitle>
                        <v-list-item-subtitle class="caption">Followings {{user.followings}}</v-list-item-subtitle>
                      </v-list-item-content>
                    </router-link>
                    <v-spacer></v-spacer>
                    <v-card-actions v-show="!user.current_user">
                      <v-btn @click="toggleFollow(user.id)" color="success" text icon small>
                        <i
                          style="font-size: 23px"
                          :id="'followIcon-' + user.id"
                          :class="user.isFollowing ? 'fas fa-check' : 'fas fa-user-plus'"
                        ></i>
                      </v-btn>
                    </v-card-actions>
                  </v-list-item>
                </v-card>
              </div>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-tabs>
      <v-card flat v-else>
        <v-card-title class="font-weight-bold">Recommended</v-card-title>
        <v-card-text>
          <carousel :per-page="3">
            <slide v-for="expert in experts" :key="expert.id" class="pa-2">
              <v-card link tile max-width="200">
                <router-link :to="'/viewuser/' + expert.id" class="text-decoration-none">
                  <v-img aspect-ratio="1" :src="'storage/profile_photo/' + expert.profile.photo">
                    <template v-slot:placeholder>
                      <v-row class="fill-height ma-0" align="center" justify="center">
                        <v-progress-circular indeterminate color="success lighten-5"></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </router-link>
                <v-list-item>
                  <v-list-item-content>
                    <router-link :to="'/viewuser/' + expert.id" class="text-decoration-none">
                      <v-list-item-title>
                        <span class="font-weight-bold">{{expert.name}}</span>
                        <div class="overline">{{expert.type}}</div>
                      </v-list-item-title>
                    </router-link>
                    <v-list-item-title class="caption">
                      Followers
                      <span class="mr-1">{{expert.followers}}</span>
                    </v-list-item-title>
                    <v-list-item-title class="caption">Followings {{expert.followings}}</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
                <v-card-actions>
                  <v-btn
                    @click="toggleFollow(expert.id)"
                    :class="expert.isFollowing ? 'success' : 'secondary'"
                    fab
                    text
                    small
                  >
                    <i
                      style="font-size: 20px"
                      :class="expert.isFollowing ? 'fas fa-check' : 'fas fa-user-plus'"
                    ></i>
                  </v-btn>
                  <v-btn fab small color="success" class="shadow-none">
                    <i style="font-size: 20px" class="fas fa-comment"></i>
                  </v-btn>
                </v-card-actions>
              </v-card>
            </slide>
          </carousel>
        </v-card-text>
      </v-card>
    </v-card>
  </v-container>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
  data() {
    return {
      tab: null,
      toggleIcon: false,
      followIcon: "",
      experts: "",
      users: "",
      posts: {},
      products: {},
      stores: {},
      searchMode: false
    };
  },
  created() {
    Fire.$on("searchMode", query => {
      this.searchMode = false;
    });
    Fire.$on("searching", query => {
      this.searchMode = true;
      //USERS
      axios
        .get("api/searchUser?q=" + query)
        .then(data => {
          this.$store.commit("search", data.data);
          this.users = this.$store.getters.users;
        })
        .catch(() => {});
      //POST
      axios
        .get("api/searchPost?q=" + query)
        .then(data => {
          this.posts = data.data;
        })
        .catch(() => {});
      //STORES
      axios
        .get("api/searchStore?q=" + query)
        .then(data => {
          this.stores = data.data;
        })
        .catch(() => {});
    });
    this.loadExperts();
  },
  methods: {
    loadExperts() {
      axios.get("api/experts").then(({ data }) => (this.experts = data.data));
    },
    toggleFollow(id) {
      let reference = $(this);
      axios
        .post("api/follow", {
          user_id: id
        })
        .then(response => {
          if (response.data.attached.length) {
            $("#followIcon-" + id).replaceWith(
              "<i style='font-size: 23px' class='fas fa-check' id='followIcon-" +
                id +
                "'></i>"
            );
            Toast.fire({
              type: "success",
              title: "You are now following user!"
            });
          } else {
            $("#followIcon-" + id).replaceWith(
              "<i style='font-size: 23px' class='fas fa-user-plus' id='followIcon-" +
                id +
                "'></i>"
            );
            Toast.fire({
              type: "warning",
              title: "You unfollowed user!"
            });
          }
          this.loadExperts();
        });
    }
  }
};
</script>
