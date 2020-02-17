<template>
  <v-container>
    <v-card>
      <v-system-bar></v-system-bar>
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title>{{getMessage.subject}}</v-list-item-title>
          <v-list-item-title class="subtitle-2">
            <span>From: {{getMessage.sender.name}}</span>
            <span class="small float-right">{{getMessage.created_at | postDate}}</span>
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-divider></v-divider>

      <div class="d-flex justify-center">
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn small v-on="on" @click="deleteMessage(getMessage)">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </template>
          <span>Delete</span>
        </v-tooltip>

        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn small v-on="on" @click="replyToMessage(getMessage)">
              <v-icon>mdi-reply</v-icon>
            </v-btn>
          </template>
          <span>Reply</span>
        </v-tooltip>

        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn small v-on="on" @click="forwardMessage(getMessage)">
              <v-icon>mdi-share</v-icon>
            </v-btn>
          </template>
          <span>Forward</span>
        </v-tooltip>

        <v-bottom-sheet v-model="sheet" inset>
          <v-card flat>
            <v-system-bar>{{label}}</v-system-bar>
            <v-form @submit.prevent="sendMessage" class="pa-3">
              <v-autocomplete
                v-model="messageForm.receiver"
                :items="allFollowingUsers"
                chips
                prefix="To"
                prepend-icon="mdi-account"
                item-text="name"
                return-object
                single-line
                multiple
              >
                <template v-slot:selection="data">
                  <v-chip
                    dark
                    color="teal"
                    :input-value="data.selected"
                    close
                    @click="data.select"
                    @click:close="remove(data.item)"
                  >
                    <v-avatar left>
                      <v-img :src="'storage/profile_photo/' + data.item.profile.photo"></v-img>
                    </v-avatar>
                    {{ data.item.name }}
                  </v-chip>
                </template>
                <template v-slot:item="data">
                  <template v-if="typeof data.item !== 'object'">
                    <v-list-item-content v-text="data.item"></v-list-item-content>
                  </template>
                  <template v-else>
                    <v-list-item-avatar color="grey">
                      <v-img :src="'storage/profile_photo/' + data.item.profile.photo"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title v-html="data.item.name"></v-list-item-title>
                      <v-list-item-subtitle v-html="data.item.group"></v-list-item-subtitle>
                    </v-list-item-content>
                  </template>
                </template>
              </v-autocomplete>
              <v-text-field
                v-model="messageForm.subject"
                label="Subject"
                :prefix="reply ? 'Re: ' : 'Fwd: '"
                prepend-icon="mdi-note-outline"
                single-line
                hide-details
              ></v-text-field>
              <v-textarea
                v-model="messageForm.content"
                rows="10"
                label="Message"
                counter
                maxlength="800"
                single-line
                dense
              ></v-textarea>
              <v-btn type="submit" color="success" absolute right class="mt-2">
                <v-icon left>mdi-send</v-icon>Send
              </v-btn>
              <div class="form-group">
                <div class="btn btn-outline-success btn-file">
                  <i class="fas fa-paperclip"></i> Attachment
                  <input type="file" name="attachment" />
                </div>
                <p class="help-block small">Max. 32MB</p>
              </div>
            </v-form>
          </v-card>
        </v-bottom-sheet>
      </div>

      <v-divider></v-divider>
      <v-card-text>
        <p>{{getMessage.content}}</p>
        <div v-if="getMessage.attachment" class="mt-2">
          <v-chip
            v-for="(file, index) in getMessage.attachment"
            :key="index"
            @click.prevent="downloadFile(getMessage.id, file.filename)"
            class="ma-2"
          >
            <v-icon left>mdi-paperclip</v-icon>
            {{file.filename}}
          </v-chip>
        </div>
      </v-card-text>

      <v-card-actions>
        <v-btn dark small color="danger" @click="deleteMessage(getMessage)">
          <v-icon left>mdi-delete</v-icon>Delete
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn small @click="replyToMessage(getMessage)">
          <v-icon left>mdi-reply</v-icon>Reply
        </v-btn>
        <v-btn small @click="forwardMessage(getMessage)">
          <v-icon left>mdi-share</v-icon>Forward
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-container>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  data() {
    return {
      sheet: false,
      label: "",
      reply: false,

      messageForm: new Form({
        receiver: "",
        subject: "",
        content: "",
        attachment: ""
      })
    };
  },

  watch: {
    sheet(val) {
      !val && this.messageForm.reset();
    }
  },

  computed: {
    ...mapGetters(["getMessage", "allFollowingUsers"])
  },

  methods: {
    ...mapActions(["addMessage", "deleteOneInbox"]),

    forceFileDownload(response) {
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement("a");
      link.href = url;
      document.body.appendChild(link);
      link.click();
    },

    downloadFile(id, file) {
      let downloadUrl = "storage/message_attachments/" + id + "/";
      axios
        .get(downloadUrl + file, { responseType: "arraybuffer" })
        .then(response => {
          let blob = new Blob([response.data]);
          let link = document.createElement("a");
          link.href = window.URL.createObjectURL(blob);
          link.download = file;
          link.click();
        });
    },

    replyToMessage(message) {
      this.reply = true;
      this.label = "Reply Message";
      this.messageForm.receiver = new Array(this.getMessage.sender);
      this.messageForm.subject = message.subject;
      this.messageForm.content =
        "On " +
        moment(message.created_at).format("lll") +
        " " +
        message.sender.name +
        "s wrote: " +
        "\n \n" +
        message.content;
      this.sheet = true;
    },

    forwardMessage(message) {
      this.reply = false;
      this.label = "Forward Message";
      this.messageForm.subject = message.subject;
      this.messageForm.content =
        "On " +
        moment(message.created_at).format("lll") +
        " " +
        message.sender.name +
        "s wrote: " +
        "\n \n" +
        message.content;
      this.sheet = true;
    },

    sendMessage() {
      this.addMessage(this.messageForm).then(() => {
        Toast.fire({
          type: "success",
          title: "Message sent!"
        });
        this.messageForm.reset();
        this.$router.push("/mailbox");
      });
    },

    remove(item) {
      const index = this.messageForm.receiver.indexOf(item);
      if (index >= 0) this.messageForm.receiver.splice(index, 1);
    },

    deleteMessage(message) {
      this.deleteOneInbox(message).then(() => {
        this.$router.push("/mailbox");
        Toast.fire({
          type: "success",
          title: "Message deleted!"
        });
      });
    }
  }
};
</script>
