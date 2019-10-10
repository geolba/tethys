<template>
  <!-- <span>property: {{ filterName }}</span>
  <span>value: {{ myLanguageFilters }}</span>-->

  <div class="panel panel-primary">
    <h3 class="panel-title filterViewModelName">{{ filterName }}</h3>
    <!-- e.g.language -->
    <ul class="filter-items" v-bind:class="{'limited':filterItems.length > 1 && collapsed }">
      <li v-for="(item, index) in filterItems" :key="index" class="list-group-item">
        <!-- <input
          class="css-w1gpbi"
          name="language"
          v-bind:id="item.value"
          type="radio"
          v-bind:value="item.value"
        />
        <label :for="item.value">
          <span click: @click="activateItem(item)">{{ item.value }} ({{ item.count }})</span>
        </label>-->
        <a :class="Active ? 'disabled' : ''" @click.prevent="activateItem(item)">{{ item.value }} ({{ item.count }})</a>
      </li>
    </ul>
    <ul class="overflowing" v-if="overflowing == true">
      <li>
        <span @click="toggle()">{{ uncollapseLabelText }}</span>
      </li>
    </ul>
  </div>
</template>

<script lang="ts">
import FacetList from "./facet-list-class";
export default FacetList;
</script>

<style scoped>
/* local styles */
.disabled {
  color: lightgrey;
  pointer-events: none;
}
</style>