<template>
  <v-container v-if="loaded">
    <v-tabs v-model="tab">
      <v-tab>TOPICS</v-tab>
      <v-tab>
        <span>My Questions</span>
      </v-tab>
      <v-spacer></v-spacer>
      <v-btn @click="dialog = true" color="success" align="right" class="align-self-center">
        <v-icon left>mdi-help-circle</v-icon>Ask Question
      </v-btn>
      <v-dialog v-model="dialog" max-width="500">
        <v-card>
          <v-card-title>
            <v-btn icon @click="dialog = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
            {{edit_mode ? "Edit Details" : "Create Discussion"}}
          </v-card-title>
          <v-card-text>
            <v-form @submit.prevent="edit_mode ? saveUpdtDiscussion() : createDiscussion()">
              <v-text-field v-model="discussionForm.title" outlined label="Title"></v-text-field>
              <v-select
                v-model="discussionForm.category"
                solo
                item-color="success"
                :items="['Agriculture', 'Aquaculture', 'Animals', 'Supplies', 'Fruits', 'Vegetables', 'Techniques']"
                label="Category"
              ></v-select>

              <v-textarea
                v-model="discussionForm.content"
                rows="4"
                outlined
                label="Say something..."
              ></v-textarea>
              <div class="d-flex">
                <v-chip @click="browseImage" class="mr-auto">
                  <v-icon top>mdi-image</v-icon>Add Image
                </v-chip>
                <input @change="coverImage" id="cover_photo" type="file" hidden accept="image/*" />

                <v-btn @click="close" class="mr-2">Cancel</v-btn>
                <v-btn type="submit" color="success">Send</v-btn>
              </div>
            </v-form>
            <v-container v-if="discussionForm.photo && discussionForm.photo !== null">
              <v-btn color="danger" top left icon>
                <v-icon @click="removeCover">mdi-close</v-icon>
              </v-btn>
              <v-img
                v-if="discussionForm.photo.length > 200"
                :src="discussionForm.photo"
                width="150"
              ></v-img>
              <v-img v-else :src="'storage/discussion_photo/' + discussionForm.photo" width="150"></v-img>
            </v-container>
          </v-card-text>
        </v-card>
      </v-dialog>
      <v-tabs-items v-model="tab">
        <v-tab-item>
          <v-data-iterator
            :items="getAllDiscussions.data"
            :search="search"
            loading="getAllDiscussions"
          >
            <template v-slot:header>
              <v-toolbar flat>
                <v-spacer></v-spacer>
                <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Search topic.."
                  single-line
                  hide-details
                ></v-text-field>
              </v-toolbar>
            </template>
            <template v-slot:default="props">
              <div
                @click="viewDiscussion(item.id)"
                v-for="item in props.items"
                :key="item.id"
                class="mb-2"
              >
                <v-hover v-slot:default="{ hover }">
                  <v-card :elevation="hover ? 5 : 0" flat v-ripple>
                    <v-list-item>
                      <v-list-item-avatar size="56">
                        <v-img
                          :src="'storage/profile_photo/' + item.user.profile.photo"
                          class="mr-3"
                        ></v-img>
                      </v-list-item-avatar>
                      <v-list-item-content>
                        <v-list-item-title class="font-weight-black">{{item.title}}</v-list-item-title>
                        <v-list-item-title>
                          <span class="subtitle-2 mr-1">by {{item.user.name}}</span>
                          &bull;
                          <span
                            class="ml-1 small"
                          >{{item.created_at | sinceDate}}</span>
                          <v-chip color="primary" outlined small>{{item.category}}</v-chip>
                          <div class="overline">{{item.user.type}}</div>
                        </v-list-item-title>
                        <v-list-item-subtitle class="mt-1">{{item.content}}</v-list-item-subtitle>
                        <v-list-item-subtitle class="mt-3">
                          <v-icon>fas fa-comments</v-icon>
                          <span class="subtitle-2 font-weight-bold mr-1">{{item.replies.length}}</span>
                          <v-chip v-show="item.resolved" color="success" small>
                            <v-icon left>mdi-check</v-icon>Resolved
                          </v-chip>
                        </v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                  </v-card>
                </v-hover>
              </div>
            </template>
          </v-data-iterator>
        </v-tab-item>

        <v-tab-item>
          <v-data-iterator :items="getMyDiscussions" :search="search">
            <template v-slot:header>
              <v-toolbar flat>
                <v-spacer></v-spacer>
                <v-text-field
                  width
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Search topic.."
                  single-line
                  hide-details
                ></v-text-field>
              </v-toolbar>
            </template>
            <template v-slot:default="props">
              <div v-for="item in props.items" :key="item.id" class="mb-2">
                <v-hover v-slot:default="{ hover }">
                  <v-card :elevation="hover ? 5 : 0" flat v-ripple>
                    <v-list-item>
                      <v-list-item-avatar size="56" @click="viewDiscussion(item.id)">
                        <img :src="'storage/profile_photo/' + item.user.profile.photo" class="mr-3" />
                      </v-list-item-avatar>
                      <v-list-item-content>
                        <v-list-item-title
                          @click="viewDiscussion(item.id)"
                          class="font-weight-black"
                        >{{item.title}}</v-list-item-title>
                        <v-list-item-title>
                          <span class="subtitle-2 mr-1">by {{item.user.name}}</span>
                          &bull;
                          <span
                            class="ml-1 small"
                          >{{item.created_at | sinceDate}}</span>
                          <v-chip color="primary" outlined small>{{item.category}}</v-chip>
                          <div class="overline">{{item.user.type}}</div>
                        </v-list-item-title>
                        <v-list-item-subtitle
                          @click="viewDiscussion(item.id)"
                          class="mt-1"
                        >{{item.content}}</v-list-item-subtitle>
                        <v-list-item-subtitle>
                          <v-chip v-show="item.resolved" color="success" small>
                            <v-icon left>mdi-check</v-icon>Resolved
                          </v-chip>
                        </v-list-item-subtitle>
                        <v-list-item-subtitle class="mt-3">
                          <v-icon>fas fa-comments</v-icon>
                          <span class="subtitle-2 font-weight-bold mr-1">{{item.replies.length}}</span>

                          <v-btn @click="editDiscussion(item)" text small color="success">
                            <v-icon left>mdi-square-edit-outline</v-icon>Edit
                          </v-btn>
                          <v-btn @click="deleteDiscussion(item.id)" text small color="danger">
                            <v-icon left>mdi-delete-circle</v-icon>Delete
                          </v-btn>
                        </v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                  </v-card>
                </v-hover>
              </div>
            </template>
          </v-data-iterator>
        </v-tab-item>
      </v-tabs-items>
    </v-tabs>
  </v-container>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
  watch: {
    dialog(val) {
      !val && this.discussionForm.reset();
    }
  },

  data() {
    return {
      loaded: false,
      dialog: false,
      page: "",
      tab: null,
      search: "",
      edit_mode: false,
      discussionForm: new Form({
        id: "",
        title: "",
        category: "",
        content: "",
        photo: ""
      })
    };
  },

  computed: {
    ...mapGetters(["getAllDiscussions", "getMyDiscussions"])
  },

  methods: {
    ...mapActions([
      "addDiscussion",
      "fetchDiscussions",
      "fetchDiscussion",
      "fetchMyDiscussions",
      "deleteDiscussion",
      "updateDiscussion"
    ]),

    close() {
      this.discussionForm.reset();
      this.dialog = false;
    },

    saveUpdtDiscussion() {
      this.updateDiscussion(this.discussionForm).then(() => {
        this.close();
        Swal.fire("Updated!", "You updated the discussion!", "success");
      });
    },

    editDiscussion(item) {
      this.edit_mode = true;
      this.dialog = true;
      this.discussionForm.fill(item);
    },

    viewDiscussion(id) {
      this.fetchDiscussion(id).then(() => {
        this.$router.push("/view_discussion");
      });
    },

    createDiscussion() {
      this.$Progress.start();
      this.addDiscussion(this.discussionForm)
        .then(() => {
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
      this.dialog = false;
    },

    browseImage() {
      $("#cover_photo").click();
    },

    coverImage(file) {
      let image = file.target.files[0];
      let reader = new FileReader();
      reader.onloadend = image => {
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
    this.fetchMyDiscussions();
  }
};
</script>

<style>
</style>
