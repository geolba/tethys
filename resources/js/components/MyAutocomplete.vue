<!-- https://pineco.de/instant-ajax-search-laravel-vue/ 
https://alligator.io/vuejs/vue-autocomplete-component/ -->
<template>
    <div style="position:relative">
        <input type="search" @input="searchChanged" v-model="search" v-bind:readonly="isLoading == true" v-bind:title="title" v-bind:placeholder="title" 
        class="pure-u-23-24" v-on:keydown.down="onArrowDown" v-on:keydown.up="onArrowUp" v-on:keydown.enter="onEnter">
        <!-- <ul class="autocomplete-results" v-show="results.length > 0"> -->
        <ul class="autocomplete-results pure-u-23-24" v-show="isOpen"> 
             <li class="loading" v-if="isLoading" >Loading results...</li>

            <li 
                v-else
                v-for="(suggestion, i) in results" 
                :key="i" 
                @click="setResult(suggestion)"
                class="autocomplete-result"
                :class="{ 'is-active': i === arrowCounter }"
                >
                 <strong>{{ suggestion.full_name }}</strong>
            </li>
        </ul>
    </div>
</template>

<script>
import _ from 'lodash';
import axios from 'axios';

export default {
  //data from parent component
  props: {
    title: String
    // items: {
    //   type: Array,
    //   required: false,
    //   default: () => []
    // },
    // isAsync: {
    //   type: Boolean,
    //   required: false,
    //   default: false
    // }
  },
  data() {
    return {
      search: null,
      results: [],
      isOpen: false,
      isLoading: false,
      isAsync: true,
      items: [],
      arrowCounter: -1
    };
  },

  //   watch: {
  //     search(after, before) {
  //       this.isOpen = true;
  //       this.filterResults();
  //     }
  //   },

  watch: {
    // Once the items content changes, it means the parent component
    // provided the needed data
    items: function(value, oldValue) {
      // we want to make sure we only do this when it's an async request
      if (this.isAsync) {
        this.results = value;
        this.isOpen = true;
        this.isLoading = false;        
      } else {
        if (value.length !== oldValue.length) {
          this.results = value;
          this.isLoading = false;
        }
      }
    }
  },

  methods: {
    setResult(person) {
      // this.search = person.full_name;
      this.reset();
      this.$emit("person", person);
    },
    reset() {
      this.search = "";
      this.results = [];
      this.isOpen = false;
    },
    searchChanged() {
      // Let's warn the parent that a change was made
      this.$emit("input", this.search);

      if (this.search.length >= 2) {
        // Is the data given by an outside ajax request?
        if (this.isAsync) {
          this.isLoading = true;
          this.filterResults();
        } else {
          // Data is sync, we can search our flat array
          this.results = this.items.filter(item => {
            return item.toLowerCase().indexOf(this.search.toLowerCase()) > -1;
          });
          this.isOpen = true;
        }
      } else {
        this.items = [];
      }
    },
    filterResults: _.debounce(function() {
      var self = this;
      axios
        .get("/api/persons", { params: { filter: this.search.toLowerCase() } })
        .then(function(response) {
          return (self.items = response.data.data);
        })
        .catch(function(error) {
          alert(error);
        });
      // this.results = this.items.filter(item => {
      //   return item.toLowerCase().indexOf(this.search.toLowerCase()) > -1;
      // });
    }, 300),
    onArrowDown() {
      if (this.arrowCounter < this.results.length - 1) {
        this.arrowCounter = this.arrowCounter + 1;
      }
    },
    onArrowUp() {
      if (this.arrowCounter > 0) {
        this.arrowCounter = this.arrowCounter - 1;
      }
    },
    onEnter() {
      if(Array.isArray(this.results) && this.results.length && this.arrowCounter !== -1 && this.arrowCounter < this.results.length){
        //this.search = this.results[this.arrowCounter];
        var person = this.results[this.arrowCounter];
        this.$emit("person", person);
        //this.isOpen = false;
        this.reset();
        this.arrowCounter = -1;
      }     
    }
  },
  computed: {
    // isOpen() {
    //   return this.results.length > 0;
    // }
  }
};
</script>

<style>
.autocomplete-results {
  padding: 0;
  margin: 0;
  border: 1px solid #eeeeee;
  height: 120px;
  overflow: auto;
}

.autocomplete-result {
  list-style: none;
  text-align: left;
  padding: 4px 2px;
  cursor: pointer;
}

.autocomplete-result.is-active,
.autocomplete-result:hover {
  background-color: #4aae9b;
  color: white;
}
</style>