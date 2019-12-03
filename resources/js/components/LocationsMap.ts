// import "leaflet";
import * as L from "leaflet";
import "leaflet-draw";
import { Component, Inject, Vue, Prop, Watch } from "vue-property-decorator";
import VueToast from "vue-toast-notification";
import "vue-toast-notification/dist/index.css";
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
    // this.$validator.extend("boundingBox", {
    //   getMessage: field => "At least one " + field + " needs to be checked.",
    //   validate: (value, [testProp]) => {
    //     const options = this.dataset.checkedLicenses;
    //     return value || options.some(option => option[testProp]);
    //   }
    // });
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

    var customControl = L.Control.extend({
      options: {
        position: "topleft",  
        faIcon: 'fa-trash'      
        // faIcon: 'fa-check-circle'
      },
      //constructor:
      initialize: function(options) {     
        //util.mixin(this.options, options);      
        L.Util.setOptions(this, options);
        // properties
        this.geolocation = options.geolocation;
      },
      onAdd: function(map) {
        this._map = map;
        this._container = L.DomUtil.create(
          "div",
          "leaflet-bar leaflet-control leaflet-control-custom"
        );
      
        this._container.style.backgroundColor = "white";
        this._container.style.width = "30px";
        this._container.style.height = "30px";
        this._buildButton();

        // container.onclick = function() {
        //   console.log("buttonClicked");
        // };
      
        return  this._container;
      },
      _buildButton: function(){
        this._link = L.DomUtil.create('a','simplebutton-action',this._container);
        // this._link.href = "#";
        if(this.options.id) {
          this._link.id = this.options.id;
        }
        if(this.options.text) {
          this._link.innerHTML = this.options.text;
        }else{
          L.DomUtil.create('i','fa ' + this.options.faIcon, this._link);
        }
        L.DomEvent.on(this._link, 'click', function(ev) {
            drawnItems.clearLayers();
            this.options.geolocation.xmin = "";
            this.options.geolocation.ymin = "";
            this.options.geolocation.xmax = "";
            this.options.geolocation.ymax = "";
            this._map.fitBounds(bounds);
          },
          this);
      }
    
    });  
    map.addControl(new customControl({ geolocation: this.geolocation }));   

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
}