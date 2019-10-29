<style scoped>
.comment-section {
  display: none;
}
</style>
<template>
  <v-container fluid>
    <v-card>
      <v-card-text>
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
                  @submit:close="remove(item)"
                >
                  <strong>{{ item }}</strong>
                </v-chip>
              </template>
            </v-combobox>
          </div>
          <v-textarea
            v-model="postForm.content"
            class="mb-0"
            outlined
            label="Spread your thoughts!"
          ></v-textarea>
          <v-chip @click="browseImage">
            <v-avatar left>
              <v-icon>mdi-image</v-icon>
            </v-avatar>Photos
          </v-chip>
          <v-chip>
            <v-avatar left>
              <v-icon>mdi-video</v-icon>
            </v-avatar>Videos
          </v-chip>
          <v-chip>
            <v-avatar left>
              <v-icon>mdi-map-marker</v-icon>
            </v-avatar>Location
          </v-chip>
          <input @change="coverImage" id="cover_photo" type="file" hidden accept="image/*" />
          <v-btn @click="createPost" outlined absolute bottom right>
            <v-icon>mdi-post</v-icon>Advertise
          </v-btn>
        </form>
      </v-card-text>
    </v-card>
    <v-card v-for="post in posts" :key="post.id" class="mt-4">
      <v-list-item>
        <v-list-item-avatar size="50">
          <img
            class="img-circle img-bordered border-success"
            :src="'storage/profile_photo/' + post.author.profile.photo"
            alt="Profile Photo"
          />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title class="headline">{{post.title}}</v-list-item-title>
          <v-list-item-subtitle>
            by {{post.author.name | capitalize}}
            <span class="small ml-2">({{post.author.type}})</span>
            <span class="float-right">Posted {{post.created_at | postDate}}</span>
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
      <v-container>
        <v-row>
          <v-col v-show="post.cover_image" sm="6">
            <v-img :src="'storage/cover_photo/' + post.cover_image" height="250"></v-img>
          </v-col>
          <v-col>
            <v-card-text class="mt-0 pt-0">
              <p>
                <strong>Commends:</strong>
                <span class="text-danger">({{post.commends}})</span>
              </p>
              <p>{{post.content}}</p>
            </v-card-text>
          </v-col>
        </v-row>
        <p>
          <a class="link-black text-sm mr-2">
            <i class="fas fa-leaf mr-1"></i> Spread
          </a>
          <a
            v-if="post.commend"
            @click="toggleCommend(post.id)"
            class="link-black text-sm mr-2 text-success"
          >
            <strong>
              <i class="fas fa-check mr-1"></i>Commended
            </strong>
          </a>
          <a
            v-else
            @click="toggleCommend(post.id)"
            class="link-black text-sm mr-2 text-success"
          >Commend</a>
          <a
            v-show="post.authorize"
            @click="dialog = true; postForm.fill(post)"
            class="link-black text-sm mr-2"
          >Edit</a>

          <!-- DIALOG FOR EDITING POST -->
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
                  <v-textarea v-model="postForm.content" class="mb-0" label="Spread your thoughts!"></v-textarea>
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
                  <input @change="coverImage" id="cover_photo" type="file" hidden accept="image/*" />
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
          <div class="card border-0 shadow-none" v-for="comment in post.comments" :key="comment.id">
            <div class="media ma-3 mb-0">
              <v-avatar size="45">
                <img
                  class="img-circle img-bordered border-success"
                  :src="'storage/profile_photo/' + comment.author.profile.photo"
                  alt="Message User Image"
                />
              </v-avatar>
              <div
                class="media-body ml-2 pa-3"
                style="background-color: #f1f1f1; border-radius: 15px"
              >
                <p class="mb-0">
                  <strong>{{comment.author.name | capitalize}}</strong>
                  <span class="small float-right">{{comment.created_at | postDate}}</span>
                </p>
                <span class="mt-0" style="font-size: 15px">{{comment.content}}</span>
              </div>
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
                @click="editComment(comment.id)"
                class="small mr-3"
              >Edit</button>
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
  </v-container>
</template>

<script>
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
    this.loadPosts();
  },
  data() {
    return {
      dialog: false,
      posts: {},
      tags: "",
      postForm: new Form({
        id: "",
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
        .post("api/comment", {
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
    loadPosts() {
      axios.get("api/post").then(({ data }) => (this.posts = data));
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
          this.loadPosts();
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
