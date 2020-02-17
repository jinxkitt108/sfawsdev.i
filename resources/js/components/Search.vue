<template>
  <v-container fluid>
    <v-card tile flat v-if="searchMode">
      <v-tabs v-model="tab" show-arrows center-active background-color="transparent" fixed-tabs>
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
            <v-card v-if="users.data.length" flat width="700" class="mx-auto">
              <v-card-title class="font-weight-bold">({{users.data.length}}) People</v-card-title>
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
            <v-card v-else flat>
              <v-divider></v-divider>
              <div class="text-center">
                <p class="text-muted">No match found.</p>
              </div>
              <v-divider></v-divider>
            </v-card>
          </v-tab-item>
          <v-tab-item>
            <v-card v-if="posts.data.length" flat width="700" class="mx-auto">
              <v-card-title class="font-weight-bold">({{posts.data.length}}) Posts</v-card-title>
              <div v-for="post in posts.data" :key="post.id">
                <v-card flat class="pt-3 pb-3 border-bottom" tile v-ripple>
                  <v-list-item>
                    <router-link
                      v-if="post.cover_image"
                      :to="'/viewpost/' + post.id"
                      class="text-decoration-none"
                    >
                      <v-list-item-avatar tile size="120" color="grey">
                        <v-img :src="'storage/cover_photo/' + post.cover_image">
                          <template v-slot:placeholder>
                            <v-row class="fill-height ma-0" align="center" justify="center">
                              <v-progress-circular indeterminate color="success lighten-5"></v-progress-circular>
                            </v-row>
                          </template>
                        </v-img>
                      </v-list-item-avatar>
                    </router-link>
                    <router-link :to="'/viewpost/' + post.id" class="text-decoration-none">
                      <v-list-item-content>
                        <v-list-item-title class="headline">{{post.title}}</v-list-item-title>
                        <v-list-item-subtitle>
                          by
                          <router-link :to="'/viewuser/' + post.author.id">
                            {{post.author.name}}
                            <span class="small ml-2">({{post.author.type}})</span>
                          </router-link>
                          <p
                            class="small font-weight-bold mt-1"
                          >Posted {{post.created_at | sinceDate}}</p>
                          <v-chip color="primary" small class="mt-1">
                            Commends
                            <span class="font-weight-bold ml-3">{{post.commends}}</span>
                          </v-chip>
                        </v-list-item-subtitle>
                      </v-list-item-content>
                    </router-link>
                  </v-list-item>
                </v-card>
              </div>
            </v-card>
            <v-card v-else flat>
              <v-divider></v-divider>
              <div class="text-center">
                <p class="text-muted">No match found.</p>
              </div>
              <v-divider></v-divider>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-tabs>
    </v-card>

    <v-card flat v-else>
      <v-card-title class="subtitle-1 font-weight-bold">Recommended for you</v-card-title>
      <swiper :options="swiperOption" ref="mySwiper" class="pa-2">
        <swiper-slide v-for="item in getExpertUsers.data" :key="item.id">
          <v-card>
            <div class="text-center">
              <v-avatar size="130">
                <v-img :src="'storage/profile_photo/' + item.profile.photo">
                  <template v-slot:placeholder>
                    <v-row class="fill-height ma-0" align="center" justify="center">
                      <v-progress-circular indeterminate color="success lighten-5"></v-progress-circular>
                    </v-row>
                  </template>
                </v-img>
              </v-avatar>
            </div>
            <v-list-item>
              <v-list-item-content>
                <router-link :to="'/viewuser/' + item.id" class="text-decoration-none">
                  <v-list-item-title>
                    <span class="subtitle-1 font-weight-bold">{{item.name}}</span>
                    <div class="overline">{{item.type}}</div>
                  </v-list-item-title>
                </router-link>
                <v-list-item-title class="caption">
                  Followers
                  <span class="mr-1">{{item.followers}}</span>
                </v-list-item-title>
                <v-list-item-title class="caption">Followings {{item.followings}}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-card-actions>
              <v-btn
                @click="toggleFollow(item.id)"
                :class="item.isFollowing ? 'success' : 'secondary'"
                fab
                text
                small
              >
                <i
                  style="font-size: 20px"
                  :class="item.isFollowing ? 'fas fa-check' : 'fas fa-user-plus'"
                ></i>
              </v-btn>
              <v-btn fab small color="success" class="shadow-none">
                <i style="font-size: 20px" class="fas fa-comment"></i>
              </v-btn>
            </v-card-actions>
          </v-card>
        </swiper-slide>
      </swiper>

      <!-- <v-data-iterator :items="getExpertUsers.data" :items-per-page="4">
        <template v-slot:header>
          <v-toolbar flat>
            <v-toolbar-title class="subtitle-1 font-weight-black">Recommended for you</v-toolbar-title>
          </v-toolbar>
        </template>

        <template v-slot:default="props">
          <div class="d-flex">
            <v-card max-width="200" v-for="item in props.items" :key="item.id" class="mr-2">
              <div class="text-center mt-2">
                <v-avatar size="150">
                  <v-img :src="'storage/profile_photo/' + item.profile.photo">
                    <template v-slot:placeholder>
                      <v-row class="fill-height ma-0" align="center" justify="center">
                        <v-progress-circular indeterminate color="success lighten-5"></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </v-avatar>
              </div>
              <v-list-item>
                <v-list-item-content>
                  <router-link :to="'/viewuser/' + item.id" class="text-decoration-none">
                    <v-list-item-title>
                      <span class="subtitle-1 font-weight-bold">{{item.name}}</span>
                      <div class="overline">{{item.type}}</div>
                    </v-list-item-title>
                  </router-link>
                  <v-list-item-title class="caption">
                    Followers
                    <span class="mr-1">{{item.followers}}</span>
                  </v-list-item-title>
                  <v-list-item-title class="caption">Followings {{item.followings}}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <v-card-actions>
                <v-btn
                  @click="toggleFollow(item.id)"
                  :class="item.isFollowing ? 'success' : 'secondary'"
                  fab
                  text
                  small
                >
                  <i
                    style="font-size: 20px"
                    :class="item.isFollowing ? 'fas fa-check' : 'fas fa-user-plus'"
                  ></i>
                </v-btn>
                <v-btn fab small color="success" class="shadow-none">
                  <i style="font-size: 20px" class="fas fa-comment"></i>
                </v-btn>
              </v-card-actions>
            </v-card>
          </div>
        </template>
      </v-data-iterator>-->
    </v-card>
  </v-container>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  mounted() {
    console.log("Search component mounted.");
    this.swiper.slideTo(3, 1000, false);
  },

  data() {
    return {
      swiperOption: {
        slidesPerView: 4,
        spaceBetween: 20,
        freeMode: true,
        grabCursor: true,
        breakpoints: {
          1024: {
            slidesPerView: 4,
            spaceBetween: 40
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 30
          },
          640: {
            slidesPerView: 2,
            spaceBetween: 20
          },
          320: {
            slidesPerView: 1,
            spaceBetween: 10
          }
        }
      },
      tab: null,
      toggleIcon: false,
      followIcon: "",
      users: {},
      posts: {},
      products: {},
      stores: {},
      searchMode: false
    };
  },

  computed: {
    ...mapGetters(["getExpertUsers"]),

    swiper() {
      return this.$refs.mySwiper.swiper;
    }
  },

  created() {
    Fire.$on("searching", query => {
      this.searchMode = true;
      //USERS
      axios
        .get("api/searchUser?q=" + query)
        .then(data => {
          this.users = data.data;
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

    this.fetchExpertUsers();
  },
  methods: {
    ...mapActions(["fetchFollowingUsers", "fetchExpertUsers"]),

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
          this.fetchFollowingUsers();
          this.fetchExpertUsers();
        });
    }
  }
};
</script>
