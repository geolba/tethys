<template>
  <!-- <span>property: {{ filterName }}</span>-->

  <div class="panel panel-default">
    <!-- <h3 class="panel-title filterViewModelName">{{ filterName }}</h3> -->
    <div class="panel-heading">
      <h3 class="panel-title titlecase filterViewModelName">{{ filterName }}</h3>
    </div>
    <div class="panel-body">


      <!-- e.g.language -->
      <ul class="filter-items list-unstyled" v-bind:class="{'limited':filterItems.length > 1 && collapsed }">
        <li v-for="(item, index) in filterItems" :key="index" class="list-group-item titlecase">
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
          <span :class="item.Active ? 'disabled' : ''" @click.prevent="activateItem(item)">{{ item.value }}
            ({{ item.count }})
          </span>
        </li>
      </ul>
      <ul class="overflowing" v-if="overflowing == true">
        <li>
          <span @click="toggle()">{{ uncollapseLabelText }}</span>
        </li>
      </ul>
    </div>

  </div>
</template>

<script lang="ts">
  import FacetCategory from "./facet-category-class";
  export default FacetCategory;
</script>

<style scoped>
  /* local styles */
  .disabled {
    /* color: #EBEBE4; */
    color: lightgray;
    pointer-events: none;
    text-decoration: line-through;
  }
</style>