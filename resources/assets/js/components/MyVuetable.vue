// MyVuetable.vue
  <!-- :fields="['name', 'email', 'birthdate']" -->
  // "https://vuetable.ratiw.net/api/users"  
<template>
  <vuetable ref="vuetable"
    api-url="/api/persons"
    v-bind:fields="fields" pagination-path=""
  ></vuetable>
</template>

<script>
import Vuetable from "vuetable-2/src/components/Vuetable";

export default {
  components: {
    Vuetable
  },
  data() {
    return {
      fields: [        
        {
          name: "full_name",
          // sortField: "name"
        },
        {
          name: "email",
          // sortField: "email"
        },
        {
          name: "date_of_birth",
          // sortField: "birthdate",
          // titleClass: "text-center",
          // dataClass: "text-center",
          // callback: "formatDate|DD-MM-YYYY"
        },        
        {
          name: "__component:custom-actions",
          title: "Actions",
          titleClass: "text-center",
          dataClass: "text-center"
        }
      ],
      css: {
        table: {
          tableClass: "table table-bordered table-striped table-hover",
          ascendingIcon: "glyphicon glyphicon-chevron-up",
          descendingIcon: "glyphicon glyphicon-chevron-down"
        },
        pagination: {
          wrapperClass: "pagination",
          activeClass: "active",
          disabledClass: "disabled",
          pageClass: "page",
          linkClass: "link",
          icons: {
            first: "",
            prev: "",
            next: "",
            last: ""
          }
        },
        icons: {
          first: "glyphicon glyphicon-step-backward",
          prev: "glyphicon glyphicon-chevron-left",
          next: "glyphicon glyphicon-chevron-right",
          last: "glyphicon glyphicon-step-forward"
        }
      },
      sortOrder: [{ field: "email", sortField: "email", direction: "asc" }],
      moreParams: {}
    };
  }
};
</script>