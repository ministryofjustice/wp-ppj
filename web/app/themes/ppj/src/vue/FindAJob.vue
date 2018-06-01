<template>

  <div class="find-a-job"
       v-cloak
       :class="{'find-a-job--job-selected': selectedLocationId}"
  >
    <div class="find-a-job__header">
      <h2 class="find-a-job__title">{{ titleText }}</h2>
      <p class="find-a-job__prompt">Enter location (postcode, town or region)</p>
      <form class="find-a-job__form" @submit.prevent="" @reset.prevent="resetSearch">
        <input type="search"
               class="find-a-job__input"
               :placeholder="placeHolderText"
               v-model="searchTerm.input"
               ref="searchInput"
               tabindex="1" />
        <div class="find-a-job__button-clear-search-container">
          <button class="find-a-job__button-clear-search"
                  type="reset"
                  :class="{'find-a-job__button-clear-search--enabled': searchTerm.input}"
          >&#10005;</button>
        </div>

        <button class="find-a-job__button-search"
                type="submit"
                :disabled="searchTerm.input == ''"
                @click="search">
          <div class="find-a-job__button-search-circle"></div>
          <div class="find-a-job__button-search-rectangle"></div>
        </button>
      </form>

      <div class="find-a-job__geolocation"
           :class="{
                'find-a-job__geolocation--is-busy': (geolocation.isBusy == true),
                'find-a-job__geolocation--is-active': (searchTerm.isGeolocation == true)
            }">
        <a class="find-a-job__geolocation-button"
           v-if="geolocation.isAvailable"
           @click.stop.prevent="useGeoLocation">
          <svg version="1.1"
               id="Layer_1"
               xmlns="http://www.w3.org/2000/svg"
               xmlns:xlink="http://www.w3.org/1999/xlink"
               x="0px"
               y="0px"
               viewBox="0 0 20 20"
               xml:space="preserve"
               class="find-a-job__geolocation-icon"
          ><path class="st0" d="M17.5,9.3c-0.3-3.6-3.2-6.5-6.8-6.8V0H9.3v2.5C5.7,2.9,2.9,5.7,2.5,9.3H0v1.4h2.5c0.3,3.6,3.2,6.5,6.8,6.8V20
              h1.4v-2.5c3.6-0.3,6.5-3.2,6.8-6.8H20V9.3H17.5z M16.1,10c0,0.2,0,0.5,0,0.7c-0.3,2.8-2.6,5.1-5.4,5.4c-0.2,0-0.5,0-0.7,0
              s-0.5,0-0.7,0c-2.8-0.3-5.1-2.6-5.4-5.4c0-0.2,0-0.5,0-0.7s0-0.5,0-0.7c0.3-2.8,2.6-5.1,5.4-5.4c0.2,0,0.5,0,0.7,0s0.5,0,0.7,0
              c2.8,0.3,5.1,2.6,5.4,5.4C16.1,9.5,16.1,9.8,16.1,10z"/>
            <path class="st0" d="M10.6,6.9c-0.2,0-0.4-0.1-0.6-0.1c-0.2,0-0.4,0-0.6,0.1C8.2,7.2,7.2,8.2,6.9,9.4c0,0.2-0.1,0.4-0.1,0.6
              s0,0.4,0.1,0.6c0.2,1.3,1.2,2.2,2.5,2.5c0.2,0,0.4,0.1,0.6,0.1c0.2,0,0.4,0,0.6-0.1c1.3-0.2,2.2-1.2,2.5-2.5
              c0-0.2,0.1-0.4,0.1-0.6s0-0.4-0.1-0.6C12.8,8.2,11.8,7.2,10.6,6.9z"/>
          </svg>
          <span>Use my current location</span>
        </a>
      </div>
    </div>

    <div class="find-a-job__map-container">
      <div class="find-a-job__map-zoom-button-container">
        <div class="find-a-job__map-button-zoom find-a-job__map-button-zoom--in"
             @click.stop.prevent="zoomBy(1)"
        >
          <svg class="find-a-job__map-button-zoom-icon"
               viewBox="0 0 32 32"
               style="enable-background:new 0 0 32 32;">
            <polygon points="22,14.9 17.1,14.9 17.1,10 14.9,10 14.9,14.9 10,14.9 10,17.1 14.9,17.1 14.9,22 17.1,22 17.1,17.1 22,17.1 "/>
          </svg>
        </div>
        <div class="find-a-job__map-button-zoom find-a-job__map-button-zoom--out"
             @click.stop.prevent="zoomBy(-1)"
        >
          <div class="find-a-job__map-button-zoom-image-container"></div>
          <svg class="find-a-job__map-button-zoom-icon"
               viewBox="0 0 32 32">
            <polygon class="zoom-icon" points="22,14.9 10,14.9 10,17.1 22,17.1 " />
          </svg>
        </div>
      </div>

      <div class="find-a-job__map"
           @click.stop.prevent=""
      ></div>
    </div>

    <div class="find-a-job__jobs">
      <div v-if="!jobFeedError && jobs.length > 0"
           class="find-a-job__jobs-available-container">
        <div class="find-a-job__jobs-available">{{ jobsAvailable }}</div>
      </div>

      <div class="find-a-job__view-list-container" v-on:scroll="handleListScroll" ref="list">
        <div v-if="jobFeedError"
             class="find-a-job__job-feed-message find-a-job__job-feed-message--feed-error">
          <div class="find-a-job__job-feed-text-container">
            <p>We can’t currently display job vacancies.</p>
            <p>Try refreshing the screen or searching on <a href="https://justicejobs.tal.net/candidate/jobboard/vacancy/3/adv/?ftq=prison+officer">Justice&nbsp;Jobs</a></p>
          </div>
        </div>

        <div v-if="!jobFeedError && jobFeedLoaded && jobs.length == 0"
             class="find-a-job__job-feed-message find-a-job__job-feed-message--no-jobs">
          <div class="find-a-job__job-feed-text-container">
            <p>There are no jobs currently available.</p>
            <p>Please check back again soon.</p>
          </div>
        </div>

        <ul class="find-a-job__view-list"ref="list">

          <li class="find-a-job__view-list-element"
              v-if="!jobFeedError && jobs.length > 0"
              :data-location-id="job.locationId"
              v-for="(job, index) in visibleJobs"
              :key="index"
              v-on:click="handleVacancyClick(job.locationId)">
            <job-summary :distance="job.distance"
                         :distance-time="job.distanceTime"
                         :position="job.role"
                         :prison-city="job.prison_location.town"
                         :prison-name="job.prison_name"
                         :prison-page-link="job.url"
                         :salary="job.salary"
                         :selected="job.locationId == selectedLocationId"
                         :title="job.title"
                         :url="job.url"
            >
            </job-summary>
          </li>
          <li v-if="jobListMessage">
            <div class="find-a-job__job-list-message">
              <a :href="jobListMessageUrlWithSearchTerm">{{ jobListMessage }}</a>
            </div>
          </li>
        </ul>
        <div class="find-a-job__pagination"
             v-if="deviceIsMobile && (numberOfResultPages > 1)">
          <a class="find-a-job__pagination-skip-link"
             :class="{'find-a-job__pagination-skip-link--enabled': (backwardEnabled == true)}"
             @click.stop.prevent="showFirstPage">
            first</a>
          <button class="find-a-job__pagination-direction"
                  :class="{'find-a-job__pagination-direction--enabled': (backwardEnabled == true)}"
                  @click.stop.prevent="showPreviousPage">
            <svg x="0px" y="0px"
                 viewBox="0 0 32.9 32.9"
                 xml:space="preserve"
            >
                <path class="st0" d="M21.2,11.7v0.9c0,0.2-0.1,0.3-0.2,0.3l-7.5,3.4c-0.1,0-0.1,0.1,0,0.1l7.5,3.4c0.1,0.1,0.2,0.2,0.2,0.3v0.9
                  c0,0.2-0.1,0.3-0.3,0.2l-8.9-4.1c-0.1-0.1-0.2-0.2-0.2-0.4V16c0-0.2,0.1-0.3,0.2-0.4l8.9-4.1C21.1,11.4,21.2,11.5,21.2,11.7z"/>
              </svg>
          </button>
          <div class="find-a-job__pagination-page-numbers-container">
            <div class="find-a-job__pagination-current-page-number">
              {{list.activePage + 1}}
            </div>
            <div class="find-a-job__pagination-of">of</div>
            <div class="find-a-job__pagination-total-pages">
              {{numberOfResultPages}}
            </div>
          </div>
          <button class="find-a-job__pagination-direction"
                  :class="{'find-a-job__pagination-direction--enabled': (forwardEnabled == true)}"
                  @click.stop.prevent="showNextPage">
            <svg x="0px"
                 y="0px"
                 viewBox="0 0 32.9 32.9"
            >
                  <path class="st0" d="M11.7,21.2v-0.9c0-0.2,0.1-0.3,0.2-0.3l7.5-3.4c0.1,0,0.1-0.1,0-0.1L11.9,13c-0.1-0.1-0.2-0.2-0.2-0.3v-0.9
                    c0-0.2,0.1-0.3,0.3-0.2l8.9,4.1c0.1,0.1,0.2,0.2,0.2,0.4v0.9c0,0.2-0.1,0.3-0.2,0.4l-8.9,4.1C11.9,21.5,11.7,21.4,11.7,21.2z"/>

            </svg>
          </button>
          <a class="find-a-job__pagination-skip-link"
             :class="{'find-a-job__pagination-skip-link--enabled': (forwardEnabled == true)}"
             @click.stop.prevent="showLastPage"
          >last</a>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios';
  import CustomMarker from '../js/CustomMarker';
  import dummyJobs from '../js/dummyJobs';

  export default {

    props: {
      'title': {
        default: '',
        type: String
      },
      'jobTitle': {
        default: '',
        type: String
      },
      'leg': {
        default: '',
        type: String
      }
    },

    data() {
      const previousState = this.getUrlParamsAsJson();

      const data = {
        previousState : previousState,

        deviceIsMobile: false,

        geolocation: {
          isAvailable: false,
          isBusy: false,
        },

        jobFeedLoaded: false,
        jobFeedError: false,

        jobs: [],

        list: {
          activePage: parseInt(previousState['active-page']) || 0,
          resultsPerPage: 5,
          forwardEnabled: true,
          backwardEnabled: false,
          scrollTop: parseInt(previousState['scroll']) || 0,
        },

        searchResults: {
          locations: {},
          orderedLocations: [],
        },

        selectedLocationId: previousState['selected-location'] || '',

        searchTerm: {
          input: previousState['search'] || '',
          query: previousState['search'] || '',
          latlng: {
            lat: previousState['marker-lat'] || '',
            lng: previousState['marker-lng'] || ''
          },
          marker: null,
          isGeolocation: (previousState.geolocation == 'true') || false,
          doneInitialZoom: false
        },

        autocomplete: null,

        map: {
          maxZoom: 18,
          minZoom: 4,
          googleMaps: {
            options: {
              disableDefaultUI: false,
              streetViewControl: false,
              fullscreenControl: false,
              mapTypeControl: false,
              gestureHandling: 'greedy',
              zoomControl: false
            }
          }
        },

        titleText: this.title,

        placeHolderText: 'e.g. SW1A 2LW, Birmingham or Essex',

        mounted: false,
      };

      data.jobListMessage = false;
      data.jobListMessageUrl = '';

      switch(this.leg) {
        case 'prison-officer':
          data.vacanciesDataURL = 'https://s3.eu-west-2.amazonaws.com/hmpps-feed-parser/staging/vacancies.json';
          break;

        case 'youth-custody':
          data.vacanciesDataURL = 'https://s3.eu-west-2.amazonaws.com/hmpps-feed-parser/youth-custody-vacancies.json';
          data.jobListMessage = 'See prison officer jobs working with adult offenders';
          data.jobListMessageUrl = '/prison-officer/find-a-job';
          break;
      }

      return data;
    },

    methods: {

      alert(title, message){
        alert(title + "\n" + message);
      },

      isDeviceMobile() {
        return (window.innerWidth < 768);
      },

      toRadians(degrees) {
        return degrees * (Math.PI / 180);
      },

      calculateDistanceBetweenTwoLatLngPoints(lat1, lng1, lat2, lng2) {
        const
          R = 6371 // kilometres
            * 0.621371 // kilometres per mile
          ,
          φ1 = this.toRadians(lat1),
          φ2 = this.toRadians(lat2),
          Δφ = this.toRadians(lat2 - lat1),
          Δλ = this.toRadians(lng2 - lng1)
        ;

        const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
          Math.cos(φ1) * Math.cos(φ2) *
          Math.sin(Δλ / 2) * Math.sin(Δλ / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const d = R * c;
        return d;
      },

      updateSelectedLocationId(locationId) {
        this.selectedLocationId = locationId;
      },

      calculateActivePageFromLocationId(locationId) {
        const groups = this.searchResults.orderedLocations;
        for (let i in groups) {
          i = parseInt(i);
          if (groups[i][0].locationId == locationId) {
            this.list.activePage = Math.floor(i / this.list.resultsPerPage);
            return;
          }
        }
      },

      zoomTo(level) {
        if (level < this.map.minZoom) level = this.map.minZoom;
        if (level > this.map.maxZoom) level = this.map.maxZoom;
        this.map.object.setZoom(level);
      },

      zoomBy(amount) {
        this.zoomTo(this.map.object.getZoom() + amount);
      },

      focusOnLocation(locationId) {
        this.updateSelectedLocationId(locationId);
        CustomMarker.changeSelectedMarkerByLocationId(locationId);

        const coords = this.getLocationLatLng(locationId);
        this.recenterMap(coords.lat, coords.lng);

        if (
          !this.searchTerm.isGeolocation &&
          !this.searchTerm.query &&
          !this.searchTerm.doneInitialZoom
        ) {
          this.searchTerm.doneInitialZoom = true;
        }
      },

      recenterMap(lat, lng) {
        this.map.object.panTo(new google.maps.LatLng(lat, lng));
      },

      scrollListToTop() {
        this.$refs.list.scrollTo(0,this.list.scrollTop);
      },

      scrollListToJob(locationId) {
        const selectedJobs = document.querySelector(`.find-a-job__view-list-element[data-location-id='${locationId}']`);
        if (selectedJobs) {
          selectedJobs.scrollIntoView({ behavior: 'smooth' });
        }
      },

      focusOnSelectedJob(self, locationId) {
        if (self.deviceIsMobile) {
          self.calculateActivePageFromLocationId(locationId);
        } else {
          self.scrollListToJob(locationId);
        }
      },

      handleMapMarkerClick(self, locationId, event) {
        self.focusOnSelectedJob(self, locationId);
        self.focusOnLocation(locationId);
        event.preventDefault();
        event.stopPropagation();
        return false;
      },

      handleVacancyClick(locationId) {
        this.focusOnLocation(locationId);
      },

      handleListScroll() {
        clearTimeout(this.handleListScrollTimeoutId);
        this.handleListScrollTimeoutId = setTimeout(()=> {
          this.persistStateToHistory();
        }, 100)
      },

      updateMapWithLocationMarkers(locations) {

        // create an arguments object for each marker
        const markerArgs = [];
        for (let locationId in locations) {
          markerArgs.push({
            class: 'find-a-job__map-marker--location',
            solid: true,
            amount: locations[locationId].jobs.length,
            locationId: locationId,
            clickCallback: this.handleMapMarkerClick.bind(null, this, locationId),
            prisonName: locations[locationId].prisonName
          });
        }

        // iterate over the markerArgs elements and create a map marker for each one
        for (let i in markerArgs) {
          if (markerArgs[i].locationId == this.selectedLocationId) {
            markerArgs[i].selected = true;
          }

          const latlng = this.getLocationLatLng(markerArgs[i].locationId);
          const googleMapLatLng = new google.maps.LatLng(latlng.lat, latlng.lng);

          new CustomMarker(
            googleMapLatLng,
            this.map.object,
            markerArgs[i]
          );
        }
      },

      getLocationLatLng(locationId) {
        return {
          lat: this.searchResults.locations[locationId].jobs[0].prison_location.lat,
          lng: this.searchResults.locations[locationId].jobs[0].prison_location.lng
        }
      },

      sortLocationsByDistance() {
        this.searchResults.orderedLocations.sort(function (a, b) {
          return a[0].distance - b[0].distance;
        });
      },

      sortLocationsByTownName() {
        this.searchResults.orderedLocations.sort(function (a, b) {
          const
            aTown = a[0].prison_location.town,
            bTown = b[0].prison_location.town
          ;
          if (aTown < bTown) {
            return -1;
          } else {
            if (aTown > bTown) {
              return 1;
            } else {
              return 0;
            }
          }
        });
      },

      updateJobsDistance(lat, lng) {

        // calculate distance
        for (let i = 0; i < this.jobs.length; i++) {

          const newDistance = this.calculateDistanceBetweenTwoLatLngPoints(
            lat,
            lng,
            this.jobs[i].prison_location.lat,
            this.jobs[i].prison_location.lng
          );

          this.jobs[i].distance = newDistance;
        }

        this.searchResults.orderedLocations = [];
        for (const id in this.searchResults.locations) {
          const locationLatLng = this.getLocationLatLng(id);
          const newDistance = this.calculateDistanceBetweenTwoLatLngPoints(
            lat,
            lng,
            locationLatLng.lat,
            locationLatLng.lng
          );
          this.searchResults.locations[id].distance = newDistance;
          this.searchResults.orderedLocations.push(this.searchResults.locations[id].jobs);
        }

        this.sortLocationsByDistance();
      },

      createLocations() {

        const jobs = this.jobs;
        const locations = {};
        const previousSearchTermMarkerFound = (this.searchTerm.latlng.lat && this.searchTerm.latlng.lng);
        let closestLocationDistance = Number.MAX_SAFE_INTEGER;
        let closestLocationId = null;

        // update the jobs distance if we already have a search term marker
        if (previousSearchTermMarkerFound) {
          this.updateJobsDistance(this.searchTerm.latlng.lat, this.searchTerm.latlng.lng);
        }

        // iterate over the jobs and put them in the correct location
        for (let i = 0; i < jobs.length; i++) {
          const locationId = jobs[i].prison_name.replace(/ /g, '-').toLowerCase();

          if (typeof locations[locationId] == 'undefined') {
            locations[locationId] = {
              prisonName: jobs[i].prison_name,
              jobs: [jobs[i]]
            };
          } else {
            locations[locationId].jobs.push(jobs[i]);
          }

          jobs[i].locationId = locationId;

          if (jobs[i].distance < closestLocationDistance) {
            closestLocationDistance = jobs[i].distance;
            closestLocationId = locationId;
          }
        }
        this.searchResults.locations = locations;

        // initialize orderedLocations array
        this.searchResults.orderedLocations = [];
        for (const id in this.searchResults.locations) {
          this.searchResults.orderedLocations.push(this.searchResults.locations[id].jobs);
        }

        // initially sort the job location groups by town name
        if (previousSearchTermMarkerFound) {
          this.sortLocationsByDistance();
        } else {
          this.sortLocationsByTownName();
        }

        this.updateMapWithLocationMarkers(this.searchResults.locations);
      },

      createMap() {
        this.map.object = new google.maps.Map(
          document.getElementsByClassName('find-a-job__map')[0],
          this.map.googleMaps.options
        );

        // add one time function to be fired after the map has loaded
        google.maps.event.addListenerOnce(this.map.object, 'idle', () => {

          // decide what the initial bounds for the map should be
          if(this.previousState.lat0 && this.previousState.lng0 && this.previousState.lat1 && this.previousState.lng1) {
            const bounds = new google.maps.LatLngBounds(
              new google.maps.LatLng(this.previousState.lat0, this.previousState.lng0),
              new google.maps.LatLng(this.previousState.lat1, this.previousState.lng1)
            );
            this.fitMapToBounds(bounds, 0);
          } else {
            this.zoomToEngland();
          }

          // Use the bounds_changed event listener to persist the state after the map has fully loaded.
          // Only persist the state 100 milliseconds after last bounds_changed event has fired.
          // The first bounds_changed event will be ignored as it is always fired on map creation
          // and we don't want to persist the default state
          let previousTimeoutId = 0,
              ignoreBoundsChanged = true;

          this.map.object.addListener('bounds_changed', () => {
            if (ignoreBoundsChanged) {
              ignoreBoundsChanged = false;
            } else {
              clearTimeout(previousTimeoutId);
              previousTimeoutId = setTimeout(()=>{
                this.persistStateToHistory();
              }, 100);
            }
          });

          // if available recreate the searchTerm marker from the previous state
          if (this.previousState['marker-lat'] && this.previousState['marker-lng']) {
            this.updateSearchTermMarker(this.previousState['marker-lat'], this.previousState['marker-lng']);
          }

        });
      },

      initAutocomplete() {
        this.autocomplete = new google.maps.places.Autocomplete(this.$refs.searchInput, {
          componentRestrictions: {country: 'uk'}
        });

        // Bind the map's bounds (viewport) property to the autocomplete object,
        // so that the autocomplete requests use the current map bounds for the
        // bounds option in the request.
        this.autocomplete.bindTo('bounds', this.map.object);

        this.autocomplete.addListener('place_changed', this.autocompletePlaceChanged);
      },

      autocompletePlaceChanged() {
        const place = this.autocomplete.getPlace();

        if (!place.geometry) {
          // User entered the name of a Place that was not suggested and
          // pressed the Enter key, or the Place Details request failed.
          // Revert to a regular geolocation search
          this.searchTerm.input = place.name;
          return this.search();
        }

        const placeName = place.formatted_address.replace(/, UK$/, '');
        this.searchTerm.input = this.searchTerm.query = placeName;
        this.searchTerm.isGeolocation = false;
        this.searchTerm.latlng = {
          lat: place.geometry.location.lat(),
          lng: place.geometry.location.lng()
        };
      },

      fitMapToBounds(latLngBounds, padding=null) {
        this.map.object.fitBounds(latLngBounds, padding);
      },

      zoomToEngland() {
        const england = new google.maps.LatLngBounds(
          new google.maps.LatLng(49.8647411, -6.418545799999947),
          new google.maps.LatLng(55.81165979999999, 1.7629159000000527)
        );
        this.fitMapToBounds(england, 0);
      },

      removeSearchTermMarker() {
        if (this.searchTerm.marker) {
          this.searchTerm.marker.remove();
        }
      },

      updateSearchTermMarker(lat, lng) {
        this.removeSearchTermMarker();

        this.searchTerm.marker = new CustomMarker(
          new google.maps.LatLng(lat, lng),
          this.map.object,
          {class: 'find-a-job__map-marker--search-term'}
        );
      },

      zoomToNearbyResults(lat, lng) {

        var bounds = new google.maps.LatLngBounds();

        // Add user's search location to the bounds
        bounds.extend({lat: lat, lng: lng});

        // Add closest tenth of search results to the bounds
        const locations = this.searchResults.orderedLocations;
        const minVisible = Math.ceil(locations.length / 10);
        const boundLocations = locations.slice(0, minVisible);
        boundLocations.forEach((location) => {
          let ll = {
            lat: location[0].prison_location.lat,
            lng: location[0].prison_location.lng
          };
          bounds.extend(ll);
        });

        this.fitMapToBounds(bounds);
      },

      setWindowHistory(json) {
        const paramString = this.convertJsonToUrlParameterString(json);

        // replaceState does nothing if passed an empty string as its 3rd argument
        // passing window.location.pathname will remove all url parameters
        window.history.replaceState(null, null, (paramString) ? paramString : window.location.pathname);
      },

      modifyPersistedStateParam(paramName, val) {
        const state = this.getUrlParamsAsJson();
        if (val) {
          state[paramName] = val;
        } else {
          delete state[paramName];
        }
        this.setWindowHistory(state);
      },

      persistStateToHistory() {
        const bounds = this.map.object.getBounds();
        const currentState = {
          'search': this.searchTerm.query,
          'geolocation': this.searchTerm.isGeolocation,
          'lat0': bounds.getSouthWest().lat(),
          'lng0': bounds.getSouthWest().lng(),
          'lat1': bounds.getNorthEast().lat(),
          'lng1': bounds.getNorthEast().lng(),
          'selected-location': this.selectedLocationId,
          'active-page': this.list.activePage,
          'scroll': document.getElementsByClassName('find-a-job__view-list-container')[0].scrollTop,
        };
        if (this.searchTerm.marker) {
          currentState['marker-lat'] = this.searchTerm.marker.latlng.lat();
          currentState['marker-lng'] = this.searchTerm.marker.latlng.lng();
        }
        this.setWindowHistory(currentState);
      },

      handleNewSearchLocation(latlng) {
        this.list.activePage = 0;
        this.searchTerm.doneInitialZoom = false;

        if (latlng == null) {
          this.removeSearchTermMarker();
          this.zoomToEngland();
          // TODO remove distances from list and reorder to default
        }
        else {
          this.updateSearchTermMarker(latlng.lat, latlng.lng);
          this.updateJobsDistance(latlng.lat, latlng.lng);
          this.zoomToNearbyResults(latlng.lat, latlng.lng);

          window.dataLayer.push({
            event: 'search_location_change',
            search_is_geolocation: this.searchTerm.isGeolocation
          });
        }
        this.scrollListToTop();
      },

      processGeocoderResults(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          this.searchTerm.latlng = {
            lat: results[0].geometry.location.lat(),
            lng: results[0].geometry.location.lng()
          };
        } else {
          let msg = '';
          if (status === 'ZERO_RESULTS') {
            this.alert('No results found for ' + this.searchTerm.query, 'Try searching again');
          } else {
            // TODO need proper content for this:
            msg = 'Problem communicating with Google Geocoder API, ' + status ;
            this.alert('', msg);
          }
          console.error(status);
          console.dir(results);
        }
      },

      getUrlParamsAsJson() {
        const search = location.search.substring(1);
        let result = null;
        if (search) {
          result = JSON.parse(
            '{"'
            + decodeURI(search)
              .replace(/"/g, '\\"')
              .replace(/&/g, '","')
              .replace(/=/g, '":"')
            + '"}'
          );
        } else {
          result = {};
        }
        return result;
      },

      convertJsonToUrlParameterString(json) {
        const paramsString = Object.keys(json).map(k => {
          return encodeURI(k)
            + '='
            + encodeURI(json[k])
        }).join('&');
        return (paramsString) ? '?' + paramsString : '';
      },

      resetSearch() {
        this.searchTerm.input = '';
        this.modifyPersistedStateParam('search', '');
        this.searchTerm.query = '';
        this.searchTerm.latlng = null;
        this.searchTerm.isGeolocation = false;
        this.$refs.searchInput.focus();
        this.removeSearchTermMarker();
      },

      search() {
        this.searchTerm.query = this.searchTerm.input;
        this.searchTerm.latlng = null;
        this.updateSelectedLocationId('');
        this.searchTerm.isGeolocation = false;

        if (this.searchTerm.query) {
          new google.maps.Geocoder().geocode(
            {'address': this.searchTerm.query + ', UK'},
            this.processGeocoderResults
          );
          this.$refs.searchInput.blur();
        }
      },

      useGeoLocation() {
        this.geolocation.isBusy = true;
        navigator.geolocation.getCurrentPosition(
          position => {
            this.searchTerm.latlng = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            this.searchTerm.query = '';
            this.searchTerm.input = '';
            this.searchTerm.isGeolocation = true;
            this.geolocation.isBusy = false;
          },
          error => {
            if (typeof error.code !== 'undefined' && error.code === 1) {
              this.alert('Sorry, we can’t detect your location.', 'Try searching by postcode, town or region.');
            } else {
              this.alert('', 'Problem using Geolocation');
            }
            console.dir(error);
          }
        );
      },

      showPage(i) {
        this.list.activePage = i;
        this.persistStateToHistory();
      },

      showNextPage() {
        if (this.list.activePage < (this.numberOfResultPages - 1)) {
          this.showPage(this.list.activePage + 1);
        }
      },

      showPreviousPage() {
        if (this.list.activePage > 0) {
          this.showPage(this.list.activePage - 1);
        }
      },

      showFirstPage() {
        this.showPage(0);
      },

      showLastPage() {
        this.showPage(this.numberOfResultPages - 1);
      },

      handleGotVacanciesData(response) {
        this.jobFeedLoaded = true;
        this.jobs = response.data;
      },

      updateIsDeviceMobile() {
        this.deviceIsMobile = this.isDeviceMobile();
      },

      handleScreenResize() {
        this.updateIsDeviceMobile();
      },
    },

    watch: {
      'searchTerm.latlng': function(val) {
        this.handleNewSearchLocation(val);
      },
    },

    computed: {
      numberOfResultPages: function () {
        const num = Math.ceil(this.searchResults.orderedLocations.length / this.list.resultsPerPage);
        return num;
      },

      defaultSearchResults: function () {
        return this.jobs;
      },

      backwardEnabled: function () {
        return (this.list.activePage > 0);
      },

      forwardEnabled: function () {
        return (this.list.activePage < (this.numberOfResultPages - 1));
      },

      visibleJobs: function() {
        if (this.mounted) {

          if (Object.keys(this.searchResults.locations).length === 0) {
            this.createLocations();
          }

          //this.focusOnSelectedJob(this, this.searchResults.selectedLocationId);
          let selectedLocations = null;
          if (this.deviceIsMobile) {
            selectedLocations = this.searchResults.orderedLocations.slice().splice(
              this.list.resultsPerPage * this.list.activePage,
              this.list.resultsPerPage
            );
          } else {
            selectedLocations = this.searchResults.orderedLocations;
          }

          const results = [];
          for (const i in selectedLocations) {
            for (const j in selectedLocations[i]) {
              results.push(selectedLocations[i][j]);
            }
          }

          return results;
        }
      },

      jobsAvailable: function() {
        return this.jobs.length + ' ' + this.jobTitle + ' jobs available: ';
      },

      jobListMessageUrlWithSearchTerm: function() {
        let params = '';
        if (
          this.searchTerm.query
          && this.searchTerm.latlng.lat
          && this.searchTerm.latlng.lng
        ) {
          params = this.convertJsonToUrlParameterString({
            'search': this.searchTerm.query,
            'marker-lat': this.searchTerm.latlng.lat,
            'marker-lng': this.searchTerm.latlng.lng,
          });
        }

        return this.jobListMessageUrl + params;
      }
    },

    created() {
      this.updateIsDeviceMobile();

      if ("geolocation" in navigator) {
        this.geolocation.isAvailable = true;
      }
    },

    mounted() {
      const self = this;
      this.createMap();
      this.initAutocomplete();
      this.mounted = true;

      window.addEventListener('resize', this.handleScreenResize);

      setTimeout(()=>{
        this.$refs.list.scrollTo(0,this.list.scrollTop);
      }, 50);

      axios.get(this.vacanciesDataURL, { responseType: 'json' })
        .then(self.handleGotVacanciesData)
        .catch((error) => {
          this.jobFeedError = true;
          console.log(error);

          window.dataLayer.push({
            event: 'job_feed_load_error',
            error_message: error.toString()
          });
        });
    }
  }
</script>
