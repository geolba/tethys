// import "leaflet";
import * as L from "leaflet";
import DeleteButton from "./DeleteButton";
import "leaflet-draw";
import { Component, Inject, Vue, Prop, Watch } from "vue-property-decorator";
import VueToast from "vue-toast-notification";
// import "vue-toast-notification/dist/index.css";
import 'vue-toast-notification/dist/theme-default.css';
Vue.use(VueToast);

// import ToastedPlugin from 'vue-toasted';
// Vue.use(ToastedPlugin);

@Component({})
export default class LocationsMap extends Vue {
  @Inject("$validator") readonly $validator;

  @Prop({ type: Object })
  geolocation;

  map = null;
  drawnItems = null;
  locationErrors: Array<any> = [];
  options = {
    theme: "bubble",
    position: "top-right",
    duration: 3000
  };

  created() {   
  }

  zoomTo() {
    var _this = this;
    _this.locationErrors.length = 0;
    this.drawnItems.clearLayers();
    //var xmin = document.getElementById("xmin").value;
    var xmin = (<HTMLInputElement>document.getElementById("xmin")).value;
    // var ymin = document.getElementById("ymin").value;
    var ymin = (<HTMLInputElement>document.getElementById("ymin")).value;
    //var xmax = document.getElementById("xmax").value;
    var xmax = (<HTMLInputElement>document.getElementById("xmax")).value;
    //var ymax = document.getElementById("ymax").value;
    var ymax = (<HTMLInputElement>document.getElementById("ymax")).value;
    var bounds = [[ymin, xmin], [ymax, xmax]];
    try {
      var boundingBox = L.rectangle(bounds, { color: "#005F6A", weight: 1 });
      // this.geolocation.xmin = xmin;
      // this.geolocation.ymin = ymin;
      // this.geolocation.xmax = xmax;
      // this.geolocation.ymax = ymax;

      _this.drawnItems.addLayer(boundingBox);
      _this.map.fitBounds(bounds);
      this.$toast.success("valid bounding box", this.options);
    } catch (e) {
      // _this.errors.push(e);
      this.$toast.error(e, this.options);
    }
  }

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
    //   edit: {
    //     featureGroup: drawnItems, //REQUIRED!!
    //     remove: false
    //   }
    };

    // Initialise the draw control and pass it the FeatureGroup of editable layers
    var drawControl = new L.Control.Draw(drawPluginOptions);
    map.addControl(drawControl);

    var customControl = new DeleteButton({ geolocation: this.geolocation, drawnItems: drawnItems, bounds: bounds  });
    map.addControl(customControl);   

    map.on(
      L.Draw.Event.CREATED,
      function(event) {
        drawnItems.clearLayers();
        var type = event.layerType;
        var layer = event.layer;

        // if (type === "rectancle") {
        // layer.bindPopup("A popup!" + layer.getBounds().toBBoxString());
        var bounds = layer.getBounds();
        this.geolocation.x_min = bounds.getSouthWest().lng;
        this.geolocation.y_min = bounds.getSouthWest().lat;
        // console.log(this.geolocation.xmin);
        this.geolocation.x_max = bounds.getNorthEast().lng;
        this.geolocation.y_max = bounds.getNorthEast().lat;
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

  get validBoundingBox(): boolean {
    if (this.geolocation.x_min != "" && this.geolocation.y_min != "" && this.geolocation.x_max != "" && this.geolocation.y_max != "" ) {
        return true;
    }
    return false;
  }

}