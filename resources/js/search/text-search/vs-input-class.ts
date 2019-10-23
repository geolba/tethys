import Vue from "vue";
import { Component, Provide } from "vue-property-decorator";

@Component({})
export default class VsInput extends Vue {
  term = ""; 

  search() {
    this.$emit("search", this.term);
  }
  
}