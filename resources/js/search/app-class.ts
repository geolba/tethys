import { Component, Vue } from 'vue-property-decorator';
import VsInput from './text-search/vs-input.vue';
import VsResults from './search-results/vs-results.vue';
import FacetCategory from './search-results/facet-category.vue';
import ActiveFacetCategory from './search-results/active-facet-category.vue';
import VsPagination from './search-results/vs-pagination.vue';
import rdrApi from './search-results/dataservice';
import FilterItem from './models/filter-item';

@Component({
  components: {
    VsInput,
    VsResults,
    FacetCategory,
    ActiveFacetCategory,
    VsPagination
  }
})
export default class App extends Vue {

  results: Array<any> = [];
  facets: Object = {};
  searchTerm: string = '';
  activeFilterCategories: Object = {};
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
    // console.log(start);
    var res = await rdrApi.search(this.searchTerm, this.activeFilterCategories, start.toString());
    this.results = res.response.docs;
  }

  async onClearFacetCategory(categoryName, alias): Promise<void> {
    // alert(categoryName);
    delete this.activeFilterCategories[categoryName];

    var res = await rdrApi.search(this.searchTerm, this.activeFilterCategories);
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
          var rObj:FilterItem;
          if (this.facets[prop].some(e => e.value === facetValue)) {
            // console.log(facetValue + " is included")
            var indexOfFacetValue = this.facets[prop].findIndex(i => i.value === facetValue);
            // console.log(indexOfFacetValue);
            rObj = this.facets[prop][indexOfFacetValue];
            rObj.count = facet_fields[prop][i + 1];
            //if facet ccategory is reactivated category, deactivate all filter items
            if (this.propName(this.facets, this.facets[prop]) == alias) {
              rObj.Active = false;
            }
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

  private propName (obj, type): string {
     let stringPropValue = Object.keys(obj).find(key => obj[key] === type);
     return stringPropValue;
  }

  async  onFilter(filter): Promise<void> {
    // console.log(filter.value);
    // if (!this.activeFilterItems.some(e => e.value === filter.value)) {
    // this.activeFilterItems.push(filter);
    if (!this.activeFilterCategories.hasOwnProperty(filter.Category)) {
      this.activeFilterCategories[filter.Category] = Vue.observable([]);
    }
    if (!this.activeFilterCategories[filter.Category].some(e => e === filter.value)) {
      this.activeFilterCategories[filter.Category].push(filter.value);
      // alert(this.activeFilterCategories[filter.Category]);
      var res = await rdrApi.search(this.searchTerm, this.activeFilterCategories);
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
              // console.log(facetValue + " is included")
              var indexOfFacetValue = this.facets[prop].findIndex(i => i.value === facetValue);
              // console.log(indexOfFacetValue);
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

    this.activeFilterCategories = {};
    // while (this.facets.length > 0) {
    //   this.facets.pop();
    // }
    this.facets = {};
    this.searchTerm = term;
    var res = await rdrApi.search(this.searchTerm, this.activeFilterCategories);
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

  private getParameterByName(name: string, url?: string) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
      results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return "";
    return decodeURIComponent(results[2].replace(/\+/g, " "));
  }

}