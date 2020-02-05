<template>
  <v-container>
    <v-card flat class="pa-3">
      <div class="media">
        <v-avatar>
          <img
            :src="'storage/profile_photo/' + getDiscussion.user.profile.photo"
            class="mr-3"
            alt="..."
          />
        </v-avatar>
        <div class="media-body">
          <div class="d-flex">
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>
                  <span class="subtitle-2 font-weight-bold mr-1">{{getDiscussion.user.name}}</span>
                  &bull;
                  <span
                    class="ml-1 small"
                  >{{getDiscussion.created_at | sinceDate}}</span>
                  <div class="overline">{{getDiscussion.user.type}}</div>
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </div>
          <h5 class="mt-2">{{getDiscussion.title}}</h5>
          <p class="font-weight-bold">{{getDiscussion.content}}</p>
          <img
            v-if="getDiscussion.photo"
            :src="'storage/discussion_photo/' + getDiscussion.photo"
            alt
          />
          <div
            v-show="reply.best_answer"
            v-for="reply in getAllReply"
            :key="reply.id"
            class="media mt-4 pa-2 border-left shadow"
          >
            <v-avatar>
              <img
                :src="'storage/profile_photo/' + reply.author.profile.photo"
                class="mr-3"
                alt="..."
              />
            </v-avatar>
            <div class="media-body">
              <v-chip color="success">BEST ANSWER !</v-chip>
              <div class="d-flex">
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>
                      <span class="subtitle-2 font-weight-bold mr-1">{{reply.author.name}}</span>
                      &bull;
                      <span
                        class="ml-1 small"
                      >{{reply.created_at | sinceDate}}</span>
                      <div class="overline">{{reply.author.type}}</div>
                    </v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </div>
              <v-img
                v-if="reply.photo"
                :src="'storage/reply_photo/' + reply.photo"
                width="200"
                class="mb-1"
              ></v-img>
              <p>{{reply.content}}</p>
            </div>
          </div>
        </div>
      </div>
    </v-card>
    <v-divider></v-divider>
    <v-card-title class="subtitle-1 font-weight-bold">REPLIES ({{getAllReply.length}})</v-card-title>
    <v-timeline v-show="getAllReply.length" align-top dense clipped>
      <v-timeline-item v-for="reply in getAllReply" :key="reply.id" right large>
        <template v-slot:icon>
          <v-avatar>
            <img :src="'storage/profile_photo/' + reply.author.profile.photo" alt />
          </v-avatar>
        </template>
        <v-card>
          <v-card-title>
            <v-bottom-sheet v-model="sheet" inset hide-overlay>
              <template v-slot:activator="{ on }">
                <v-chip
                  v-show="!reply.authorize"
                  v-on="on"
                  @click="reply_to(reply.author)"
                  color="success"
                  outlined
                  small
                >
                  <v-icon left>fas fa-reply</v-icon>REPLY
                </v-chip>
              </template>
              <v-card>
                <v-card-title class="subtitle-1">
                  <v-icon left>mdi-reply</v-icon>Reply to
                  <span class="success--text ml-1 font-weight-black">{{replyForm.to_user_name}}</span>
                </v-card-title>
                <v-card-text>
                  <v-divider></v-divider>
                  <v-textarea v-model="replyForm.content" autofocus></v-textarea>
                  <div class="d-flex">
                    <v-chip @click="browseImage" class="mr-auto">
                      <v-icon top>mdi-image</v-icon>Add Image
                    </v-chip>
                    <input
                      @change="coverImage"
                      id="cover_photo"
                      type="file"
                      hidden
                      accept="image/*"
                    />
                    <v-btn @click="sheet = !sheet" class="mr-2" rounded>Cancel</v-btn>
                    <v-btn @click="sendReply" rounded color="success">
                      <v-icon>mdi-send</v-icon>
                    </v-btn>
                  </div>
                  <v-container v-show="replyForm.photo">
                    <v-btn color="danger" top left icon>
                      <v-icon @click="removeCover">mdi-close</v-icon>
                    </v-btn>
                    <v-img :src="replyForm.photo" width="150"></v-img>
                  </v-container>
                </v-card-text>
              </v-card>
            </v-bottom-sheet>
            <v-checkbox
              v-show="getDiscussion.authorize"
              loading
              small
              label="Best Answer"
              :input-value="reply.best_answer"
              @change="toggleBestAnswer(reply)"
              class="ml-2"
            ></v-checkbox>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>
                  <span class="subtitle-2 font-weight-bold mr-1">{{reply.author.name}}</span>
                  &bull;
                  <span class="ml-1 small mr-1">{{reply.created_at | sinceDate}}</span>
                  <span v-show="reply.authorize">&bull;</span>
                  <v-btn v-show="reply.authorize" @click="deleteReply(reply.id)" text small>
                    <v-icon left>mdi-delete-circle</v-icon>Delete
                  </v-btn>
                  <div class="overline">{{reply.author.type}}</div>
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-card-title>
          <v-card-text>
            <v-img
              v-if="reply.photo"
              :src="'storage/reply_photo/' + reply.photo"
              width="200"
              class="mb-1"
            ></v-img>
            <p>
              <span
                v-if="reply.to_user !== null"
                class="success--text subtitle-2 font-weight-black mr-1"
              >{{'@' + reply.to_user.name}}</span>
              {{reply.content}}
            </p>
          </v-card-text>
        </v-card>
      </v-timeline-item>
    </v-timeline>
    <v-card-actions v-show="getDiscussion.authorize">
      <v-btn v-if="getDiscussion.resolved" @click="toggleResolved(getDiscussion)">
        <v-icon left>mdi-check</v-icon>Resolved
      </v-btn>
      <v-btn v-else @click="toggleResolved(getDiscussion)" color="success">Mark as Resolved</v-btn>
    </v-card-actions>
    <v-textarea v-model="replyForm.content" rows="3" outlined label="type reply.." required></v-textarea>
    <v-chip @click="browseImage">
      <v-icon top>mdi-image</v-icon>Add Image
    </v-chip>
    <input @change="coverImage" id="cover_photo" type="file" hidden accept="image/*" />
    <v-btn @click="sendReply" outlined color="success" absolute right>
      <v-icon>mdi-send</v-icon>
    </v-btn>
    <v-container v-show="replyForm.photo">
      <v-btn color="danger" top left icon>
        <v-icon @click="removeCover">mdi-close</v-icon>
      </v-btn>
      <v-img :src="replyForm.photo" width="150"></v-img>
    </v-container>
  </v-container>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
  computed: {
    ...mapGetters(["getDiscussion", "getAllReply"])
  },

  data() {
    return {
      sheet: false,
      rules: {
        required: value => !!value || "Required."
      },
      replyForm: new Form({
        discussion_id: "",
        to_user_id: "",
        to_user_name: "",
        content: "",
        photo: ""
      })
    };
  },

  methods: {
    ...mapActions([
      "fetchDiscussion",
      "addReply",
      "fetchAllReply",
      "updateBestAnswer",
      "updateResolved",
      "deleteReply"
    ]),

    reply_to(user) {
      this.replyForm.to_user_id = user.id;
      this.replyForm.to_user_name = user.name;
    },
    toggleResolved(discussion) {
      this.updateResolved(discussion).then(() => {
        this.fetchDiscussion(discussion.id);
        Toast.fire({
          type: "success",
          title: "Best answer updated!"
        });
      });
    },

    toggleBestAnswer(reply) {
      this.updateBestAnswer(reply).then(() => {
        this.fetchAllReply(this.getDiscussion.id);
        Toast.fire({
          type: "success",
          title: "Best answer updated!"
        });
      });
    },

    sendReply() {
      this.addReply(this.replyForm)
        .then(() => {
          this.replyForm.reset();
          this.sheet = false;
          this.fetchAllReply(this.getDiscussion.id);
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

    removeCover() {
      this.replyForm.photo = "";
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
        this.replyForm.photo = reader.result;
      };
      reader.readAsDataURL(image);
    }
  },

  created() {
    this.replyForm.discussion_id = this.getDiscussion.id;
    this.fetchAllReply(this.getDiscussion.id);
  }
};
</script>

<style>
</style>
