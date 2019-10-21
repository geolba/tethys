<template>
  <div style="position:relative">
    <!-- <div id="inset">
      xmin:
      <input
        type="text"
        name="xmin"
        id="xmin"
        v-model="geolocation.xmin"
        data-vv-scope="step-2"
        v-validate="'decimal'"
      >
      <br>ymin:
      <input
        type="text"
        name="ymin"
        id="ymin"
        v-model="geolocation.ymin"
        data-vv-scope="step-2"
      >
      xmax:
      <input
        type="text"
        name="xmax"
        id="xmax"
        v-model="geolocation.xmax"
        data-vv-scope="step-2"
      >
      <br>ymax:
      <input
        type="text"
        name="ymax"
        id="ymax"
        v-model="geolocation.ymax"
        data-vv-scope="step-2"
      >
      <input type="button" v-on:click="zoomTo" value="zoomTo">
    </div>-->
    <div id="map"></div>

    <div class="pure-g">
      <div class="pure-u-1 pure-u-md-1-2 pure-div">
        <label for="xmin">xmin:</label>
        <input
          name="xmin"
          type="text"
          class="pure-u-23-24"
          v-model="geolocation.xmin"
          data-vv-scope="step-2"
          id="xmin"
          v-validate="'decimal'"
        />
      </div>

      <div class="pure-u-1 pure-u-md-1-2 pure-div">
        <label for="ymin">ymin:</label>
        <input
          name="ymin"
          type="text"
          class="pure-u-23-24"
          v-model="geolocation.ymin"
          data-vv-scope="step-2"
          id="ymin"
          v-validate="'decimal'"
        />
      </div>

      <div class="pure-u-1 pure-u-md-1-2 pure-div">
        <label for="xmax">xmax:</label>
        <input
          name="xmax"
          type="text"
          class="pure-u-23-24"
          v-model="geolocation.xmax"
          data-vv-scope="step-2"
          id="xmax"
          v-validate="'decimal'"
        />
      </div>

      <div class="pure-u-1 pure-u-md-1-2 pure-div">
        <label for="ymax">ymax:</label>
        <input
          name="ymax"
          type="text"
          class="pure-u-23-24"
          v-model="geolocation.ymax"
          data-vv-scope="step-2"
          id="ymax"
          v-validate="'decimal'"
        />
      </div>
      <input type="button" v-on:click="zoomTo" value="validate coordinates" />
    </div>
  </div>
</template>

<script>
// import "leaflet";
import * as L from "leaflet";
import "leaflet-draw";

//const L = window.L;
export default {
  inject: {
    $validator: "$validator"
  },
  props: {
    geolocation: {
      type: Object
    }
  },
  data() {
    return {
      map: [],
      drawnItems: null,
      locationErrors: []
    };
  },
  created() {
    this.$validator.extend("boundingBox", {
      getMessage: field => "At least one " + field + " needs to be checked.",
      validate: (value, [testProp]) => {
        const options = this.dataset.checkedLicenses;
        return value || options.some(option => option[testProp]);
      }
    });
  },
  computed: {},
  watch: {},
  methods: {
    zoomTo() {
      var _this = this;
      _this.locationErrors.length = 0;
      this.drawnItems.clearLayers();
      var xmin = document.getElementById("xmin").value;
      var ymin = document.getElementById("ymin").value;
      var xmax = document.getElementById("xmax").value;
      var ymax = document.getElementById("ymax").value;
      var bounds = [[ymin, xmin], [ymax, xmax]];
      try {
        var boundingBox = L.rectangle(bounds, { color: "#005F6A", weight: 1 });
        // this.geolocation.xmin = xmin;
        // this.geolocation.ymin = ymin;
        // this.geolocation.xmax = xmax;
        // this.geolocation.ymax = ymax;

        _this.drawnItems.addLayer(boundingBox);
        _this.map.fitBounds(bounds);
        _this.$toast.success("valid bounding box");
      } catch (e) {
        // _this.errors.push(e);
        _this.$toast.error(e);
      }
    }
  },
  mounted() {
    const map = L.map("map");
    this.map = map;
    map.scrollWheelZoom.disable();
    // Construct a bounding box for this map that the user cannot
    var southWest = L.latLng(46.5, 9.9),
      northEast = L.latLng(48.9, 16.9),
      bounds = L.latLngBounds(southWest, northEast);
    // zoom the map to that bounding box
    // map.fitBounds(bounds);

    var southWest1 = L.latLng(46.35877, 8.782379); //lowerCorner
    var northEast1 = L.latLng(49.037872, 17.189532); //upperCorner
    var layerBounds = L.latLngBounds(southWest1, northEast1);
    // var attribution = "www.basemap.at";
    // var basemap_0 = L.tileLayer(
    //   "https://{s}.wien.gv.at/basemap/bmapgrau/normal/google3857/{z}/{y}/{x}.png",
    //   {
    //     attribution: attribution,
    //     subdomains: ["maps", "maps1", "maps2", "maps3"],
    //     continuousWorld: false,
    //     detectRetina: false,
    //     bounds: layerBounds
    //   }
    // );
    //basemap_0.addTo(map);   
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution:
        '© <a target="_blank" href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(this.map);

    map.fitBounds(bounds);

    this.map = map;
    // this.addPlaces(this.places)

    // Initialise the FeatureGroup to store editable layers
    var drawnItems = (this.drawnItems = new L.FeatureGroup());
    map.addLayer(drawnItems);
    var drawPluginOptions = {
      position: "topright",
      draw: {
        polygon: false,
        // disable toolbar item by setting it to false
        polyline: false,
        circle: false,
        circlemarker: false,
        rectangle: {
          shapeOptions: {
            clickable: true,
            color: "#005F6A"
          }
        },
        marker: false
      },
      edit: {
        featureGroup: drawnItems, //REQUIRED!!
        remove: false
      }
    };

    // Initialise the draw control and pass it the FeatureGroup of editable layers
    var drawControl = new L.Control.Draw(drawPluginOptions);
    map.addControl(drawControl);

    map.on(
      L.Draw.Event.CREATED,
      function(event) {
        drawnItems.clearLayers();
        var type = event.layerType;
        var layer = event.layer;

        // if (type === "rectancle") {
        // layer.bindPopup("A popup!" + layer.getBounds().toBBoxString());
        var bounds = layer.getBounds();
        this.geolocation.xmin = bounds.getSouthWest().lng;
        this.geolocation.ymin = bounds.getSouthWest().lat;
        // console.log(this.geolocation.xmin);
        this.geolocation.xmax = bounds.getNorthEast().lng;
        this.geolocation.ymax = bounds.getNorthEast().lat;
        // }

        drawnItems.addLayer(layer);
      },
      this
    );

    map.on(
      L.Draw.Event.EDITED,
      function(event) {
        var layers = event.layers.getLayers();
        var layer = layers[0];

        var bounds = layer.getBounds();
        this.geolocation.xmin = bounds.getSouthWest().lng;
        this.geolocation.ymin = bounds.getSouthWest().lat;
        // console.log(this.geolocation.xmin);
        this.geolocation.xmax = bounds.getNorthEast().lng;
        this.geolocation.ymax = bounds.getNorthEast().lat;
      },
      this
    );
  }
};
</script>

// https://github.com/vuejs-templates/webpack/issues/121
// npm install node-sass sass-loader --save-dev
<style lang="scss">
// Import CSS from Leaflet and plugins.
@import "~leaflet/dist/leaflet.css";
@import "~leaflet-draw/dist/leaflet.draw.css";

#map {
  width: 100%;
  height: 400px;
  font-weight: bold;
  font-size: 13px;
  text-shadow: 0 0 2px #fff;
}

#inset {
  position: absolute;
  bottom: 0;
  left: 0;
  border: none;
  width: 120px;
  z-index: 999;
  // height: 120px;
}
</style>