<template>
  <div class="sidebar-simplesearch">
    <!-- <form method="GET" action="//repository.geologie.ac.at/search" accept-charset="UTF-8"> -->
    <div>
      <input
        class="search-input"
        placeholder="Enter your search term..."
        name="q"
        type="text"
        v-model="term" v-on:keyup.enter="search()"
      />

      <!-- <button @click="search()" class="css-1gklxk5 ekqohx90"> -->
      <button class="css-1gklxk5 ekqohx90">
        <svg
          alt="Search"
          @click="search()"
          class="search-icon"
          height="14"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 15 15"
        >
          <title>Search</title>
          <path
            d=" M6.02945,10.20327a4.17382,4.17382,0,1,1,4.17382-4.17382A4.15609,4.15609, 0,0,1,6.02945,10.20327Zm9.69195,4.2199L10.8989,9.59979A5.88021,5.88021, 0,0,0,12.058,6.02856,6.00467,6.00467,0,1,0,9.59979,10.8989l4.82338, 4.82338a.89729.89729,0,0,0,1.29912,0,.89749.89749,0,0,0-.00087-1.29909Z "
          />
        </svg>
      </button>
    </div>
  </div>
</template>


<script lang="ts">
// import Vue from "vue";
// import { Component, Provide } from "vue-property-decorator";

// @Component({})
// export default class VsInput extends Vue {
//   term = "";  
//   search() {
//     this.$emit("search", this.term);
//   }
// }
import VsInput from "./vs-input-class";
export default VsInput;
</script>