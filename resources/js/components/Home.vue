<template>
  <v-container fluid>
    <v-toolbar dark color="primary">
      <v-toolbar-title>Search:</v-toolbar-title>
      <v-text-field
        @click="searchMode"
        cache-items
        hide-no-data
        hide-details
        class="mx-4"
        flat
        label="What are you looking for?"
        solo-inverted
      ></v-text-field>
    </v-toolbar>
    <v-card v-if="search">
      <v-card-title class="subtitle-1">Suggested for you</v-card-title>
      <v-card-text>
        <carousel :per-page="4">
          <slide v-for="expert in experts" :key="expert.id" class="pa-2">
            <v-card>
              <img
                :src="'storage/profile_photo/' + expert.profile.photo"
                alt="avatar"
                style="max-width: 200px"
              />
              <v-card-text>
                <span class="subtitle-2 text--primary">{{expert.name | capitalize}}</span>
                <br />
                <span class="small text--primary">{{expert.type}}</span>
                <br />
                <span class="text--primary mr-3">Followers {{expert.followers}}</span>
                <span class="text--primary">Followings {{expert.followings}}</span>
                <br />
                <v-btn
                  v-if="expert.isFollowing"
                  @click="toggleFollow(expert.id)"
                  small
                  rounded
                  outlined
                >Unfollow</v-btn>
                <v-btn v-else @click="toggleFollow(expert.id)" small rounded color="primary">Follow</v-btn>
              </v-card-text>
            </v-card>
          </slide>
        </carousel>
      </v-card-text>
    </v-card>
    <v-card v-else>
      <v-card-text>
        <form>
          <div class="d-flex justify">
            <v-text-field v-model="postForm.title" outlined label="Title"></v-text-field>
            <v-combobox
              v-model="tags"
              chips
              clearable
              label="Add Tags"
              multiple
              outlined
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
            <v-icon>mdi-post</v-icon>post
          </v-btn>
        </form>
      </v-card-text>
    </v-card>
    <v-card v-for="post in posts" :key="post.id" class="mt-4">
      <v-list-item>
        <v-list-item-avatar color="grey">
          <img :src="'storage/profile_photo/' + post.author.profile.photo" alt="Profile Photo" />
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
            <v-card-text>
              <p>{{post.content}}</p>
            </v-card-text>
          </v-col>
        </v-row>
        <v-card-actions>
          <v-btn text>View</v-btn>
          <v-btn text>Comment</v-btn>
          <v-btn v-show="post.authorize" text color="primary" @click="dialog = true">[Edit]</v-btn>
          <v-dialog
            v-model="dialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
          >
            <v-card tile>
              <v-toolbar flat dark color="primary" height="50px">
                <v-btn icon dark @click="dialog = false">
                  <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>Settings</v-toolbar-title>
                <div class="flex-grow-1"></div>
                <v-toolbar-items>
                  <v-btn dark text @click="dialog = false">Save</v-btn>
                </v-toolbar-items>
                <v-menu bottom right offset-y>
                  <template v-slot:activator="{ on }">
                    <v-btn dark icon v-on="on">
                      <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                  </template>
                </v-menu>
              </v-toolbar>
              <v-card-text>
                <v-btn color="primary" dark class="ma-2" @click="dialog2 = !dialog2">Open Dialog 2</v-btn>
                <v-tooltip right>
                  <template v-slot:activator="{ on }">
                    <v-btn class="ma-2" v-on="on">Tool Tip Activator</v-btn>
                  </template>
                  Tool Tip
                </v-tooltip>
                <v-list three-line subheader>
                  <v-subheader>User Controls</v-subheader>
                  <v-list-item>
                    <v-list-item-content>
                      <v-list-item-title>Content filtering</v-list-item-title>
                      <v-list-item-subtitle>Set the content filtering level to restrict apps that can be downloaded</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                  <v-list-item>
                    <v-list-item-content>
                      <v-list-item-title>Password</v-list-item-title>
                      <v-list-item-subtitle>Require password for purchase or use password to restrict purchase</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </v-list>
                <v-divider></v-divider>
              </v-card-text>

              <div style="flex: 1 1 auto;"></div>
            </v-card>
          </v-dialog>
          <v-btn v-show="post.authorize" text color="danger" @click="deletePost(post.id)">[Delete]</v-btn>
          <div class="flex-grow-1"></div>
          <v-btn icon>
            <v-icon>mdi-leaf</v-icon>
          </v-btn>
          <v-btn icon>
            <v-icon>mdi-share-variant</v-icon>
          </v-btn>
        </v-card-actions>
        <v-textarea outlined rows="1" name="input-7-4" label="Type comment.."></v-textarea>
        <div class="text-right">
          <v-btn color="primary">Post</v-btn>
        </div>
      </v-container>
    </v-card>
  </v-container>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
  created() {
    this.loadPosts();
    this.loadExperts();
  },
  data() {
    return {
      search: false,
      dialog: false,
      posts: {},
      experts: {},
      tags: "",
      postForm: new Form({
        title: "",
        content: "",
        cover_image: ""
      })
    };
  },
  methods: {
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
    toggleFollow(id) {
      let reference = $(this);
      axios
        .post("api/follow", {
          user_id: id
        })
        .then(response => {
          this.loadExperts();
          this.loadPosts();
          Toast.fire({
            type: "success",
            title: "Successful!"
          });
        });
    },
    loadExperts() {
      axios.get("api/experts").then(({ data }) => (this.experts = data.data));
    },
    searchMode() {
      this.search = true;
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
      axios
        .post("api/post", {
          title: this.postForm.title,
          content: this.postForm.content,
          cover_image: this.postForm.cover_image
        })
        .then(() => {
          //this.loadPosts();
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
    }
  }
};
</script>
