<template>
  <div class="sidebar-simplesearch">
    <!-- <form method="GET" action="//repository.geologie.ac.at/search" accept-charset="UTF-8"> -->
     <!-- v-on:keyup.enter="search()" -->
    <div class="autocomplete__box">
      <input
        class="search-input"     
        name="q"
        type="text"
        v-model="display"       
        @input="searchChanged"
        v-bind:title="title"
        v-bind:placeholder="placeholder"
        v-on:keydown.down="onArrowDown" v-on:keydown.up="onArrowUp" v-on:keydown.enter="onEnter"  @keydown.tab="close"
        v-on:focus="focus"
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

      <!-- clearButtonIcon -->
      <!-- <span v-show="!isLoading" class="autocomplete__icon autocomplete--clear" @click="clear">       
        <img src="../assets/close.svg">
      </span> -->

      <ul class="autocomplete-results pure-u-23-24" v-show="showResults">
        <li class="loading" v-if="isLoading">Loading results...</li>

        <li
          v-else
          v-for="(result, key) in suggestions"
          :key="key"       
          class="autocomplete-result-item"         
          :class="{'is-active' : isSelected(key) }"
            @click.prevent="select(result)"
            ref="options"
        >
          <strong> {{ result }}</strong>
        </li>
      </ul>
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


<style>
.sidebar-simplesearch {
   position: relative;
  width: 100%;  
    box-sizing: border-box;
}

.autocomplete-results {
  padding: 0;
  margin: 0;
  border: 1px solid #eeeeee;
  list-style-type: none;
  z-index: 1000;
  position: absolute;
  max-height: 200px;
  overflow-y: auto;
  background: white;
  width: 100%;
  border: 1px solid #ccc;
  border-top: 0;
  color: black;
}

.autocomplete-result-item {
  list-style: none;
  text-align: left;
  padding: 7px 10px;
  cursor: pointer;
}

.autocomplete-result-item.is-active {
  background: rgba(0, 180, 255, 0.15);
}

.autocomplete-result-item:hover {
  background: rgba(0, 180, 255, 0.075); 
}
</style>
