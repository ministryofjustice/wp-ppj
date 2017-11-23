
function CustomMarker(latlng, map, args) {
    this.latlng = latlng;
    this.args = args;
    this.setMap(map);
}

function styleMarker(div, args) {

    if (args.solid) {
        div.style.background = 'black';
    }

    if (args.selected) {
        div.classList.add('search__map-marker--selected');
    }
}

function changeSelectedMarker(div) {
    // find all markers
    const searchMap = div.closest('.search__map');

    // remove any selected marker classes
    const jobMarkers = searchMap.querySelectorAll('.search__map-marker--job-location-group.search__map-marker--selected');
    for (let i in jobMarkers) {
        if (typeof jobMarkers[i].classList !== 'undefined') {
            const classList = jobMarkers[i].classList;
            classList.remove('search__map-marker--selected');
        }
    }

    // add selected marker class to this marker
    div.classList.add('search__map-marker--selected');
}

CustomMarker.prototype = new google.maps.OverlayView();

CustomMarker.prototype.draw = function() {

    var self = this;
    var div = this.div;

    if (!div) {
        div = this.div = document.createElement('div');
        div.classList.add('search__map-marker');
        div.classList.add(self.args.class);
        styleMarker(div, self.args);

        if (typeof(self.args.marker_id) !== 'undefined') {
            div.dataset.marker_id = self.args.marker_id;
        }

        if (typeof self.args.clickCallback !== 'undefined'){
            google.maps.event.addDomListener(div, "click", function(event) {
                google.maps.event.trigger(self, "click");
                changeSelectedMarker(div);

                self.args.clickCallback(self.args.groupId);

            });
        }

        var panes = this.getPanes();
        panes.overlayImage.appendChild(div);
    }

    var point = this.getProjection().fromLatLngToDivPixel(this.latlng);

    if (point) {
        div.style.left = (point.x - 10) + 'px';
        div.style.top = (point.y - 20) + 'px';
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