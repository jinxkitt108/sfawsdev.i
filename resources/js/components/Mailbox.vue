<template>
  <v-container>
    <v-card flat>
      <v-tabs v-model="tab" icons-and-text>
        <v-tab>
          <v-icon left>mdi-inbox-arrow-down</v-icon>Inbox
        </v-tab>
        <v-tab>
          <v-icon left>mdi-email-send</v-icon>Sent
        </v-tab>
        <v-spacer class="d-none d-lg-block ml-1"></v-spacer>
        <v-tab class="v-tab--active">
          <v-btn color="success">
            <v-icon left>mdi-email-plus</v-icon>
            <span class="d-none d-lg-block">Compose</span>
          </v-btn>
        </v-tab>

        <v-tabs-items v-model="tab">
          <v-tab-item>
            <v-card flat class="d-lg-none">
              <v-data-iterator :items="getInboxMessages" :search="search">
                <template v-slot:header>
                  <v-toolbar flat>
                    <v-toolbar-title class="subtitle-1 font-weight-black">INBOX</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-text-field
                      v-model="search"
                      append-icon="mdi-magnify"
                      label="Search message.."
                      single-line
                      hide-details
                    ></v-text-field>
                  </v-toolbar>
                </template>

                <template v-slot:default="props">
                  <div v-for="item in props.items" :key="item.id" class="mb-2">
                    <v-hover v-slot:default="{ hover }">
                      <v-card
                        :elevation="hover ? 5 : 0"
                        flat
                        v-ripple
                        class="mb-2"
                        :class="item.read ? '' : ' primary lighten-1'"
                      >
                        <v-list-item three-line>
                          <v-list-item-avatar size="35" color="grey">
                            <v-img
                              :src="'storage/profile_photo/' + item.message.sender.profile.photo"
                            ></v-img>
                          </v-list-item-avatar>
                          <v-list-item-content :class="item.read ? '' : 'white--text'">
                            <v-list-item-title
                              class="body-2"
                              :class="item.read ? '' : ' font-weight-bold'"
                            >
                              <span
                                class="small float-right"
                                :class="item.read ? '' : ' font-weight-bold'"
                              >{{item.message.created_at | sinceDate}}</span>
                              {{item.message.sender.name}}
                            </v-list-item-title>
                            <v-list-item-title
                              class="caption mt-1"
                              :class="item.read ? '' : ' font-weight-black'"
                            >{{limitStr(item.message.subject)}}</v-list-item-title>
                            <v-list-item-subtitle
                              class="caption"
                              :class="item.read ? '' : ' white--text'"
                            >{{limitStr(item.message.content)}}</v-list-item-subtitle>
                          </v-list-item-content>
                        </v-list-item>
                      </v-card>
                    </v-hover>
                  </div>
                </template>
              </v-data-iterator>
            </v-card>

            <v-card class="d-none d-lg-block" flat>
              <v-data-table
                v-model="selected_inbox"
                :items="getInboxMessages"
                :headers="sentHeaders"
                show-select
                item-key="id"
                :search="search"
                class="elevation-1"
                @click:row="viewMessage"
              >
                <template v-slot:top>
                  <v-toolbar flat>
                    <v-toolbar-title class="subtitle-1 font-weight-black">Inbox</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-text-field
                      v-model="search"
                      append-icon="mdi-magnify"
                      label="Search message.."
                      single-line
                      hide-details
                    ></v-text-field>
                  </v-toolbar>
                  <v-toolbar v-if="selected_inbox.length > 0" flat dense height="30">
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn icon color="danger" @click="deleteInboxItem" v-on="on">
                          <v-icon>mdi-delete</v-icon>
                        </v-btn>
                      </template>
                      <span>Delete</span>
                    </v-tooltip>
                  </v-toolbar>
                </template>

                <template v-slot:item.avatar="{ item }">
                  <v-avatar size="30">
                    <v-img :src="'storage/profile_photo/' + item.message.sender.profile.photo"></v-img>
                  </v-avatar>
                </template>

                <template v-slot:item.name="{ item }">
                  <span class="subtitle-2 font-weight-bold">{{item.message.sender.name}}</span>
                </template>

                <template v-slot:item.content="{ item }">{{limitStr(item.message.content)}}</template>

                <template v-slot:item.attachment="{ item }">
                  <v-icon v-show="item.message.attachment">mdi-paperclip</v-icon>
                </template>

                <template v-slot:item.created_at="{ item }">
                  <span class="font-weight-bold small">{{item.message.created_at | sinceDate}}</span>
                </template>
              </v-data-table>
            </v-card>
          </v-tab-item>

          <v-tab-item>
            <v-card flat class="d-lg-none">
              <v-data-iterator :items="getSentMessages" :search="search">
                <template v-slot:header>
                  <v-toolbar flat>
                    <v-toolbar-title class="subtitle-1 font-weight-black">SENT</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-text-field
                      v-model="search"
                      append-icon="mdi-magnify"
                      label="Search message.."
                      single-line
                      hide-details
                    ></v-text-field>
                  </v-toolbar>
                </template>

                <template v-slot:default="props">
                  <div v-for="item in props.items" :key="item.id" class="mb-2">
                    <v-hover v-slot:default="{ hover }">
                      <v-card :elevation="hover ? 5 : 0" flat v-ripple>
                        <v-list-item three-line>
                          <v-list-item-avatar>
                            <v-badge
                              v-if="item.messageables.length > 1"
                              bottom
                              overlap
                              color="white"
                            >
                              <template v-slot:badge>
                                <span
                                  class="black--text font-weight-black"
                                >&plus;{{item.messageables.length - 1}}</span>
                              </template>
                              <v-avatar size="35" color="grey">
                                <v-img
                                  :src="'storage/profile_photo/' + item.messageables[0].receiver.profile.photo"
                                ></v-img>
                              </v-avatar>
                            </v-badge>
                            <v-avatar v-else size="35" color="grey">
                              <v-img
                                :src="'storage/profile_photo/' + item.messageables[0].receiver.profile.photo"
                              ></v-img>
                            </v-avatar>
                          </v-list-item-avatar>
                          <v-list-item-content>
                            <v-list-item-title
                              v-if="item.messageables.length <= 2"
                              class="body-2 font-weight-bold"
                            >
                              <span class="small float-right">{{item.created_at | sinceDate}}</span>
                              <span
                                v-for="user in item.messageables"
                                :key="user.id"
                                class="subtitle-2 font-weight-bold"
                              >
                                {{user.receiver.name}}
                                <span
                                  v-show="item.messageables.indexOf(user) < (item.messageables.length - 1)"
                                  class="mr-1"
                                >,</span>
                              </span>
                            </v-list-item-title>
                            <v-list-item-title v-else class="body-2 font-weight-bold">
                              <span class="small float-right">{{item.created_at | sinceDate}}</span>
                              <span class="subtitle-2 font-weight-bold">
                                {{item.messageables[0].receiver.name}}, {{item.messageables[1].receiver.name}},
                                <span
                                  class="success--text"
                                >&plus; {{item.messageables.length}} more...</span>
                              </span>
                            </v-list-item-title>
                            <v-list-item-title
                              class="caption mt-1 font-weight-black"
                            >{{limitStr(item.subject)}}</v-list-item-title>
                            <v-list-item-subtitle class="caption">{{limitStr(item.content)}}</v-list-item-subtitle>
                          </v-list-item-content>
                        </v-list-item>
                      </v-card>
                    </v-hover>
                  </div>
                </template>
              </v-data-iterator>
            </v-card>

            <v-card flat class="d-none d-lg-block">
              <v-data-table
                v-model="selected_sent"
                :items="getSentMessages"
                :headers="sentHeaders"
                show-select
                item-key="id"
                :search="search"
                class="elevation-1"
              >
                <template v-slot:top>
                  <v-toolbar flat>
                    <v-toolbar-title class="subtitle-1 font-weight-black">Sent</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-text-field
                      v-model="search"
                      append-icon="mdi-magnify"
                      label="Search message.."
                      single-line
                      hide-details
                    ></v-text-field>
                  </v-toolbar>
                  <v-toolbar v-if="selected_sent.length > 0" flat dense height="30">
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn icon color="danger" @click="deleteSentItem" v-on="on">
                          <v-icon>mdi-delete</v-icon>
                        </v-btn>
                      </template>
                      <span>Delete</span>
                    </v-tooltip>
                  </v-toolbar>
                </template>

                <template v-slot:item.avatar="{ item }">
                  <v-badge v-if="item.messageables.length > 1" bottom overlap color="white">
                    <template v-slot:badge>
                      <span
                        class="black--text font-weight-black"
                      >&plus;{{item.messageables.length - 1}}</span>
                    </template>
                    <v-avatar size="30">
                      <v-img
                        :src="'storage/profile_photo/' + item.messageables[0].receiver.profile.photo"
                      ></v-img>
                    </v-avatar>
                  </v-badge>
                  <v-avatar v-else size="30">
                    <v-img
                      :src="'storage/profile_photo/' + item.messageables[0].receiver.profile.photo"
                    ></v-img>
                  </v-avatar>
                </template>

                <template v-slot:item.name="{ item }">
                  <div v-if="item.messageables.length <= 2">
                    <span
                      v-for="user in item.messageables"
                      :key="user.id"
                      class="subtitle-2 font-weight-bold"
                    >
                      {{user.receiver.name}}
                      <span
                        v-show="item.messageables.indexOf(user) < (item.messageables.length - 1)"
                        class="mr-1"
                      >,</span>
                    </span>
                  </div>
                  <div v-else>
                    <span
                      class="subtitle-2 font-weight-bold"
                    >{{item.messageables[0].receiver.name}},</span>
                    <em class="font-weight-bold small">&plus;{{item.messageables.length - 1}} more</em>
                  </div>
                </template>

                <template v-slot:item.content="{ item }">{{limitStr(item.content)}}</template>

                <template v-slot:item.attachment="{ item }">
                  <v-icon v-show="item.attachment">mdi-paperclip</v-icon>
                </template>

                <template v-slot:item.created_at="{ item }">
                  <span class="font-weight-bold small">{{item.created_at | sinceDate}}</span>
                </template>
              </v-data-table>
            </v-card>
          </v-tab-item>

          <v-tab-item>
            <v-card flat>
              <v-form @submit.prevent="sendMessage" class="pa-3">
                <v-autocomplete
                  id="receiver"
                  v-model="messageForm.receiver"
                  :items="allFollowingUsers"
                  chips
                  label="To"
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
                  id="subject"
                  v-model="messageForm.subject"
                  label="Subject"
                  prepend-icon="mdi-note-outline"
                  single-line
                  hide-details
                ></v-text-field>
                <v-textarea
                  id="content"
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
                    <input ref="attachments" type="file" multiple @change="uploadFiles" />
                  </div>
                  <p class="help-block small">Max. 32MB</p>
                </div>
                <div class="d-flex justify-start">
                  <span v-for="(file, index) in attachments" :key="index">
                    <v-chip class="mr-1" close @click:close="removeFile(file)">{{file.name}}</v-chip>
                  </span>
                </div>
              </v-form>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-tabs>
    </v-card>
  </v-container>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data() {
    return {
      tab: null,
      search: "",
      selected_sent: [],
      selected_inbox: [],
      attachments: [],
      form: new FormData(),

      //Sent Headers
      sentHeaders: [
        { text: "Avatar", sortable: false, filterable: false, value: "avatar" },
        { text: "Name", value: "name", sortable: false, filterable: true },
        { text: "Content", sortable: false, value: "content" },
        {
          text: "Attachment",
          sortable: false,
          filterable: false,
          align: "center",
          value: "attachment"
        },
        { text: "Date", filterable: false, value: "created_at" }
      ],

      messageForm: new Form({
        receiver: "",
        subject: "",
        content: "",
        attachments: []
      })
    };
  },

  computed: {
    ...mapGetters(["allFollowingUsers", "getSentMessages", "getInboxMessages"])
  },

  methods: {
    ...mapActions([
      "addMessage",
      "fetchSentMessages",
      "fetchInboxMessages",
      "fetchMessage",
      "deleteInboxMessage",
      "deleteSentMessage"
    ]),

    viewMessage(item) {
      this.fetchMessage(item.message_id).then(() => {
        this.$router.push("/view-message");
      });
    },

    deleteInboxItem() {
      //   Swal.fire({
      //     title: "Are you sure?",
      //     text: "You won't be able to revert this!",
      //     type: "warning",
      //     showCancelButton: true,
      //     confirmButtonColor: "teal",
      //     cancelButtonColor: "#d33",
      //     confirmButtonText: "Yes, delete it!"
      //   }).then(result => {
      //     // Send request to the server
      //     if (result.value) {
      this.deleteInboxMessage(this.selected_inbox).then(() => {
        this.selected_inbox = [];
      });
      //         .then(() => {
      //           Toast.fire({
      //             type: "success",
      //             title: "Post Deleted!"
      //           });
      //         })
      //         .catch(() => {
      //           Swal.fire({
      //             type: "error",
      //             title: "Oops...",
      //             text: "Something went wrong!"
      //           });
      //         });
      //     }
      //   });
    },

    deleteSentItem() {
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
          this.deleteSentMessage(this.selected_sent)
            .then(() => {
              this.selected_sent = [];
              Toast.fire({
                type: "success",
                title: "Post Deleted!"
              });
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

    limitStr(text) {
      let str = text;
      let limit = 35;
      if (typeof str === "string" && str.length > limit) {
        str = str.slice(0, limit) + "...";
      }
      return str;
    },

    remove(item) {
      const index = this.messageForm.receiver.indexOf(item);
      if (index >= 0) this.messageForm.receiver.splice(index, 1);
    },

    removeFile(file) {
      const index = this.attachments.indexOf(file);
      if (index >= 0) this.attachments.splice(index, 1);
    },

    uploadFiles() {
      let files = this.$refs.attachments.files;

      if (!files.length) {
        return false;
      }

      for (let i = 0; i < files.length; i++) {
        this.attachments.push(files[i]);
      }
    },

    clear() {
      const input = this.$refs.attachments;
      input.type = "text";
      input.type = "file";
    },

    sendMessage(e) {
      for (let i = 0; i < this.attachments.length; i++) {
        this.form.append("attachments[]", this.attachments[i]);
      }
      this.form.append("subject", this.messageForm.subject);
      this.form.append("content", this.messageForm.content);
      if (this.messageForm.receiver.length > 1) {
        for (let i = 0; i < this.messageForm.receiver.length; i++) {
          this.form.append("receiver[]", this.messageForm.receiver[i].id);
        }
      } else {
        this.form.append("receiver", this.messageForm.receiver[0].id);
      }

      this.addMessage(this.form).then(() => {
        Toast.fire({
          type: "success",
          title: "Message sent!"
        });
        this.clear();
        this.form = new FormData();
        this.attachments = [];
        this.messageForm.reset();
        this.fetchSentMessages();
      });
    }
  },

  created() {
    this.fetchInboxMessages();
    this.fetchSentMessages();
  }
};
</script>
