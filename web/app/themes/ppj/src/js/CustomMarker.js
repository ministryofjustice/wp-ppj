
function CustomMarker(latlng, map, args) {
    this.latlng = latlng;
    this.args = args;
    this.setMap(map);
}

function styleMarker(div, args) {
    if (args.selected) {
        div.classList.add('search__map-marker--selected');
    }
}

CustomMarker.deselectMarker = function() {
  // find all markers
  const searchMap = document.querySelector('.search__map');

  const jobMarkers = searchMap.querySelectorAll('.search__map-marker--job-location-group.search__map-marker--selected');
  for (let i in jobMarkers) {
    if (typeof jobMarkers[i].classList !== 'undefined') {
      const classList = jobMarkers[i].classList;
      classList.remove('search__map-marker--selected');
    }
  }
};

CustomMarker.changeSelectedMarker = function(div) {
    // remove any selected marker classes
    CustomMarker.deselectMarker();

    // add selected marker class to this marker
    div.classList.add('search__map-marker--selected');
};

CustomMarker.changeSelectedMarkerByGroupId = function(groupId) {
  const marker = document.querySelector('[data-group-id="' + groupId + '"]');
  CustomMarker.changeSelectedMarker(marker);
};

CustomMarker.prototype = new google.maps.OverlayView();

CustomMarker.prototype.draw = function() {

    var self = this;
    var div = this.div;

    if (!div) {
      div = this.div = document.createElement('div');
      div.classList.add('search__map-marker');
      div.classList.add(self.args.class);
      div.setAttribute('data-group-id', self.args.groupId);
      styleMarker(div, self.args);

      const icon = document.createElement('div');
      icon.classList.add('search__map-marker-icon');
      div.appendChild(icon);

      icon.addEventListener("mouseover", function() {
        div.classList.add('search__map-marker--hover');
      });

      icon.addEventListener("mouseleave", function() {
        div.classList.remove('search__map-marker--hover');
      });

      const label = document.createElement('div');
      label.innerHTML = self.args.prisonName;
      label.classList.add('search__map-marker-label');
      div.appendChild(label);

      if (typeof(self.args.marker_id) !== 'undefined') {
          div.dataset.marker_id = self.args.marker_id;
      }

      if (typeof self.args.clickCallback !== 'undefined'){
          google.maps.event.addDomListener(div, "click", function(event) {
              google.maps.event.trigger(self, "click");
              self.args.clickCallback(event);
          });
      }

      var panes = this.getPanes();
      panes.overlayImage.appendChild(div);
    }

    var point = this.getProjection().fromLatLngToDivPixel(this.latlng);

    if (point) {
        div.style.left = point.x + 'px';
        div.style.top  = point.y + 'px';
    }
};

CustomMarker.prototype.remove = function() {
    if (this.div) {
        this.div.parentNode.removeChild(this.div);
        this.div = null;
    }
};

CustomMarker.prototype.getPosition = function() {
    return this.latlng;
};

export default CustomMarker;
