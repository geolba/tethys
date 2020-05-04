<!-- https://pineco.de/instant-ajax-search-laravel-vue/ 
https://alligator.io/vuejs/vue-autocomplete-component/ -->
<template>
  <div style="position:relative">
    <input
      type="search"
      @input="searchChanged"
      v-model="display"
      v-bind:readonly="isLoading == true"
      v-bind:title="title"
      v-bind:placeholder="title"
      class="pure-u-23-24"
      v-on:keydown.down="onArrowDown"
      v-on:keydown.up="onArrowUp"
      v-on:keydown.enter="onEnter"
    />
    <!-- <ul class="autocomplete-results" v-show="results.length > 0"> -->
    <ul class="autocomplete-results pure-u-23-24" v-show="showResults">
      <li class="loading" v-if="isLoading">Loading results...</li>

      <li
        v-else
        v-for="(suggestion, i) in results"
        :key="i"
        @click.prevent="setResult(suggestion)"
        ref="options"
        class="autocomplete-result"
        :class="{ 'is-active': i === selectedIndex }"
      >
        <strong>{{ suggestion.full_name }}</strong>
      </li>
    </ul>
  </div>
</template>

<script lang="ts">
import Vue from "vue";
import debounce from "lodash/debounce";
import axios from "axios";
import { Component, Prop, Watch } from "vue-property-decorator";

@Component({})
export default class MyAutocomplete extends Vue {
  //data from parent component
  @Prop()
  title: string;

  display: string = "";
  value = null;
  error: string = null;
  suggestions: Array<any> = [];
  isOpen = false;
  // isLoading = false;
  loading: boolean = false;
  isAsync: boolean = true;
  results: Array<any> = [];
  selectedIndex: number = null;
  selectedDisplay = null;

  get showResults(): boolean {
    return this.results.length > 0;
  }
  get noResults(): boolean {
    return Array.isArray(this.results) && this.results.length === 0;
  }
  get isLoading(): boolean {
    return this.loading === true;
  }
  get hasError(): boolean {
    return this.error !== null;
  }

  @Watch("results")
  onResultsChanged(value: Array<string>, oldValue: Array<string>) {
    // we want to make sure we only do this when it's an async request
    if (this.isAsync) {
      this.suggestions = value;
      this.isOpen = true;
      this.loading = false;
    } else {
      if (value.length !== oldValue.length) {
        this.suggestions = value;
        this.loading = false;
      }
    }
  }

  setResult(person) {
    // this.search = person.full_name;
    this.clear();
    this.$emit("person", person);
  }

  clear() {
    this.display = "";
    this.results = [];
    this.error = null;
    this.$emit("clear");
  }

  searchChanged() {
    // Let's warn the parent that a change was made
    this.$emit("input", this.display);

    this.selectedIndex = null;

    if (this.display.length >= 2) {
      // Is the data given by an outside ajax request?
      if (this.isAsync) {
        this.loading = true;
        this.resourceSearch();
      } else {
        // Data is sync, we can search our flat array
        this.results = this.results.filter(item => {
          return item.toLowerCase().indexOf(this.display.toLowerCase()) > -1;
        });
        this.isOpen = true;
      }
    } else {
      this.results = [];
    }
  }

  resourceSearch = debounce(function() {
    var self = this;
    if (!this.display) {
      this.results = [];
      return;
    }
    this.loading = true;
    this.request();
    // axios
    //   .get("/api/persons", { params: { filter: this.display.toLowerCase() } })
    //   .then(function(response) {
    //     return (self.results = response.data.data);
    //   })
    //   .catch(function(error) {
    //     alert(error);
    //   });
  }, 200);

  async request() {
    try {
      var res = await this.searchTerm(this.display.toLowerCase());
      this.error = null;
      this.results = res.data;
      this.loading = false;
    } catch (error) {
      this.error = error.message;
      this.loading = false;
    }
  }

  async searchTerm(term: string): Promise<any> {
    let res = await axios.get("/api/persons", { params: { filter: term } });
    return res.data; //.response;//.docs;
  }

  onArrowDown(ev) {
    ev.preventDefault();
    if (this.selectedIndex === null) {
      this.selectedIndex = 0;
      return;
    }
    this.selectedIndex =
      this.selectedIndex === this.suggestions.length - 1
        ? 0
        : this.selectedIndex + 1;
    this.fixScrolling();
  }

  onArrowUp(ev) {
    ev.preventDefault();
    if (this.selectedIndex === null) {
      this.selectedIndex = this.suggestions.length - 1;
      return;
    }
    this.selectedIndex =
      this.selectedIndex === 0
        ? this.suggestions.length - 1
        : this.selectedIndex - 1;
    this.fixScrolling();
  }

  private fixScrolling() {
    const currentElement = this.$refs.options[this.selectedIndex];
    currentElement.scrollIntoView({
      behavior: "smooth",
      block: "nearest",
      inline: "start"
    });
  }

  onEnter() {
    if (
      Array.isArray(this.results) &&
      this.results.length &&
      this.selectedIndex !== -1 &&
      this.selectedIndex < this.results.length
    ) {
      //this.display = this.results[this.selectedIndex];
      var person = this.results[this.selectedIndex];
      this.$emit("person", person);
      this.clear();
      this.selectedIndex = -1;
    }
  }
}
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
