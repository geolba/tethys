<template>
  <div  class="search-container row">

    <div class="four columns left-bar">
      <div id="left-bar" class="sidebar left-bar">
         <h2 class="indexheader">DataXplore</h2>
          
          <!-- <div class="card" v-for="item in facets.language" :key="item.id">
          <span>{{ item }}</span>     
        </div> -->

         <!-- <facet-list v-bind:data="facets"></facet-list> -->
          <div class="card" v-for="(valueArray, filterName, index) in facets" :key="index">
             <facet-list :data="valueArray" :filterName="filterName"></facet-list>
          </div>
      </div>
    </div>

    <div class="eight columns right-bar">
      <div id="right-bar" class="sidebar right-bar">
        <!-- Search input section -->

        <div class="row">
          <div class="twelve columns">
            <vs-input @search="onSearch"></vs-input>
          </div>
        </div>

        <!-- Results section -->
        <vs-results v-bind:data="results"></vs-results>

      
      </div>
    </div>
  </div>
  <!-- <div class="card" v-for="item in results" :key="item.id">
      <img
        v-if="item.thumb"
        class="card-img-top"
        :src="item.thumb"
        :alt="item.title"
        @error="error(item)"
      />
      <div class="card-body">
        <h5 class="card-title">{{item.name}}</h5>
        <p class="card-text" v-html="truncate(item.description || item.abstract, 50)"></p>
      </div>
    </div>
  </div>-->
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