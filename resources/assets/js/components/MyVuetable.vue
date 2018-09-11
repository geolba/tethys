// MyVuetable.vue
  <!-- :fields="['name', 'email', 'birthdate']" -->
  // "https://vuetable.ratiw.net/api/users"  
<template>
  <vuetable ref="vuetable"
    api-url="/api/persons"
    v-bind:fields="fields" pagination-path=""
    v-bind:css="css.table"
  ></vuetable>
</template>

<script>
import Vuetable from "vuetable-2/src/components/Vuetable";
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
import CustomActions from './CustomActions'

Vue.component('custom-actions', CustomActions)

export default {
  components: {
    Vuetable,
     VuetablePagination,
    VuetablePaginationInfo, 
  },
  data() {
    return {
      fields: [        
        {
          name: "full_name",
          // sortField: "full_name"
        },
        {
          name: "email",
          sortField: "email"
        },
        {
          name: "date_of_birth",
          sortField: "date_of_birth",
          // titleClass: "text-center",
          // dataClass: "text-center",
          // callback: "formatDate|DD-MM-YYYY"
        },        
        {
          name: "__component:custom-actions",
          title: "Actions",
          // titleClass: "text-center",
          // dataClass: "text-center"
        }
      ],
      css: {
        table: {
          tableClass: "table pure-table pure-table-horizontal",
          ascendingIcon: "glyphicon glyphicon-chevron-up",
          descendingIcon: "glyphicon glyphicon-chevron-down"
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
  }//data end
};
</script>

<style>
.pagination {
  margin: 0;
  float: right;
}
.pagination a.page {
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 10px;
  margin-right: 2px;
}
.pagination a.page.active {
  color: white;
  background-color: #337ab7;
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 10px;
  margin-right: 2px;
}
.pagination a.btn-nav {
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 7px;
  margin-right: 2px;
}
.pagination a.btn-nav.disabled {
  color: lightgray;
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 7px;
  margin-right: 2px;
  cursor: not-allowed;
}
.pagination-info {
  float: left;
}
</style>