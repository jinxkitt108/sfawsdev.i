<style lang="scss" scoped>
</style>

<template>
  <v-container>
      <v-avatar color="blue" size="100">
          <v-icon>mdi-mail</v-icon>
      </v-avatar>
  </v-container>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
  created() {
    this.fetchFollowingUsers();
  },

  data() {
    return {
      search: null,

      headers: [
        { text: "Name", value: "name" },
        { text: "Type", value: "type" },
        { text: "USER", value: "username" },
        { text: "Email", value: "email" }
      ]
    };
  },

  computed: {
    ...mapGetters(["allFollowingUsers"])
  },

  methods: {
    ...mapActions(["fetchFollowingUsers"])
  }
};
</script>
