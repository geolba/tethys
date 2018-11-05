<script>
import Vue from "vue";

let dataset = new Vue({
  data: function() {
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
      checkedAuthors: [],
      checkedLicenses: [], // [],
      files: [],
      references: [],
      checkedContributors: [],
      checkedSubmitters: [],

      persons: [],
      contributors: [],
      submitters: []
    };
  },
  created() {
    let json = JSON.stringify(this.$data);
    this.reset = () => {
      Object.assign(this.$data, JSON.parse(json));
    };
  }
});
export { dataset };
</script>