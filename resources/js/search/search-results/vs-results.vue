<template>
  <!-- <div class="card-columns" >
    <div class="card" v-for="item in results" :key="item.id">
      <img v-if="item.thumb" class="card-img-top" :src="item.thumb" :alt="item.title" @error="error(item)">
      <div class="card-body">
        <h5 class="card-title">{{item.name}}</h5>
        <p class="card-text" v-html="truncate(item.description || item.abstract, 50)"></p>
      </div>
    </div>
  </div>-->
  <div class="results">

    <!-- <div class="result-list-info">
      <div class="resultheader">
        Your search yielded
        <strong>{{ results.length }}</strong> results:
      </div>
    </div> -->

    <section class="result-list-container">
      <div class="row">
        <ul class="search-items isotope js-isotope u-cf">
          <li v-for="document in results" :key="document.id" class="six columns post">
            <div class="search-detail">
              <div>
                <a
                  v-bind:href="'dataset/' + document.id"
                >{{ document.title_output }}</a>
              </div>
              {{ convert(document.server_date_published) }}

              <p v-if="document.title_additional && document.title_additional.length > 0">
                <em>Additional Title:{{ document.title_additional.join(', ') }}</em>
              </p>

              <div v-if="document.author && document.author.length > 0">
                <em>Author: {{ document.author.join(', ') }}</em>
              </div>

              <p class="clamped clamped-2">
                <span class="text">
                  Abstract: {{ document.abstract_output }}
                  <span class="ellipsis">...</span>
                  <span class="fill"></span>
                </span>
              </p>
              <div class="css-subject" v-if="document.subject && document.subject.length > 0">
                <div v-for="(item, index) in document.subject" :key="index" class="css-keyword">#{{ item }}</div>
                <!-- <div class="css-keyword">#graphql</div> -->
              </div>
            </div>
          </li>
        </ul>
      </div>
    </section>
  </div>
</template>

<script lang="ts">
import VgResults from "./vs-results-class";
export default VgResults;
</script>

