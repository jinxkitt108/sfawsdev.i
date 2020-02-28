<template>
  <v-card>
    <v-toolbar flat dark color="success">
      <v-btn icon dark @click="() => this.$emit('closeDialog')">
        <v-icon>mdi-close</v-icon>
      </v-btn>
      <v-spacer></v-spacer>
      <v-btn dark text @click="() => this.$emit('closeDialog')">Close</v-btn>
    </v-toolbar>
    <v-card-text>
      <v-list>
        <v-list-item>
          <router-link :to="'viewuser/' + getViewPost.author.id" class="text-decoration-none">
            <v-list-item-avatar size="50">
              <img
                class="img-circle img-bordered border-success"
                :src="'storage/profile_photo/' + getViewPost.author.profile.photo"
                alt="Profile Photo"
              />
            </v-list-item-avatar>
          </router-link>
          <v-list-item-content>
            <v-list-item-title class="headline">{{getViewPost.title}}</v-list-item-title>
            <v-list-item-subtitle>
              by
              <router-link :to="'viewuser/' + getViewPost.author.id">{{getViewPost.author.name}}</router-link>
              <span class="small ml-2">({{getViewPost.author.type}})</span>
              <span class="small float-right">Posted {{getViewPost.created_at | sinceDate}}</span>
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>

      <!-- POST SECTION -->
      <v-container>
        <v-row>
          <v-col cols="12" md="7">
            <v-img
              aspect-ratio="2"
              :src="'storage/cover_photo/' + getViewPost.cover_image"
              width="450"
            ></v-img>
          </v-col>
          <v-col cols="12" md="5" class="border-left">
            <p>{{getViewPost.content}}</p>
            <v-badge color="green">
              <template v-slot:badge>{{getViewPost.commends}}</template>
              <v-chip color="primary" small>Commends</v-chip>
            </v-badge>
          </v-col>
        </v-row>
      </v-container>

      <!-- REACTION SECTION -->
      <p>
        <a
          v-if="getViewPost.commend"
          @click="toggleCommend(getViewPost.id)"
          class="link-black text-sm mr-2 primary--text"
        >
          <strong>
            <i class="fas fa-check mr-1"></i>Recommended
          </strong>
        </a>
        <a
          v-else
          @click="toggleCommend(getViewPost.id)"
          class="link-black text-sm mr-2 primary--text"
        >Recommend</a>
        <a
          v-show="getViewPost.authorize"
          @click="dialog = true; postForm.fill(getViewPost)"
          class="link-black text-sm mr-2"
        >Edit</a>

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
                    v-model="postForm.tags"
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
                <v-btn color="danger" top left icon>
                  <v-icon @click="removeCover" left>mdi-close</v-icon>
                </v-btn>
                <v-img
                  :src="postForm.cover_image.length > 200 ? postForm.cover_image : 'storage/cover_photo/' + postForm.cover_image"
                  width="150"
                ></v-img>
              </v-container>
            </v-card-text>
            <div style="flex: 1 1 auto;"></div>
          </v-card>
        </v-dialog>

        <a
          v-show="getViewPost.authorize"
          @click="deletePost(getViewPost.id)"
          class="link-black text-sm text-danger"
        >Delete</a>
        <span class="float-right">
          <a @click="toggleComment(getViewPost.id)" class="link-black text-sm">
            <i class="fas fa-comments mr-1"></i>
            <span>Comments</span>
            ({{getViewPost.comments.length}})
          </a>
        </span>
      </p>
    </v-card-text>

    <!-- COMMENTS SECTION -->
    <v-card-text class="ml-4 mr-4">
      <div class="comment-section" :id="'DCS-' + getViewPost.id">
        <div class="media mb-3" v-for="comment in getViewPost.comments" :key="comment.id">
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
        <v-btn @click="toggleComment(getViewPost.id)" text small>View Less</v-btn>
      </div>

      <!-- Create Comment Input -->
      <v-form @submit.prevent="createComment(getViewPost)" class="mt-2">
        <div class="input-group">
          <input v-model="comment.body" placeholder="Comment here..." class="form-control" />
          <span class="input-group-append">
            <button
              type="submit"
              @submit.prevent="createComment(getViewPost.id)"
              class="btn btn-success"
            >Send</button>
          </span>
        </div>
      </v-form>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data() {
    return {
      dialog: false,

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
    ...mapGetters(["getViewPost"])
  },

  methods: {
    ...mapActions(["fetchViewPost"]),

    agreeComment(comment_id) {
      axios
        .post("api/agree", {
          comment_id: comment_id
        })
        .then(response => {
          this.fetchViewPost(this.getViewPost.id);
          Toast.fire({
            type: "success",
            title: "Successful!"
          });
        });
    },

    removeCover() {
      this.postForm.cover_image = "";
    },

    toggleCommend(post_id) {
      axios
        .post("api/commend", {
          post_id: post_id
        })
        .then(response => {
          this.fetchViewPost(post_id);
          Toast.fire({
            type: "success",
            title: "Successful!"
          });
        });
    },

    remove(item) {
      this.tags.splice(this.tags.indexOf(item), 1);
      this.tags = [...this.tags];
    },

    browseImage() {
      $("#cover_photo").click();
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

    deleteComment(id) {
      this.comment
        .delete("api/comment/" + id)
        .then(() => {
          Toast.fire({
            type: "success",
            title: "Comment Deleted!"
          });
          this.fetchViewPost(this.getViewPost.id);
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
      $("#DCS-" + comment_section).slideToggle();
    },

    //Create Comment
    createComment(post) {
      this.comment.post_id = post.id;
      this.comment.post_author = post.author_id;
      axios
        .post("api/comment", this.comment)
        .then(() => {
          this.fetchViewPost(post.id);
          this.comment.reset();
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
          this.fetchViewPost(this.postForm.id);
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
              this.$router.push("/home");
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
    }
  },

  created() {}
};
</script>
