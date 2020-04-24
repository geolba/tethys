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

    <section class="normal result-list-container">
      
      <div v-for="document in results" :key="document.id" class="row record-elem">
        <div  class="twelve columns post">
         
            <span class="label label-info" data-container="div" data-title="Publication date">
              {{ convert(document.server_date_published) }}
            </span>
            <span class="label label-default ng-binding">{{ document.doctype }}</span>
            <span class="label label-success titlecase">Open Access</span>


            <h4>
              <a target="_self" v-bind:href="'dataset/' + document.id" class="ng-binding">
                {{ document.title_output }}
              </a>
            </h4>

            
              <p v-if="document.author && document.author.length > 0">
                <!-- <span>Author: {{ document.author.join(', ') }}</span> -->
                <span v-for="(author,index) in document.author" :key="index">{{ author }}; </span>
              </p>


              <!-- <p v-if="document.title_additional && document.title_additional.length > 0">
                <span>Additional Title:{{ document.title_additional.join(', ') }}</span>
              </p> -->

              <p class="clamped clamped-2">
                <span class="text">
                  Abstract: {{ document.abstract_output }}
                  <span class="ellipsis">...</span>
                  <span class="fill"></span>
                </span>
              </p>

              <!-- <div class="css-subject" v-if="document.subject && document.subject.length > 0"> -->
              <span class="label label-keyword titlecase" v-for="(item, index) in document.subject" :key="index">
                #{{ item }}
              </span>
              <!-- <p>
                <small class="text-muted hidden-xs ng-binding">
                  Uploaded on March 15, 2019
                </small>
              </p>           -->

        </div>
      </div>
    </section>
  </div>
</template>

<script lang="ts">
  import VgResults from "./vs-results-class";
  export default VgResults;
</script>