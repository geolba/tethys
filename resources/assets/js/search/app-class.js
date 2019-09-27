import { Component, Vue, Prop, Provide } from 'vue-property-decorator';
import VsInput from './components/vs-input.vue';
import rdrApi from './search-results/search-api';

@Component({
  components: {   
    VsInput
  }
})
export default class App extends Vue {

  results = [];
  bar = 'bar';

  async  onSearch(term) {
    console.log(term);
    this.results = await rdrApi.search(term);
  }
 

  mounted() {
    console.log('Component mounted.')
  }

}