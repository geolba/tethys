import { Component, Vue, Watch } from 'vue-property-decorator';

//outside of the component:
function initialState() {
  return {
    type: "",
    server_state: "",
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
      x_min: null,
      y_min:null,
      x_max: null,
      y_max: null,
      elevation_min: null,
      elevation_max: null,
      elevation_absolut: null,
      depth_min: null,
      depth_max: null,
      depth_absolut: null,
      time_min: null,
      time_max: null,
      time_absolut: null
    },
    checkedAuthors: [],
    checkedLicenses: [], // [],
    files: [],
    subjects: [],
    references: [],
    titles: [],
    abstratcs: [],
    checkedContributors: [],
    // checkedSubmitters: [],

    persons: [],
    contributors: []
    // submitters: []
  };
}

// const dataset = new Vue({
@Component
export default class Dataset extends Vue {
  // data: function() {
  //   return initialState();
  // }
  initialState = {};
  type = "";
  server_state = "";
  rights = null;
  project_id = "";

  creating_corporation = "TETHYS Repository";
  language = "";
  embargo_date = "";
  belongs_to_bibliography = 0;

  title_main = {
    value: "",
    language: ""
  };
  abstract_main = {
    value: "",
    language: ""
  };
  // geolocation: {
  //   xmin: "",
  //   ymin: "",
  //   xmax: "",
  //   ymax: ""
  // },
  coverage = {
    x_min: "",
    y_min: "",
    x_max: "",
    y_max: "",
    elevation_min: "",
    elevation_max: "",
    elevation_absolut: "",
    depth_min: "",
    depth_max: "",
    depth_absolut: "",
    time_min: "",
    time_max: "",
    time_absolut: ""
  };
  checkedAuthors = [];
  checkedLicenses = [];
  files = [];
  subjects = [];
  references = [];
  titles = [];
  abstracts = [];
  checkedContributors = [];
  // checkedSubmitters: [],

  authors = [];
  persons = [];
  contributors = [];
  // submitters: []

  created() {
    this.initialState = Object.assign({}, this);
    // let json = JSON.stringify(this.$data);
    // this.reset = () => {
    //   Object.assign(this.$data, JSON.parse(json));
    // };
    // this.reset(json);
  }

  @Watch('language')
  onLanguageChanged(val) {
    this.title_main.language = val;
    this.abstract_main.language = val;
    for (let [key, title] of Object.entries(this.titles)) {
      if (title.type == "Main") {
        title.language = val;
      }
    }
    for (let [key, abstract] of Object.entries(this.abstracts)) {
      if (abstract.type == "Abstract") {
        abstract.language = val;
      }
    }
  }


  reset() {
    // Object.assign(this.$data, initialState());
    Object.assign(this.$data, this.initialState);
  }

}
// export default dataset;

