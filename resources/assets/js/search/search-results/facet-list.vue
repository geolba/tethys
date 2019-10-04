<template>
  <!-- <span>property: {{ filterName }}</span>
  <span>value: {{ myLanguageFilters }}</span>-->

  <div class="panel panel-primary">
    <h3 class="panel-title filterViewModelName">{{ filterName }}</h3>
    <!-- e.g.language -->
    <ul
      class="filter-items"
      v-for="(value, index) in myLanguageFilters"
      :key="index"
      v-bind:class="{'limited':filterItems.length > 1}"
    >
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
    <ul class="overflowing">
      <li>
        <span @click="toggle()"></span>
      </li>
    </ul>
  </div>
</template>

<script lang="ts">
import FacetList from "./facet-list-class";
export default FacetList;
</script>