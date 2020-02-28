<style scoped>
.comment-section {
  display: none;
}
</style>

<template>
  <v-container>
    <v-tabs show-arrows centered v-model="tab">
      <v-tabs-slider></v-tabs-slider>
      <v-tab>Overview</v-tab>
      <v-tab>Post ({{posts.length}})</v-tab>
      <v-tab>Videos</v-tab>
      <v-tab>Achievements</v-tab>
    </v-tabs>
    <v-tabs-items v-model="tab">
      <v-tab-item>
        <v-container>
          <v-row style="background-image: linear-gradient(#00ACC1, #00838F, #006064)">
            <v-col cols="12" md="3" class="text-center">
              <v-avatar color="indigo" size="164">
                <v-img
                  v-if="getUser.profile.photo"
                  :src="'/storage/profile_photo/' + getUser.profile.photo"
                  class="img-bordered border-light"
                >
                  <template v-slot:placeholder>
                    <v-row class="fill-height ma-0" align="center" justify="center">
                      <v-progress-circular indeterminate color="success lighten-5"></v-progress-circular>
                    </v-row>
                  </template>
                </v-img>
              </v-avatar>
            </v-col>

            <v-col cols="12" md="3">
              <v-card dark flat shaped style="background-color: #10383738">
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>
                      <span class="font-weight-bold headline">{{getUser.name}}</span>
                      <div class="overline">{{getUser.type}}</div>
                    </v-list-item-title>
                    <v-list-item-title>
                      <p class="small">Member since {{getUser.created_at | sinceDate}}</p>
                    </v-list-item-title>
                    <v-list-item-title v-show="!getUser.current_user">
                      <v-btn
                        @click="toggleFollow(user.id)"
                        :class="getUser.isFollowing ? 'success' : 'secondary'"
                        fab
                        text
                        small
                      >
                        <i
                          style="font-size: 20px"
                          :class="getUser.isFollowing ? 'fas fa-check' : 'fas fa-user-plus'"
                        ></i>
                      </v-btn>
                      <v-btn fab small color="success" class="shadow-none">
                        <i style="font-size: 20px" class="fas fa-comment"></i>
                      </v-btn>
                    </v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-col>

            <v-col cols="12" md="6">
              <v-row no-gutters class="justify-content-center">
                <v-col cols="4" md="4" class="pr-2">
                  <v-card dark flat shaped style="background-color: #10383738" class="text-center">
                    <v-avatar align="center" size="64" tile>
                      <v-icon size="50">mdi-account-multiple</v-icon>
                    </v-avatar>
                    <v-list-item class="text-center">
                      <v-list-item-content>
                        <v-list-item-title class="font-weight-bold headline">{{getUser.followers}}</v-list-item-title>
                        <p class="small">Followers</p>
                      </v-list-item-content>
                    </v-list-item>
                  </v-card>
                </v-col>

                <v-col cols="4" md="4" class="pr-2">
                  <v-card dark flat shaped style="background-color: #10383738" class="text-center">
                    <v-avatar class="mx-auto" size="64" tile>
                      <v-icon size="50">mdi-account-box-multiple</v-icon>
                    </v-avatar>
                    <v-list-item class="text-center">
                      <v-list-item-content>
                        <v-list-item-title class="font-weight-bold headline">{{getUser.followings}}</v-list-item-title>
                        <p class="small">Followings</p>
                      </v-list-item-content>
                    </v-list-item>
                  </v-card>
                </v-col>

                <v-col cols="4" md="4">
                  <v-card dark flat shaped style="background-color: #10383738" class="text-center">
                    <v-avatar class="mx-auto" size="64" tile>
                      <v-icon size="50">mdi-trophy</v-icon>
                    </v-avatar>
                    <v-list-item class="text-center">
                      <v-list-item-content>
                        <v-list-item-title class="font-weight-bold headline">243</v-list-item-title>
                        <p class="small">Experience</p>
                      </v-list-item-content>
                    </v-list-item>
                  </v-card>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-container>
      </v-tab-item>
      <v-tab-item>
        <v-card flat tile class="mt-4" v-show="posts.length == 0">
          <v-card-text class="text-center">
            <v-divider></v-divider>
            <p class="lead">No available post.</p>
            <v-divider></v-divider>
          </v-card-text>
        </v-card>
        <v-card v-for="post in posts" :key="post.id" class="mt-4">
          <v-list-item>
            <v-list-item-avatar size="50">
              <v-img
                class="img-circle img-bordered border-light"
                :src="'/storage/profile_photo/' + post.author.profile.photo"
                alt="Profile Photo"
              >
                <template v-slot:placeholder>
                  <v-row class="fill-height ma-0" align="center" justify="center">
                    <v-progress-circular indeterminate color="success lighten-5"></v-progress-circular>
                  </v-row>
                </template>
              </v-img>
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title class="headline">{{post.title}}</v-list-item-title>
              <v-list-item-subtitle>
                by {{post.author.name}}
                <span class="small ml-2">({{post.author.type}})</span>
                <span class="float-right">Posted {{post.created_at | sinceDate}}</span>
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          <v-container>
            <v-row>
              <v-col v-if="post.cover_image" sm="6">
                <v-img :src="'/storage/cover_photo/' + post.cover_image" height="250"></v-img>
              </v-col>
              <v-col>
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-action>
                      <v-badge color="green">
                        <template v-slot:badge>{{post.commends}}</template>
                        <v-chip color="primary" small>Commends</v-chip>
                      </v-badge>
                    </v-list-item-action>
                    <v-list-item-title class="mt-2">{{post.content}}</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-col>
            </v-row>
            <p>
              <a class="link-black text-sm mr-2">
                <i class="fas fa-leaf mr-1"></i>Spread
              </a>
              <a
                v-if="post.commend"
                @click="toggleCommend(post.id)"
                class="link-black text-sm mr-2 primary--text"
              >
                <strong>
                  <i class="fas fa-check mr-1"></i>Commended
                </strong>
              </a>
              <a
                v-else
                @click="toggleCommend(post.id)"
                class="link-black text-sm mr-2 primary--text"
              >Commend</a>
              <a
                v-show="post.authorize"
                @click="dialog = true; postForm.fill(post)"
                class="link-black text-sm mr-2"
              >Edit</a>

              <v-dialog v-model="dialog" width="600">
                <v-card tile>
                  <v-toolbar dark color="primary">
                    <v-toolbar-title>Edit Post</v-toolbar-title>
                    <div class="flex-grow-1"></div>
                    <v-toolbar-items>
                      <v-btn dark text @click="dialog = false">
                        <v-icon>mdi-close</v-icon>Cancel
                      </v-btn>
                    </v-toolbar-items>
                  </v-toolbar>
                  <v-card-text class="mt-3">
                    <form>
                      <div class="d-flex justify">
                        <v-text-field v-model="postForm.title" label="Title"></v-text-field>
                        <v-combobox
                          v-model="tags"
                          chips
                          clearable
                          label="Add Tags"
                          multiple
                          solo
                          class="ml-5 w-50"
                          tag
                        >
                          <template v-slot:selection="{ attrs, item, select, selected }">
                            <v-chip
                              v-bind="attrs"
                              :input-value="selected"
                              close
                              @click="select"
                              @click:close="remove(item)"
                            >
                              <strong>{{ item }}</strong>
                            </v-chip>
                          </template>
                        </v-combobox>
                      </div>
                      <v-textarea
                        v-model="postForm.content"
                        class="mb-0"
                        label="Spread your thoughts!"
                      ></v-textarea>
                      <v-chip small @click="browseImage">
                        <v-avatar left>
                          <v-icon>mdi-image</v-icon>
                        </v-avatar>Photos
                      </v-chip>
                      <v-chip small>
                        <v-avatar left>
                          <v-icon>mdi-video</v-icon>
                        </v-avatar>Videos
                      </v-chip>
                      <v-chip small>
                        <v-avatar left>
                          <v-icon>mdi-map-marker</v-icon>
                        </v-avatar>Location
                      </v-chip>
                      <input
                        @change="coverImage"
                        id="cover_photo"
                        type="file"
                        hidden
                        accept="image/*"
                      />
                      <v-btn @click="updatePost" absolute outlined right>Update</v-btn>
                    </form>
                  </v-card-text>
                  <div style="flex: 1 1 auto;"></div>
                </v-card>
              </v-dialog>

              <a
                v-show="post.authorize"
                @click="deletePost(post.id)"
                class="link-black text-sm text-danger"
              >Delete</a>
              <span class="float-right">
                <a @click="toggleComment(post.id)" class="link-black text-sm">
                  <i class="fas fa-comments mr-1"></i>
                  <span>Comments</span>
                  ({{post.comments.length}})
                </a>
              </span>
            </p>
            <div class="comment-section" :id="'CS-' + post.id">
              <div
                class="card border-0 shadow-none bg-transparent"
                v-for="comment in post.comments"
                :key="comment.id"
              >
                <v-divider></v-divider>
                <div class="media ma-3 mb-0">
                  <v-avatar size="45">
                    <v-img
                      class="img-circle img-bordered border-success"
                      :src="'/storage/profile_photo/' + comment.author.profile.photo"
                      alt="Message User Image"
                    ></v-img>
                  </v-avatar>
                  <v-card-text>
                    <p class="mb-0">
                      <strong>{{comment.author.name}}</strong>
                      <span class="small float-right">{{comment.created_at | sinceDate}}</span>
                    </p>
                    <span class="mt-0" style="font-size: 15px">{{comment.content}}</span>
                  </v-card-text>
                </div>
                <div class="text-left mt-0" style="margin-left: 65px">
                  <button
                    v-if="comment.agree"
                    @click="agreeComment(comment.id)"
                    class="small mr-3"
                    style="color: teal"
                  >
                    <strong>
                      <i class="fas fa-check"></i> Agreed
                    </strong>
                  </button>
                  <button v-else @click="agreeComment(comment.id)" class="small mr-3">
                    <strong>Agree</strong>
                  </button>
                  <button
                    v-show="comment.authorize"
                    @click="deleteComment(comment.id)"
                    class="small mr-3"
                  >Delete</button>
                </div>
              </div>
            </div>
            <v-form :id="'F-' + post.id" @submit.prevent="createComment(post.id)">
              <div class="input-group">
                <input
                  type="text"
                  :id="'C-'+ post.id"
                  placeholder="Comment here..."
                  class="form-control"
                />
                <span class="input-group-append">
                  <button
                    type="submit"
                    @submit.prevent="createComment(post.id)"
                    class="btn btn-success"
                  >Send</button>
                </span>
              </div>
            </v-form>
          </v-container>
        </v-card>
      </v-tab-item>
      <v-tab-item></v-tab-item>
      <v-tab-item></v-tab-item>
      <v-tab-item></v-tab-item>
    </v-tabs-items>
  </v-container>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  mounted() {},

  watch: {
    dialog(val) {
      !val && this.postForm.reset();
    }
  },

  created() {
    this.loadPosts();
  },

  computed: {
    ...mapGetters(["getUser"])
  },

  data() {
    return {
      isActive: false,
      dialog: false,
      tags: "",
      tab: null,
      posts: {},

      postForm: new Form({
        id: "",
        rating: null,
        title: "",
        content: "",
        cover_image: "",
        tags: ""
      }),

      comment: new Form({
        id: "",
        post_id: "",
        user_id: "",
        body: ""
      })
    };
  },

  methods: {
    agreeComment(comment_id) {
      axios
        .post("api/agree", {
          comment_id: comment_id
        })
        .then(response => {
          this.loadPosts();
          Toast.fire({
            type: "success",
            title: "Successful!"
          });
        });
    },

    toggleCommend(post_id) {
      axios
        .post("api/commend", {
          post_id: post_id
        })
        .then(response => {
          this.loadPosts();
          Toast.fire({
            type: "success",
            title: "Successful!"
          });
        });
    },

    deleteComment(id) {
      this.comment
        .delete("api/comment/" + id)
        .then(() => {
          Toast.fire({
            type: "success",
            title: "Comment Deleted!"
          });
          this.loadPosts();
        })
        .catch(() => {
          Swal.fire({
            type: "error",
            title: "Oops...",
            text: "Something went wrong!"
          });
        });
    },

    toggleComment(comment_section) {
      $("#CS-" + comment_section).slideToggle();
    },

    //Create Comment
    createComment(post_id) {
      let comment = document.querySelector("#C-" + post_id).value;
      console.log(comment);
      axios
        .post("/api/comment", {
          post_id: post_id,
          content: comment
        })
        .then(() => {
          this.loadPosts();
          document.querySelector("#F-" + post_id).reset();
          Toast.fire({
            type: "success",
            title: "Comment posted!"
          });
        })
        .catch(() => {
          Swal.fire({
            type: "error",
            title: "Oops...",
            text: "Something went wrong!"
          });
        });
    },

    updatePost() {
      this.$Progress.start();
      this.postForm
        .put("api/post/" + this.postForm.id)
        .then(() => {
          this.dialog = false;
          Swal.fire({
            position: "top-end",
            type: "success",
            title: "Post Updated!",
            showConfirmButton: false,
            timer: 1500
          });
          this.$Progress.finish();
          this.loadPosts();
          this.postForm.reset();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },

    deletePost(id) {
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
          this.postForm
            .delete("api/post/" + id)
            .then(() => {
              Toast.fire({
                type: "success",
                title: "Post Deleted!"
              });
              this.loadPosts();
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

    remove(item) {
      this.tags.splice(this.tags.indexOf(item), 1);
      this.tags = [...this.tags];
    },

    coverImage(file) {
      let image = file.target.files[0];
      console.log(image);
      let reader = new FileReader();
      reader.onloadend = image => {
        //console.log('RESULT', reader.result)
        this.postForm.cover_image = reader.result;
      };
      reader.readAsDataURL(image);
    },

    browseImage() {
      $("#cover_photo").click();
    },

    loadPosts() {
      axios
        .get("api/userpost/" + this.getUser.id)
        .then(({ data }) => (this.posts = data));
    },

    toggleFollow(id) {
      let reference = $(this);
      axios
        .post("/api/follow", {
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
          this.fetchUser(this.getUser.id);
        });
    }
  }
};
</script>
