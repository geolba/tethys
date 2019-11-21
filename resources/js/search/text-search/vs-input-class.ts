import Vue from "vue";
import { Component, Prop } from 'vue-property-decorator';
import debounce from 'lodash/debounce';
import rdrApi from '../search-results/dataservice';



@Component({})
export default class VsInput extends Vue {

  @Prop()
  title: string;

  @Prop({ default: 'Search' })
  placeholder: string;

  display: string = "";
  value = null;
  error: string = null;
  //search: null,
  results: Array<any> = [];
  // suggestions: Object = {};
  //showResults: boolean = false;
  loading: boolean = false;
  isAsync: boolean = true;
  items: Array<any> = [];
  selectedIndex: number = null;
  selectedDisplay = null;
  isFocussed: boolean = false;

  // get results() {
  //   return this.items;
  // }

  get showResults(): boolean {
    return this.results.length > 0;
  }

  get noResults(): boolean {
    return Array.isArray(this.results) && this.results.length === 0
  }

  get isLoading(): boolean {
    return this.loading === true
  }

  get hasError(): boolean {
    return this.error !== null
  }

  get suggestions(): any[] {
    var suggestion = {
      titles: [],
      authors: [],
      subjects: []
    };

    for (let key in this.results) {
      let obj = this.results[key];

      if (obj["title_output"].toLowerCase().indexOf(this.display) !== -1) {
        var title = obj["title_output"];
        if (!suggestion["titles"].find(value => value === title)) {
          suggestion.titles.push(title);
        }
      }
      if (this.find(obj["author"], this.display.toLowerCase()) !== "") {
        var author = this.find(obj["author"], this.display.toLowerCase());
        if (!suggestion["authors"].find(value => value === author)) {
          suggestion.authors.push(author);
        }

      }
      if (this.find(obj["subject"], this.display.toLowerCase()) != "") {
        var subject = this.find(obj["subject"], this.display.toLowerCase());
        if (!suggestion["subjects"].find(value => value === subject)) {
          suggestion.subjects.push(subject);
        }
      }
    }
    var suggestions = Array.prototype.concat(suggestion.titles, suggestion.authors, suggestion.subjects);
    return suggestions;
  }

  // @Watch('items')
  // onItemsChanged(val: Array<any>, oldVal: Array<any>) {
  //   this.results = val;   
  //   this.loading = false;
  // }

  /**
    * Clear all values, results and errors
    */
  clear() {
    this.display = null;
    // this.value = null
    this.results = [];
    this.error = null;
    this.$emit('clear');
  }

  search() {
    this.$emit("search", this.display);
  }

  searchChanged() {
    this.selectedIndex = null
    // Let's warn the parent that a change was made
    // this.$emit("input", this.display);
    if (this.display.length >= 2) {
      this.loading = true;
      this.resourceSearch();
    } else {
      this.results = [];
    }
  }

  resourceSearch = debounce(function () {
    if (!this.display) {
      this.results = []
      return
    }
    this.loading = true;
    // this.setEventListener();
    this.request();
  }, 200);

  async request() {
    try {
      var res = await rdrApi.searchTerm(this.display);
      this.error = null
      this.results = res.response.docs;
      this.loading = false;
    } catch (error) {
      this.error = error.message;
      this.loading = false;
    }
  }

  /**
 * Register the component as focussed
 */
  focus() {
    this.isFocussed = true
  }
  /**
   * Remove the focussed value
   */
  blur() {
    this.isFocussed = false
  }

  /**
 * Is this item selected?
 * @param {Object}
 * @return {Boolean}
 */
  isSelected(key) {
    return key === this.selectedIndex
  }

  onArrowDown(ev) {
    ev.preventDefault()
    if (this.selectedIndex === null) {
      this.selectedIndex = 0;
      return;
    }
    this.selectedIndex = (this.selectedIndex === this.suggestions.length - 1) ? 0 : this.selectedIndex + 1;
    this.fixScrolling();
  }

  fixScrolling() {
    const currentElement = this.$refs.options[this.selectedIndex];
    currentElement.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });
  }

  onArrowUp(ev) {
    ev.preventDefault()
    if (this.selectedIndex === null) {
      this.selectedIndex = this.suggestions.length - 1;
      return;
    }
    this.selectedIndex = (this.selectedIndex === 0) ? this.suggestions.length - 1 : this.selectedIndex - 1;
    this.fixScrolling();
  }

  onEnter() {
    if (this.selectedIndex === null) {
      this.$emit('nothingSelected', this.display);
      return;
    }
    this.select(this.suggestions[this.selectedIndex]);
    this.$emit('enter', this.display);
  }

  select(obj) {
    if (!obj) {
      return
    }
    this.value = obj; //(obj["title_output"]) ? obj["title_output"] : obj.id
    this.display = obj;// this.formatDisplay(obj)
    this.selectedDisplay = this.display
    // this.$emit('selected', {
    //   value: this.value,
    //   display: this.display,
    //   selectedObject: obj
    // })
    this.$emit('input', this.value);
    this.$emit("search", this.value);
    // alert(this.value);
    this.close();
  }

  formatDisplay(obj) {
    if (obj.title_output.toLowerCase().indexOf(this.display) !== -1) {
      return obj.title_output;
    } else if (this.find(obj.author, this.display.toLowerCase()) !== "") {
      var author = this.find(obj.author, this.display.toLowerCase());
      return author;
    } else if (this.find(obj.subject, this.display.toLowerCase()) != "") {
      return this.find(obj.subject, this.display.toLowerCase());
    } else {
      return obj.id;
    }

  }

  private find(myarray, searchterm): string {
    for (var i = 0, len = myarray.length; i < len; i += 1) {
      if (typeof (myarray[i]) === 'string' && myarray[i].toLowerCase().indexOf(searchterm) !== -1) {
        // print or whatever
        return myarray[i];
      }
    }
    return "";
  }

  /**
    * Close the results list. If nothing was selected clear the search
    */
  close() {
    if (!this.value || !this.selectedDisplay) {
      this.clear();
    }
    if (this.selectedDisplay !== this.display && this.value) {
      this.display = this.selectedDisplay;
    }
    this.results = [];
    this.error = null;
    //this.removeEventListener()
    this.$emit('close');
  }
}