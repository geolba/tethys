// <script>
import Vue from "vue";

//outside of the component:
function initialState() {
  return {
    type: "",
    state: "",
    rights: null,
    project_id: "",

    creating_corporation: "GBA Repository",
    language: "",
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
    // geolocation: {
    //   xmin: "",
    //   ymin: "",
    //   xmax: "",
    //   ymax: ""
    // },
    coverage: {
      xmin: "",
      ymin: "",
      xmax: "",
      ymax: "",
      elevation_min: "",
      elevation_max: "",
      elevation_absolut: "",
      depth_min: "",
      depth_max: "",
      depth_absolut: "",
      time_min: "",
      time_max: "",
      time_absolut: ""
    },
    checkedAuthors: [],
    checkedLicenses: [], // [],
    files: [],
    keywords: [],
    references: [],
    titles: [],
    descriptions: [],
    checkedContributors: [],
    // checkedSubmitters: [],

    persons: [],
    contributors: []
    // submitters: []
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
  watch: {
    language(val) {
      this.title_main.language = val;
      this.abstract_main.language = val;
    }
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