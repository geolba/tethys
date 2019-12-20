import * as L from "leaflet";

// var CustomControl = L.Control.extend({
export default class DeleteButton extends L.Control {
  options = {
    position: "topleft",
    faIcon: 'fa-trash',
    id: "",
    text: ""
  };

  geolocation = null;
  drawnItems = null;
  bounds = null;
  _map = null;
  _container = null;

  constructor(options) {
    super();
    //util.mixin(this.options, options);      
    L.Util.setOptions(this, options);
    // properties
    this.geolocation = options.geolocation;
    this.drawnItems = options.drawnItems;
    this.bounds = options.bounds;
  }

  onAdd(map) {
    this._map = map;
    this._container = L.DomUtil.create(
      "div",
      "leaflet-bar leaflet-control leaflet-control-custom"
    )

    this._container.style.backgroundColor = "white";
    this._container.style.width = "30px";
    this._container.style.height = "30px";
    this._buildButton();
    return this._container;
  }

  _buildButton() {
    var _link = L.DomUtil.create('a', 'simplebutton-action', this._container);
    // this._link.href = "#";
    if (this.options.id) {
      _link.id = this.options.id;
    }
    if (this.options.text) {
      _link.innerHTML = this.options.text;
    } else {
      L.DomUtil.create('i', 'fa ' + this.options.faIcon, _link);
    }
    L.DomEvent.on(_link, 'click', function (ev) {
      this.drawnItems.clearLayers();
      this.options.geolocation.x_min = "";
      this.options.geolocation.y_min = "";
      this.options.geolocation.x_max = "";
      this.options.geolocation.y_max = "";
      this._map.fitBounds(this.bounds);
    },
      this);
  }
}
// export default CustomControl;