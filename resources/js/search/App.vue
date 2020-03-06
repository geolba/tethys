<template v-if="loaded">
  <div class="search-container row">
    <div class="four columns left-bar">
      <div id="left-bar" class="sidebar left-bar">
        <div class="sidebar-image"></div>
        <!-- <h2 class="indexheader">DataXplore</h2> -->

        <!-- <div class="card" v-for="item in facets.language" :key="item.id">
          <span>{{ item }}</span>     
        </div>-->

        <!-- <facet-list v-bind:data="facets"></facet-list> -->
        <!-- <div class="card" v-for="(item, index) in facets" :key="index"> -->
        <div class="card" v-for="(values, key, index) in facets" :key="index">
          <facet-category :data="values" :filterName="key" @filter="onFilter"></facet-category>
        </div>
      </div>
    </div>

    <div class="eight columns right-bar">
      <div id="right-bar" class="sidebar right-bar">
        <!-- Search input section -->
        <div class="row">
          <div class="twelve columns">
            <vs-input @search="onSearch"  title="searching solr datasets"    placeholder="Enter your search term..."></vs-input>
          </div>
        </div>

        <div v-if="results.length > 0" class="result-list-info">
          <div class="resultheader">
            Your search yielded
            <strong>{{ numFound }}</strong> results:
          </div>
        </div>

        <div class="row">
          <!-- <div class="active-filter-items twelve columns">
            <a class="filter-link" v-for="(value, key, index) in activeFilterItems" :key="index">
              <span>{{ key + ": " }}</span>
              <span v-if="value && value.length > 0">{{ value.join(', ') }}</span>
            </a>
          </div> -->
            <div class="twelve columns">
              <span class="active-filter-items" v-for="(values, key, index) in activeFilterCategories" :key="index">    
                  <active-facet-category :data="values" :categoryName="key" @clearFacetCategory="onClearFacetCategory"></active-facet-category>              
              </span>
            </div>
      
         
        </div>

        <!-- Results section -->
        <vs-results v-bind:data="results"></vs-results>

        <!-- pagination -->
        <div class="row">
          <div class="twelve columns">
            <vs-pagination v-bind:data="pagination" @paginate="onPaginate"></vs-pagination>
          </div>
        </div>
      </div>
    </div>  <!-- end eight columns -->

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

<script lang="ts">
import App from "./app-class";
export default App;
</script>

<style lang="scss">
#app {
  color: #56b983;
}

.content {
    flex: 1 1 70%;
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>