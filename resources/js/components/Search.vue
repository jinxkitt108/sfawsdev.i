<template>
  <v-container fluid>
    <v-card v-if="searchMode">
      <v-card-title class="font-weight-bold mb-2 pa-3">People</v-card-title>
      <v-card-text v-for="user in users.data" :key="user.id">
        <v-row>
          <v-col cols="12" md="3">
            <v-avatar class="profile mr-3" color="grey" size="150" tile>
              <v-img :src="'storage/profile_photo/' + user.profile.photo"></v-img>
            </v-avatar>
          </v-col>
          <v-col cols="12" md="4">
            <span class="subtitle-1 text--primary">{{user.name | capitalize}}</span>
            <v-spacer></v-spacer>
            <span class="small text--primary">{{user.type}}</span>
            <v-spacer></v-spacer>
            <span class="text--primary mr-3">
              Followers
              <strong>{{user.followers}}</strong>
            </span>
            <span class="text--primary">
              Followings
              <strong>{{user.followings}}</strong>
            </span><br>
            <v-btn
              :id="'follow-' + user.id"
              v-show="!user.current_user"
              @click="toggleFollow(user.id)"
              rounded
              :class="{'primary': !user.isFollowing}"
              small
            >{{user.isFollowing ? 'Unfollow' : 'Follow'}}</v-btn>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <v-card v-else>
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
                  :id="'follow-' + expert.id"
                  @click="toggleFollow(expert.id)"
                  rounded
                  :class="{'primary': !expert.isFollowing}"
                  small
                >{{expert.isFollowing ? 'Unfollow' : 'Follow'}}</v-btn>
              </v-card-text>
            </v-card>
          </slide>
        </carousel>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
  data() {
    return {
      experts: "",
      users: "",
      searchMode: false
    };
  },
  created() {
    Fire.$on("searching", query => {
      this.searchMode = true;
      axios
        .get("api/searchUser?q=" + query)
        .then(data => {
          this.$store.commit("search", data.data);
          this.users = this.$store.getters.users;
        })
        .catch(() => {});
    });
    this.loadExperts();
  },
  methods: {
    loadExperts() {
      axios.get("api/experts").then(({ data }) => (this.experts = data.data));
    },
    toggleFollow(id) {
      let reference = $(this);
      axios
        .post("api/follow", {
          user_id: id
        })
        .then(response => {
          if (response.data.attached.length) {
            $("#follow-" + id)
              .text("Unfollow")
              .removeClass("primary");
          } else {
            $("#follow-" + id)
              .text("Follow")
              .addClass("primary");
          }
          this.loadExperts();
          Toast.fire({
            type: "success",
            title: "Successful!"
          });
        });
    }
  }
};
</script>
