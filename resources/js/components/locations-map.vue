<template>
  <div style="position:relative">
    <!-- <div id="inset">
    </div>-->
    <div id="map"></div>
    <!-- <div id="delete">Delete Features</div> -->   
    <div class="pure-g">
      <div class="pure-u-1 pure-u-md-1-2 pure-div">
        <label for="xmin">xmin:</label>
        <input
          name="coverage[x_min]"          
          type="text"
          class="pure-u-23-24"
          v-model="geolocation.x_min"
          data-vv-scope="step-2"
          id="xmin"
          v-validate="'decimal|required'"
        />
      </div>

      <div class="pure-u-1 pure-u-md-1-2 pure-div">
        <label for="ymin">ymin:</label>
        <input
          name="coverage[y_min]"
          type="text"
          class="pure-u-23-24"
          v-model="geolocation.y_min"
          data-vv-scope="step-2"
          id="ymin"
          v-validate="'decimal|required'"
        />
      </div>

      <div class="pure-u-1 pure-u-md-1-2 pure-div">
        <label for="xmax">xmax:</label>
        <input
          name="coverage[x_max]"
          type="text"
          class="pure-u-23-24"
          v-model="geolocation.x_max"
          data-vv-scope="step-2"
          id="xmax"
          v-validate="'decimal|required'"
        />
      </div>

      <div class="pure-u-1 pure-u-md-1-2 pure-div">
        <label for="ymax">ymax:</label>
        <input
          name="coverage[y_max]"
          type="text"
          class="pure-u-23-24"
          v-model="geolocation.y_max"
          data-vv-scope="step-2"
          id="ymax"
          v-validate="'decimal|required'"
        />
      </div>
      <input type="button" v-if="validBoundingBox" v-on:click="zoomTo" value="validate coordinates" />
    </div>
  </div>
</template>

<script lang="ts">
import LocationsMap from "./LocationsMap";
export default LocationsMap;
</script>

// https://github.com/vuejs-templates/webpack/issues/121
// npm install node-sass sass-loader --save-dev
<style lang="scss">
/* Import CSS from Leaflet and plugins.*/
@import "~leaflet/dist/leaflet.css";
@import "~leaflet-draw/dist/leaflet.draw.css";

#map {
  width: 100%;
  height: 400px;
  font-weight: bold;
  font-size: 13px;
  text-shadow: 0 0 2px #fff;
}
#delete,
#export {
  position: absolute;
  top: 50px;
  right: 10px;
  z-index: 100;
  background: white;
  color: black;
  padding: 6px;
  border-radius: 4px;
  font-family: "Helvetica Neue";
  cursor: pointer;
  font-size: 12px;
  text-decoration: none;
  z-index: 999;
}
#inset {
  position: absolute;
  bottom: 0;
  left: 0;
  border: none;
  width: 120px;
  z-index: 999;
  /*height: 120px;*/
}
</style>