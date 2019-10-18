import { Component, Vue, Prop, Provide } from 'vue-property-decorator';
import VsInput from './text-search/vs-input.vue';
import VsResults from './search-results/vs-results.vue';
import FacetList from './search-results/facet-list.vue';
import VsPagination from './search-results/vs-pagination.vue';
import rdrApi from './search-results/dataservice';
import FilterItem from './models/filter-item';

@Component({
  components: {
    VsInput,
    VsResults,
    FacetList,
    VsPagination
  }
})
export default class App extends Vue {

  results: Array<any> = [];
  facets: Object = {};
  searchTerm: string = '';
  activeFilterItems: Object = {};
  pagination: Object = {
    total: 0,
    per_page: 2,
    current_page: 0,
    // last_page: 0,
    data: []
  };
  loaded = false;
  numFound: number;

  async onPaginate(start: number): Promise<void> {
    console.log(start);
    var res = await rdrApi.search(this.searchTerm, this.activeFilterItems, start.toString());
    this.results = res.response.docs;
  }

  async  onFilter(filter): Promise<void> {
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
      this.numFound = res.response.numFound;

      // pagination
      this.pagination['total'] = res.response.numFound;
      this.pagination['per_page'] = res.responseHeader.params.rows;
      this.pagination['current_page'] = 1;
      this.pagination['data'] = res.response.docs;

      var facet_fields = res.facet_counts.facet_fields;
      for (var prop in facet_fields) {
        var facetValues = facet_fields[prop].map((facetValue, i) => {
          if (i % 2 === 0) {
            // var rObj = { value: facetValue, count: facet_fields[prop][i + 1] };
            var rObj;
            if (filter.value == facetValue) {
              rObj = filter;
            } else if (this.facets[prop].some(e => e.value === facetValue)) {
              console.log(facetValue + " is included")
              var indexOfFacetValue = this.facets[prop].findIndex(i => i.value === facetValue);
              console.log(indexOfFacetValue);
              rObj = this.facets[prop][indexOfFacetValue];
              rObj.count = facet_fields[prop][i + 1];
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

  async  onSearch(term): Promise<void> {
    if (term){
      term = term.trim();
    } else {
      term = "*%3A*";
    }

    this.activeFilterItems = {};
    // while (this.facets.length > 0) {
    //   this.facets.pop();
    // }
    this.facets = {};
    this.searchTerm = term;
    var res = await rdrApi.search(this.searchTerm, this.activeFilterItems);
    this.results = res.response.docs;
    this.numFound = res.response.numFound;

    // pagination
    this.pagination['total'] = res.response.numFound;
    this.pagination['per_page'] = res.responseHeader.params.rows;
    this.pagination['current_page'] = 1;
    this.pagination['data'] = res.response.docs;

    // facets
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

  // When the window loads, read query parameters and perform search
  async mounted() {
    var query = this.getParameterByName("q");
    if (query) query = query.trim();
    await this.onSearch("*%3A*");
    this.loaded = true;
  }

  getParameterByName(name: string, url?: string) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
      results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return "";
    return decodeURIComponent(results[2].replace(/\+/g, " "));
  }

}