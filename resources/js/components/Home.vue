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
                <v-btn v-if="expert.isFollowing" @click="toggleFollow(expert.id)" small rounded outlined >Unfollow</v-btn>
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
