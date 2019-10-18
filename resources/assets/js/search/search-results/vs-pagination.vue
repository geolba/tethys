<template>
<nav v-if="pages.length > 1" class="pagination is-rounded is-medium" role="navigation" aria-label="pagination">
  <ul class="pagination-list">

    <!-- Previous Page Link -->
    <li v-if="data.current_page > 1">     
       <a class="pagination-previous" href="#" rel="prev" v-on:click.prevent="changePage(data.current_page - 1)">&laquo;</a>
    </li>
    <li v-else>
      <a class="pagination-previous disabled" disabled href="#" rel="prev">&laquo;</a>
    </li>

    <li v-for="(page, index) in pages" :key="index" >     
      <a  href="#"  v-on:click.prevent="changePage(page)" v-bind:class="['pagination-link', page == data.current_page ? 'is-current' : '']">{{ page }}</a>
    </li>

     <!-- Previous Page Link -->
    <li v-if="data.current_page < numberOfPages">     
      <a  class="pagination-next" href="#" v-on:click.prevent="changePage(data.current_page + 1)" rel="next">&raquo;</a>
    </li>
     <li v-else>
      <a  class="pagination-next disabled" disabled href="#" rel="next">&raquo;</a>
    </li>

  </ul>
</nav>
</template>

<script lang="ts">
import VsPagination from "./vs-pagination-class";
export default VsPagination;
</script>

<style scoped>
/* local styles */
/* vom cms Pagination */
.pagination {
  text-align: center;
  margin: 80px 0 0 0;
  clear: both;  
}

.pagination a {
  /*font-size:1.1em;*/
  padding-left: 1em;
  padding-right: 1em;
}

/* selbst Pagination */
.pagination-previous,
.pagination-next,
.pagination-link,
.pagination-dots {
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  -moz-appearance: none;
  -webkit-appearance: none;
  align-items: center;
  border: 1px solid transparent;
  border-radius: 4px;
  box-shadow: none;
  display: inline-flex;
  /*display: inline-block;*/
  font-size: 1rem;
  height: 2.25em;
  justify-content: flex-start;
  line-height: 1.5;
  padding: calc(0.375em - 1px) calc(0.625em - 1px) calc(0.375em - 1px)
    calc(0.625em - 1px);
  /*padding-top: calc(0.375em - 1px);
    padding-right: calc(0.625em - 1px);
    padding-bottom: calc(0.375em - 1px);
    padding-left: calc(0.625em - 1px);*/
  position: relative;
  vertical-align: top;
}

.pagination-previous:focus,
.pagination-next:focus,
.pagination-link:focus,
.pagination-dots:focus,
.is-focused.pagination-next,
.is-focused.pagination-link,
.is-focused.pagination-dots,
.pagination-previous:active,
.pagination-next:active,
.pagination-link:active,
.pagination-dots:active,
.is-active.pagination-previous,
.is-active.pagination-next,
.is-active.pagination-link,
.is-active.pagination-dots {
  outline: none;
}

.pagination-previous.disabled,
.pagination-next.disabled,
.pagination-link.disabled,
.pagination-dots.disabled {
  cursor: not-allowed;
}

.pagination {
  /*background-color:azure;*/
  font-size: 1rem;
  margin: -0.25rem;
}

.pagination.is-small {
  font-size: 0.75rem;
}

.pagination.is-medium {
  font-size: 1.25rem;
}

.pagination.is-large {
  font-size: 1.5rem;
}

.pagination.is-rounded .pagination-previous,
.pagination.is-rounded .pagination-next {
  padding-left: 1em;
  padding-right: 1em;
  border-radius: 290486px;
}

.pagination.is-rounded .pagination-link {
  border-radius: 290486px;
}

/*.pagination,*/
.pagination-list {
  align-items: center;
  display: flex;
  justify-content: center;
  text-align: center;
  list-style: none;
}

.pagination-previous,
.pagination-next,
.pagination-link,
.pagination-dots {
  font-size: 1em;
  padding-left: 0.5em;
  padding-right: 0.5em;
  justify-content: center;
  margin: 0.25rem;
  text-align: center;
}

.pagination-previous,
.pagination-next,
.pagination-link {
  border-color: #dbdbdb;
  color: #363636;
  min-width: 2.25em;
}

.pagination-previous:hover,
.pagination-next:hover,
.pagination-link:hover {
  border-color: #b5b5b5;
  color: #363636;
}

.pagination-previous:focus,
.pagination-next:focus,
.pagination-link:focus {
  border-color: #3273dc;
}

.pagination-previous:active,
.pagination-next:active,
.pagination-link:active {
  box-shadow: inset 0 1px 2px rgba(10, 10, 10, 0.2);
}

.pagination-previous.disabled,
.pagination-next.disabled,
.pagination-link.disabled {
  background-color: #dbdbdb;
  border-color: #dbdbdb;
  box-shadow: none;
  color: #7a7a7a;
  opacity: 0.5;
}

.pagination-previous,
.pagination-next {
  padding-left: 0.75em;
  padding-right: 0.75em;
  white-space: nowrap;
}

.pagination-link.is-current {
  background-color: #3abac4; /*#3273dc;*/
  border-color: #3abac4;
  color: #fff;
}

.pagination-dots {
  color: #b5b5b5;
  pointer-events: none;
}

.pagination-list {
  flex-wrap: wrap;
}

@media screen and (max-width: 767px) {
  .pagination {
    flex-wrap: wrap;
  }

  .pagination-previous,
  .pagination-next {
    flex-grow: 1;
    flex-shrink: 1;
  }

  .pagination-list li {
    flex-grow: 1;
    flex-shrink: 1;
  }
}

@media screen and (min-width: 768px), print {
  .pagination-list {
    flex-grow: 1;
    flex-shrink: 1;
    justify-content: center;
    order: 1;
  }

  .pagination-previous {
    order: 2;
  }

  .pagination-next {
    order: 3;
  }

  .pagination {
    justify-content: space-between;
  }

  .pagination.is-centered .pagination-previous {
    order: 1;
  }

  .pagination.is-centered .pagination-list {
    justify-content: center;
    order: 2;
  }

  .pagination.is-centered .pagination-next {
    order: 3;
  }

  .pagination.is-right .pagination-previous {
    order: 1;
  }

  .pagination.is-right .pagination-next {
    order: 2;
  }

  .pagination.is-right .pagination-list {
    justify-content: flex-end;
    order: 3;
  }
}
</style>