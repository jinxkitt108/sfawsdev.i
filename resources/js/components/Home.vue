<style scoped>
.comment-section {
  display: none;
}
</style>
<template>
  <v-container fluid>
    <v-form>
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
              @submit:close="remove(item)"
            >
              <strong>{{ item }}</strong>
            </v-chip>
          </template>
        </v-combobox>
      </div>
      <v-textarea
        v-model="postForm.content"
        rows="2"
        class="mb-0"
        outlined
        label="Spread your thoughts!"
      ></v-textarea>
      <v-chip @click="browseImage" color="success" outlined>
        <v-icon left>mdi-image</v-icon>Photo/Video
      </v-chip>
      <input @change="coverImage" id="cover_photo" type="file" hidden accept="image/*, video/*" />
      <v-btn
        :loading="loading"
        :disabled="loading"
        @click="createPost"
        color="success"
        absolute
        right
      >Advertise</v-btn>
    </v-form>

    <v-container v-show="postForm.cover_image">
      <v-btn color="danger" top left icon>
        <v-icon @click="removeCover">mdi-close</v-icon>
      </v-btn>
      <v-img :src="postForm.cover_image" width="150"></v-img>
    </v-container>

    <!-- POST SECTION -->
    <v-card v-for="post in getAllPosts" :key="post.id" class="mt-4">
      <v-list-item>
        <router-link :to="'/viewuser/' + post.author.id" class="text-decoration-none">
          <v-list-item-avatar size="50">
            <img
              class="img-circle img-bordered border-success"
              :src="'storage/profile_photo/' + post.author.profile.photo"
              alt="Profile Photo"
            />
          </v-list-item-avatar>
        </router-link>
        <v-list-item-content>
          <v-list-item-title @click="viewPost(post)" class="headline">{{post.title}}</v-list-item-title>
          <v-list-item-subtitle>
            by
            <router-link :to="'/viewuser/' + post.author.id">{{post.author.name}}</router-link>
            <span class="small ml-2">({{post.author.type}})</span>
            <span class="small float-right">Posted {{post.created_at | sinceDate}}</span>
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
      <v-container>
        <v-row>
          <v-col v-if="post.cover_image" sm="6">
            <v-img :src="'storage/cover_photo/' + post.cover_image" max-width="270"></v-img>
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
                <v-list-item-title class="mt-2">{{limitStr(post.content)}}</v-list-item-title>
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
          <a v-show="post.authorize" @click="editPost(post)" class="link-black text-sm mr-2">Edit</a>

          <!-- DIALOG FOR EDITING POST -->
          <v-dialog v-model="dialog" width="600">
            <v-card tile>
              <v-toolbar>
                <v-toolbar-title>Edit Post</v-toolbar-title>
                <div class="flex-grow-1"></div>
                <v-toolbar-items>
                  <v-btn dark text @click="dialog = false">
                    <v-icon>mdi-close</v-icon>Cancel
                  </v-btn>
                </v-toolbar-items>
              </v-toolbar>
              <v-card-text class="mt-3">
                <v-form>
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
                  <v-textarea v-model="postForm.content" class="mb-0" label="Spread your thoughts!"></v-textarea>
                  <v-chip @click="browseImage">
                    <v-icon>mdi-image</v-icon>
                  </v-chip>
                  <v-chip>
                    <v-icon>mdi-video</v-icon>
                  </v-chip>
                  <v-chip>
                    <v-icon>mdi-map-marker</v-icon>
                  </v-chip>
                  <input @change="coverImage" id="cover_photo" type="file" hidden accept="image/*" />
                  <v-btn @click="updatePost" absolute outlined right>Update</v-btn>
                </v-form>
                <v-container v-show="postForm.cover_image">
                  <v-btn color="danger" top right icon>
                    <v-icon @click="removeCover">mdi-close</v-icon>
                  </v-btn>
                  <v-img
                    max-width="250"
                    v-show="postForm.cover_image !== null"
                    :src="postForm.cover_image !== null && postForm.cover_image.length > 200? postForm.cover_image : 'storage/cover_photo/' + postForm.cover_image"
                  ></v-img>
                </v-container>
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
              <span>View</span>
              ({{post.comments.length}})
            </a>
          </span>
        </p>

        <!-- COMMENT SECTION -->
        <div class="comment-section" :id="'CS-' + post.id">
          <div class="media mb-3" v-for="comment in post.comments" :key="comment.id">
            <v-avatar size="42" class="mr-2">
              <v-img :src="'storage/profile_photo/' + comment.author.profile.photo"></v-img>
            </v-avatar>
            <div class="media-body">
              <v-sheet class="rounded shadow-sm pl-2">
                <span class="mt-0 caption font-weight-bold">{{comment.author.name}}</span>
                <p class="body-1 mb-0">{{comment.content}}</p>
                <span class="small mr-3">{{comment.created_at | sinceDate}}</span>
                <span
                  v-if="comment.agree"
                  @click="agreeComment(comment.id)"
                  class="btn-link success--text small mr-3"
                  :style="{ cursor: 'pointer'}"
                >&check; Agreed</span>
                <span
                  v-else
                  @click="agreeComment(comment.id)"
                  color="primary"
                  class="btn-link primary--text small mr-3"
                  :style="{ cursor: 'pointer'}"
                >Agree</span>
                <span
                  v-show="comment.authorize"
                  @click="deleteComment(comment.id)"
                  class="btn-link danger--text small"
                  :style="{ cursor: 'pointer'}"
                >Delete</span>
              </v-sheet>
            </div>
          </div>
          <v-btn @click="toggleComment(post.id)" text small>View Less</v-btn>
        </div>

        <v-form :id="'F-' + post.id" @submit.prevent="createComment(post)" class="mt-2">
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
                @submit.prevent="createComment(post)"
                class="btn btn-success"
              >Send</button>
            </span>
          </div>
        </v-form>
      </v-container>
    </v-card>

    <div v-show="!loaded">
      <v-skeleton-loader type="card-avatar" class="mx-auto mt-3"></v-skeleton-loader>
      <v-skeleton-loader type="list-item-avatar-three-line" class="mx-auto mt-2"></v-skeleton-loader>
      <v-skeleton-loader type="list-item-avatar-three-line" class="mx-auto mt-2"></v-skeleton-loader>
    </div>

    <v-overlay :value="overlay">
      <v-progress-circular indeterminate color="green" :size="100" :width="15"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  mounted() {
    console.log("Component mounted.");
  },

  watch: {
    dialog(val) {
      !val && this.postForm.reset();
    }
  },

  created() {
    this.fetchAllPosts().then(() => {
      this.loaded = true;
    });
  },

  data() {
    return {
      loaded: false,
      loading: false,
      overlay: false,
      isActive: false,
      dialog: false,
      tags: "",

      postForm: new Form({
        id: "",
        rating: null,
        title: "",
        content: "",
        cover_image: "",
        delete_cover: "",
        tags: ""
      }),

      comment: new Form({
        id: "",
        post_id: "",
        post_author: "",
        body: ""
      })
    };
  },

  computed: {
    ...mapGetters(["getCurrentUser", "getAllPosts"])
  },

  methods: {
    ...mapActions(["fetchViewPost", "fetchAllPosts"]),

    limitStr(text) {
      let str = text;
      let limit = 100;
      if (typeof str === "string" && str.length > limit) {
        str = str.slice(0, limit) + "...";
      }
      return str;
    },

    viewPost(post) {
      this.overlay = true;
      this.fetchViewPost(post.id).then(() => {
        this.$router.push("/view-post");
      });
    },

    removeCover() {
      this.postForm.cover_image = "";
    },

    agreeComment(comment_id) {
      axios
        .post("api/agree", {
          comment_id: comment_id
        })
        .then(response => {
          this.fetchAllPosts();
          Toast.fire({
            type: "success",
            title: "Successful!"
          });
        });
    },

    toggleCommend(post_id) {
      this.loading = true;
      axios
        .post("api/commend", {
          post_id: post_id
        })
        .then(response => {
          this.loading = false;
          this.fetchAllPosts();
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
          this.fetchAllPosts();
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
    createComment(post) {
      let comment = document.querySelector("#C-" + post.id).value;

      this.comment.post_id = post.id;
      this.comment.post_author = post.author_id;
      this.comment.body = comment;
      axios
        .post("api/comment", this.comment)
        .then(() => {
          this.fetchAllPosts();
          document.querySelector("#F-" + post.id).reset();
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

    editPost(post) {
      this.postForm.fill(post);
      this.dialog = true;
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
          this.fetchAllPosts();
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
              this.fetchAllPosts();
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

    createPost() {
      this.$Progress.start();
      this.postForm.tags = this.tags;
      axios
        .post("api/post", {
          title: this.postForm.title,
          content: this.postForm.content,
          cover_image: this.postForm.cover_image,
          tags: this.postForm.tags
        })
        .then(() => {
          this.fetchAllPosts();
          Toast.fire({
            type: "success",
            title: "Post successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
      this.postForm.reset();
      this.tags = "";
    }
  }
};
</script>
