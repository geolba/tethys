import { Component, Vue, Prop, Provide } from 'vue-property-decorator';
import VsInput from './text-search/vs-input.vue';
import VsResults from './search-results/vs-results.vue';
import FacetList from './search-results/facet-list.vue'
import rdrApi from './search-results/dataservice';
import FilterItem from './models/filter-item';

@Component({
  components: {
    VsInput,
    VsResults,
    FacetList
  }
})
export default class App extends Vue {

  results = [];
  facets = {};
  searchTerm = '';
  activeFilterItems = {};

  async  onFilter(filter) {  
    // console.log(filter.value);
    // if (!this.activeFilterItems.some(e => e.value === filter.value)) {
      // this.activeFilterItems.push(filter);
    if (!this.activeFilterItems.hasOwnProperty(filter.Category)) {     
      this.activeFilterItems[filter.Category] = [];     
    }
    if (!this.activeFilterItems[filter.Category].some(e => e === filter.value)) {
      this.activeFilterItems[filter.Category].push(filter.value);

      var res = await rdrApi.search(this.searchTerm, this.activeFilterItems);
      this.results = res.response.docs;
      // this.facets = res.facet_counts.facet_fields;
      // this.facets = [];
      var facet_fields = res.facet_counts.facet_fields;
      for (var prop in facet_fields) {
        var facetValues = facet_fields[prop].map((facetValue, i) => {
          if (i % 2 === 0) {
            // var rObj = { value: facetValue, count: facet_fields[prop][i + 1] };
            var rObj;
            if (filter.value == facetValue) {
              rObj = filter;
            } else if( this.facets[prop].some(e => e.value === facetValue)) {
              console.log(facetValue + " is included")
              var indexOfFacetValue =  this.facets[prop].findIndex(i => i.value === facetValue);
              console.log(indexOfFacetValue);
              rObj = this.facets[prop][indexOfFacetValue];
              rObj.count =  facet_fields[prop][i + 1];
            } else {
              rObj = new FilterItem(facetValue, facet_fields[prop][i + 1]);
            }
            return rObj;
          }
        }).filter(function (el) {
          return el != null && el.count > 0;
        });
        // this.facets.push({ filterName: prop, values: facetValues });
        this.facets[prop] = facetValues;
      }

    }
  }

  async  onSearch(term) {
    // console.log(term);
    // while (this.activeFilterItems.length > 0) {
    //   this.activeFilterItems.pop();
    // }
    this.activeFilterItems = {};
    // while (this.facets.length > 0) {
    //   this.facets.pop();
    // }
    this.facets = {};
    this.searchTerm = term;
    var res = await rdrApi.search(this.searchTerm, this.activeFilterItems);
    this.results = res.response.docs;
    var facet_fields = res.facet_counts.facet_fields;
    for (var prop in facet_fields) {
      var facetValues = facet_fields[prop].map((facet, i) => {
        if (i % 2 === 0) {
          //var rObj = { value: facet, count: facet_fields[prop][i + 1] };
          var rObj = new FilterItem(facet, facet_fields[prop][i + 1])
          return rObj;
        }
      }).filter(function (el) {
        return el != null && el.count > 0;
      });
      //this.facets.push({ filterName: prop, values: facetValues });
      this.facets[prop] = facetValues;
    }
    // console.log(this.facets.toString());
  }


  mounted() {
    // console.log('Component mounted.')
  }

}