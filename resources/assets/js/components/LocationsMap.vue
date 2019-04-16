<template>
  <div>   
    <div id="map">
    </div>
  </div>
</template>

<script>
// import "leaflet";
import * as L from "leaflet";
import "leaflet-draw";

//const L = window.L;
export default {
  props: {
    geolocation: {
      type: Object
    }
  },
  data() {
    return {
      map: [],
      markers: null
    };
  },
  computed: {},
  watch: {},
  methods: {},
  mounted() {
    const map = L.map("map");
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
    var attribution = "www.basemap.at";
    var basemap_0 = L.tileLayer(
      "https://{s}.wien.gv.at/basemap/bmapgrau/normal/google3857/{z}/{y}/{x}.png",
      {
        attribution: attribution,
        subdomains: ["maps", "maps1", "maps2", "maps3"],
        continuousWorld: false,
        detectRetina: false,
        bounds: layerBounds
      }
    );
    basemap_0.addTo(map);
    map.fitBounds(bounds);

    this.map = map;
    // this.addPlaces(this.places)

    // Initialise the FeatureGroup to store editable layers
    var drawnItems = new L.FeatureGroup();
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
</style>