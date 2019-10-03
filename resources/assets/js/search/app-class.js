import { Component, Vue, Prop, Provide } from 'vue-property-decorator';
import VsInput from './text-search/vs-input.vue';
import VsResults from './search-results/vs-results.vue';
import FacetList from './search-results/facet-list.vue'
import rdrApi from './search-results/dataservice';

@Component({
  components: {   
    VsInput,
    VsResults,
    FacetList
  }
})
export default class App extends Vue {

  results = [];
  facets = [];
  bar = 'bar';

  async  onSearch(term) {
    console.log(term);
    // this.results = await rdrApi.search(term);
    var res = await rdrApi.search(term);
    this.results = res.response.docs;
    this.facets = res.facet_counts.facet_fields;
  }
 

  mounted() {
    console.log('Component mounted.')
  }

}