<template>
  <div class="container">
    <v-card>
      <v-card-title>
        <h2 class="font-weight-bold basil--text">
          Build A Store
          <v-btn outlined absolute right to="/marketplace">
            <v-icon class="mr-2">mdi-arrow-left-circle</v-icon>Go to SFAWS Market
          </v-btn>
        </h2>
      </v-card-title>
      <form role="form">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="exampleInputEmail1">Name</label>
                <input type="text" class="form-control" placeholder="Enter Name" />
              </div>
            </div>
            <div class="col-sm-6">
              <v-file-input
                :rules="rules"
                accept="image/png, image/jpeg, image/bmp"
                placeholder="Pick a cover photo."
                prepend-icon="mdi-camera"
                label="Store Photo"
              ></v-file-input>
            </div>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Address</label>
            <input type="text" class="form-control" placeholder="Address" />
          </div>
          <v-textarea outlined name="input-7-4" label="Description"></v-textarea>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </v-card>
  </div>
</template>

<script>
export default {};
</script>
