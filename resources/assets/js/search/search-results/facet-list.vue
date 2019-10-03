<template>
  <div>
    <div class="card" v-for="(valueArray, filterName, index) in facets" :key="index">
      <!-- <span>property: {{ filterName }}</span>
      <span>value: {{ myLanguageFilters }}</span> -->

      <div class="panel panel-primary">
        <h3 class="panel-title">{{ filterName }}</h3> <!-- e.g.language -->
        <ul class="filter-items" v-for="(value, index) in myLanguageFilters" :key="index">
          <li class="active" role="radio">
            <input
              class="css-w1gpbi"
              name="language"
              v-bind:id="value.value"
              type="radio"
              v-bind:value="value.value"
            />
            <label :for="value.value">
              <span>{{ value.value }} ({{ value.count }})</span>
            </label>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import FacetList from "./facet-list-class";
export default FacetList;
</script>