<template>
  <div>
    <!-- <div>{{ bar }}</div> -->
    <!-- Search input section -->
    <div class="row">
      <div class="twelve columns">
        <vs-input @search="onSearch"></vs-input>
      </div>
    </div>

    <!-- Results section -->
    <div class="results">
      <div class="card" v-for="item in results" :key="item.id">
        <img
          v-if="item.thumb"
          class="card-img-top"
          :src="item.thumb"
          :alt="item.title"
          @error="error(item)"
        />
        <div class="card-body">
          <h5 class="card-title">{{item.name}}</h5>
          <!-- <p class="card-text" v-html="truncate(item.description || item.abstract, 50)"></p> -->
        </div>
      </div>
    </div>

  </div>
</template>

<script lang="js">
  import App from './app-class.js';
  export default App;
</script>

<style lang="scss">
#app {
  color: #56b983;
}
</style>