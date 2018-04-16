
function CustomMarker(latlng, map, args) {
    this.latlng = latlng;
    this.args = args;
    this.setMap(map);
}

function styleMarker(div, args) {
    if (args.selected) {
        div.classList.add('find-a-job__map-marker--selected');
    }
}

CustomMarker.deselectMarker = function() {
  // find all markers
  const searchMap = document.querySelector('.find-a-job__map');

  const jobMarkers = searchMap.querySelectorAll('.find-a-job__map-marker--job-location-group.find-a-job__map-marker--selected');
  for (let i in jobMarkers) {
    if (typeof jobMarkers[i].classList !== 'undefined') {
      const classList = jobMarkers[i].classList;
      classList.remove('find-a-job__map-marker--selected');
    }
  }
};

CustomMarker.changeSelectedMarker = function(div) {
    // remove any selected marker classes
    CustomMarker.deselectMarker();

    // add selected marker class to this marker
    div.classList.add('find-a-job__map-marker--selected');
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
      div.classList.add('find-a-job__map-marker');
      div.classList.add(self.args.class);

      const icon = document.createElement('div');
      icon.classList.add('find-a-job__map-marker-icon');
      div.appendChild(icon);

      if (self.args.class == 'find-a-job__map-marker--job-location-group') {

        div.setAttribute('data-group-id', self.args.groupId);
        styleMarker(div, self.args);

        icon.addEventListener("mouseover", function() {
          div.classList.add('find-a-job__map-marker--hover');
        });

        icon.addEventListener("mouseleave", function() {
          div.classList.remove('find-a-job__map-marker--hover');
        });

        const label = document.createElement('div');
        label.innerHTML = self.args.prisonName;
        label.classList.add('find-a-job__map-marker-label');
        div.appendChild(label);

        if (typeof(self.args.marker_id) !== 'undefined') {
          div.dataset.marker_id = self.args.marker_id;
        }

        if (typeof self.args.clickCallback !== 'undefined'){

          const action = function(str, event) {
            google.maps.event.trigger(div, "click");
            self.args.clickCallback(event);
          };
          google.maps.event.addDomListener(icon, "click", action.bind(null, 'clicked marker'));
          google.maps.event.addDomListener(icon, "touchstart", action.bind(null, 'touched marker'));
        }

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
