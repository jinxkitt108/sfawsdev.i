<template>
  <v-container v-if="loaded">
    <v-btn @click="dialog = true" color="success" align="right">Ask Question</v-btn>
    <v-dialog v-model="dialog" max-width="500">
      <v-card>
        <v-card-title>
          <v-btn icon @click="dialog = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>Create Discussion
        </v-card-title>
        <v-card-text>
          <v-form @submit.prevent="createDiscussion">
            <v-text-field v-model="discussionForm.title" outlined label="Title"></v-text-field>
            <v-select
              v-model="discussionForm.category"
              solo
              item-color="success"
              :items="['Agriculture', 'Aquaculture', 'Animals', 'Supplies', 'Fruits', 'Vegetables', 'Techniques']"
              label="Category"
            ></v-select>

            <v-textarea v-model="discussionForm.content" rows="4" outlined label="Say something..."></v-textarea>
            <v-chip @click="browseImage">
              <v-icon top>mdi-image</v-icon>Add Image
            </v-chip>
            <input @change="coverImage" id="cover_photo" type="file" hidden accept="image/*" />

            <v-btn type="submit" color="success" absolute right>Send</v-btn>
          </v-form>
          <v-container v-show="discussionForm.photo">
            <v-btn color="danger" top left icon>
              <v-icon @click="removeCover">mdi-close</v-icon>
            </v-btn>
            <v-img :src="discussionForm.photo" width="150"></v-img>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-divider class="mb-2"></v-divider>
    <div
      @click="viewDiscussion(discussion.id)"
      v-for="discussion in getAllDiscussions.data"
      :key="discussion.id"
      class="mb-2"
      v-ripple
    >
      <v-hover v-slot:default="{ hover }" open-delay="200">
        <v-card flat :elevation="hover ? 5 : 0" class="w-100 border-bottom">
          <v-list-item>
            <v-list-item-avatar size="60">
              <img :src="'storage/profile_photo/' + discussion.user.profile.photo" class="mr-3" />
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title class="font-weight-black">{{discussion.title}}</v-list-item-title>
              <v-list-item-title>
                <span class="subtitle-2 mr-1">by {{discussion.user.name}}</span>
                &bull;
                <span class="ml-1 small">{{discussion.created_at | sinceDate}}</span>
                <v-chip color="primary" outlined small>{{discussion.category}}</v-chip>
                <div class="overline">{{discussion.user.type}}</div>
              </v-list-item-title>
              <v-list-item-subtitle class="mt-1">{{discussion.content}}</v-list-item-subtitle>
              <v-list-item-subtitle class="mt-3">
                <v-icon>fas fa-comments</v-icon>
                <span class="subtitle-2 font-weight-bold mr-1">{{discussion.replies.length}}</span>
                <v-chip v-show="discussion.resolved" color="success" small>
                  <v-icon left>mdi-check</v-icon>Resolved
                </v-chip>
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-card>
      </v-hover>
    </div>
    <div class="text-center">
      <v-pagination v-model="getAllDiscussions.current_page" :length="getAllDiscussions.last_page" circle></v-pagination>
    </div>
  </v-container>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
  data() {
    return {
      loaded: false,
      dialog: false,
      page: '',
      discussionForm: new Form({
        title: "",
        category: "",
        content: "",
        photo: ""
      })
    };
  },

  computed: {
    ...mapGetters(["getAllDiscussions"])
  },

  methods: {
    ...mapActions(["addDiscussion", "fetchDiscussions", "fetchDiscussion"]),

    viewDiscussion(id) {
      this.fetchDiscussion(id).then(() => {
        this.$router.push("/view-discussion");
      });
    },

    createDiscussion() {
      this.$Progress.start();
      this.addDiscussion(this.discussionForm)
        .then(() => {
          this.dialog = false;
          Toast.fire({
            type: "success",
            title: "Topic created successfuly!"
          });

          this.discussionForm.reset();
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
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
        this.discussionForm.photo = reader.result;
      };
      reader.readAsDataURL(image);
    },

    removeCover() {
      this.discussionForm.photo = "";
    }
  },

  created() {
    this.fetchDiscussions().then(() => {
      this.loaded = true;
    });
  }
};
</script>

<style>
</style>