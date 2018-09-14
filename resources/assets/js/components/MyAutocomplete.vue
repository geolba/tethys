<!-- https://pineco.de/instant-ajax-search-laravel-vue/ 
https://alligator.io/vuejs/vue-autocomplete-component/ -->
<template>
    <div>
        <input type="search" @input="onChange" v-model="search" v-bind:disabled="isLoading == true" v-bind:title=title placeholder="Search for authors and contributors...">
        <!-- <ul class="autocomplete-results" v-show="results.length > 0"> -->
        <ul class="autocomplete-results" v-show="isOpen"> 
             <li class="loading" v-if="isLoading" >Loading results...</li>

            <li 
                v-else
                v-for="result in results" 
                :key="result.id" 
                @click="setResult(result)"
                class="autocomplete-result">
                 <strong>{{ result.full_name }}</strong>
            </li>
        </ul>
    </div>
</template>

<script>
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
      items: []
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
        if (val.length !== oldValue.length) {
          this.results = val;
          this.isLoading = false;
        }
      }
    }
  },

  methods: {
    setResult(person) {
      // this.search = person.full_name;
      this.reset();
      this.isOpen = false;
      this.$emit("person", person);
    },
    reset() {
      this.search = "";
    },
    onChange() {
      // Let's warn the parent that a change was made
      this.$emit("input", this.search);

      // Is the data given by an outside ajax request?
      if (this.isAsync) {
        this.isLoading = true;
        this.filterResults();
      } else {
        // Data is sync, we can search our flat array
        this.filterResults();
        this.isOpen = true;
      }
    },
    filterResults() {
      var self = this;
      axios
        .get("/api/persons", { params: { filter: this.search } })
        .then(function(response) {
          return (self.items = response.data.data);
        })
        .catch(function(error) {
          alert(error);
        });
      // this.results = this.items.filter(item => {
      //   return item.toLowerCase().indexOf(this.search.toLowerCase()) > -1;
      // });
    }
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

.autocomplete-result:hover {
  background-color: #4aae9b;
  color: white;
}
</style>
