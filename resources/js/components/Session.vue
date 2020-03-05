<template>
  <v-container fluid>
    <v-card>
      <!-- TimeSlot Availability -->
      <v-card-text>
        <v-card flat tile width="350">
          <v-card-actions>
            <!-- Set New Time Slot -->
            <v-dialog v-model="dialog" width="400">
              <template v-slot:activator="{ on }">
                <v-btn v-on="on" small rounded outlined color="success">
                  <v-icon left>mdi-plus</v-icon>Time Slot
                </v-btn>
              </template>
              <v-card>
                <v-toolbar dense flat height="50">
                  <v-btn @click="close" icon>
                    <v-icon>mdi-close</v-icon>
                  </v-btn>
                  <v-toolbar-title>Time Slot Availability</v-toolbar-title>
                </v-toolbar>
                <v-form @submit.prevent="saveTimeSlot">
                  <v-card-text>
                    <v-select
                      v-model="time_slot.day"
                      label="Day"
                      :items="['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ]"
                      outlined
                      dense
                      rounded
                    ></v-select>
                    <div class="d-flex">
                      <v-text-field
                        v-model="time_slot.from"
                        label="From"
                        type="time"
                        outlined
                        dense
                        rounded
                      ></v-text-field>
                      <v-spacer></v-spacer>
                      <v-text-field
                        v-model="time_slot.to"
                        label="To"
                        type="time"
                        outlined
                        dense
                        rounded
                      ></v-text-field>
                    </div>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="close" small>Close</v-btn>
                    <v-btn type="submit" small>Save</v-btn>
                  </v-card-actions>
                </v-form>
              </v-card>
            </v-dialog>
          </v-card-actions>

          <v-subheader class="font-weight-bold">Available Time Slot</v-subheader>
          <v-simple-table fixed-header>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">Day</th>
                  <th colspan="2" class="text-center">Time</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in getTimeSlots" :key="item.id">
                  <td>{{item.day}}</td>
                  <td class="text-center">{{'2020-01-01 ' + item.from | shortTime}} - {{'2020-01-01 ' + item.to | shortTime}}</td>
                  <td>
                    <v-btn icon small @click="deletingTimeSlot(item.id)">
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-card>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  created() {
    this.fetchTimeSlots();
  },

  computed: {
    ...mapGetters(["getTimeSlots"])
  },

  data() {
    return {
      dialog: false,
      time_slot: new Form({
        day: "",
        from: "",
        to: ""
      })
    };
  },

  methods: {
    ...mapActions(["addTimeSlot", "fetchTimeSlots", "deleteTimeSlot"]),

    deletingTimeSlot(id) {
      this.deleteTimeSlot(id).then(() => {
        Toast.fire({
          type: "warning",
          title: "Time slot deleted!"
        });
      });
    },

    close() {
      this.time_slot.reset();
      this.dialog = false;
    },

    saveTimeSlot() {
      this.addTimeSlot(this.time_slot).then(() => {
        this.close();
        Toast.fire({
          type: "success",
          title: "Time slot added!"
        });
      });
    }
  }
};
</script>
