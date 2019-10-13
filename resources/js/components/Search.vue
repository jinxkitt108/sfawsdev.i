<template>
  <v-container fluid>
    <v-card>
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
  </v-container>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
  data() {
    return {
      experts: ""
    };
  },
  created() {
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
