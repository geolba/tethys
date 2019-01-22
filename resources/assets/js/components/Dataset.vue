// <script>
import Vue from "vue";

//outside of the component:
function initialState() {
  return {
    type: "",
    state: "",
    rights: null,
    project_id: "",

    creating_corporation: "GBA",
    embargo_date: "",
    belongs_to_bibliography: 0,

    title_main: {
      value: "",
      language: ""
    },
    abstract_main: {
      value: "",
      language: ""
    },
    geolocation: {
      xmin: "",
      ymin: "",
      xmax: "",
      ymax: ""
    },
    checkedAuthors: [],
    checkedLicenses: [], // [],
    files: [],
    references: [],
    titles: [],
    checkedContributors: [],
    checkedSubmitters: [],

    persons: [],
    contributors: [],
    submitters: []
  };
}

const dataset = new Vue({
  data: function() {
    return initialState();
  },
  created: function() {
    // let json = JSON.stringify(this.$data);
    // this.reset = () => {
    //   Object.assign(this.$data, JSON.parse(json));
    // };
    // this.reset(json);
  },
  methods: {
    reset() {
      Object.assign(this.$data, initialState());
    }
  }
});
export default dataset;
//export { dataset };
</script>