<template>
  <div class="find-a-job"
       v-cloak
       :class="{'find-a-job--job-selected': selectedLocationId, 'jobs-closed' : this.jobsClosed  == 1}"
  >
    <screen-overlay :active="screenOverlayActive" class="screen-overlay--loading-job-site">
      <spinner></spinner>
      <div class="screen-overlay__message">Loading job description…</div>
    </screen-overlay>
    <div v-if="this.jobsClosed == 0" class="find-a-job__header">
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
                'find-a-job__geolocation--is-busy'   : geolocation.isBusy,
                'find-a-job__geolocation--is-active' : searchTerm.isGeolocation
            }">
        <a class="find-a-job__geolocation-button"
           v-if="geolocation.isAvailable"
           @click.stop.prevent="useGeoLocation">
          <svg
            aria-labelledby="geolocationIconTitle geolocationIconDesc"
            class="find-a-job__geolocation-icon"
            viewBox="0 0 20 20"
            x="0px"
            xml:space="preserve"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            y="0px"
          >
            <title id="geolocationIconTitle">Geolocation icon</title>
            <desc id="geolocationIconDesc">A magnifying glass</desc>
            <path class="st0" d="M17.5,9.3c-0.3-3.6-3.2-6.5-6.8-6.8V0H9.3v2.5C5.7,2.9,2.9,5.7,2.5,9.3H0v1.4h2.5c0.3,3.6,3.2,6.5,6.8,6.8V20
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

      <div class="find-a-job__view-list-container" @scroll="onListScroll" ref="list">
        <div v-if="jobFeedError"
             class="find-a-job__job-feed-message find-a-job__job-feed-message--feed-error">
          <div class="find-a-job__job-feed-text-container">
            <p>We can’t currently display job vacancies.</p>
            <p>Try refreshing the screen or searching on <a href="https://justicejobs.tal.net/candidate/jobboard/vacancy/3/adv/?ftq=prison+officer">Justice&nbsp;Jobs</a></p>
          </div>
        </div>

        <div v-if="!jobFeedError && jobFeedLoaded && jobs.length == 0 && this.jobsClosed == 0"
             class="find-a-job__job-feed-message find-a-job__job-feed-message--no-jobs">
          <div class="find-a-job__job-feed-text-container">
            <p>There are no jobs currently available.</p>
            <p>Please check back again soon.</p>
          </div>
        </div>

        <ul class="find-a-job__view-list" ref="list">

          <li class="find-a-job__view-list-element"
              v-if="!jobFeedError && jobs.length > 0"
              :data-location-id="job.locationId"
              v-for="(job, index) in visibleJobs"
              :key="index"
              @click="handleVacancyClick(job.locationId)">
            <job-summary :distance="job.distance"
                         :prison-city="job.prison_location.town"
                         :prison-name="job.prison_name"
                         :salary="job.salary"
                         :selected="job.locationId == selectedLocationId"
                         :title="job.title"
                         :url="job.url"
                         @job-link-clicked="loadJobUrl"
            >
            </job-summary>
          </li>
          <li v-if="jobListMessage">
            <div class="find-a-job__job-list-message" v-html="jobListMessageHTML"></div>
          </li>

          <li v-if="!!this.$slots['jobAlertHTML']">
            <div class="find-a-job__job-alert">
              <slot name="jobAlertHTML"></slot>
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
            <svg
                aria-labelledby="previousPageButtonTitle previousPageButtonDesc"
                viewBox="0 0 32.9 32.9"
                x="0px"
                xml:space="preserve"
                y="0px"
            >
              <title id="previousPageButtonTitle">Previous page button</title>
              <desc id="previousPageButtonDesc">A shape like a less than sign</desc>
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
            <svg
              aria-labelledby="nextPageButtonTitle nextPageButtonDesc"
              viewBox="0 0 32.9 32.9"
              x="0px"
              y="0px"
            >
              <title id="nextPageButtonTitle">Next page button</title>
              <desc id="nextPageButtonDesc">A shape like a greater than sign</desc>
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
  // import NPM packages
  import axios from 'axios';
  import debounce from 'debounce';

  // import libraries
  import CustomMarker from '../js/CustomMarker';

  // import Vue components
  import JobSummary from './JobSummary.vue';
  import ScreenOverlay from '../vue/ScreenOverlay.vue';
  import Spinner from '../vue/Spinner.vue';

  export default {
    components: {
      'job-summary' : JobSummary,
      'screen-overlay' : ScreenOverlay,
      'spinner' : Spinner,
    },
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
      },
      'jobsClosed': {
        default: '',
        type: Boolean
      },
      'jobListMessage': {
        default: '',
        type: String
      },
      'jobListMessageUrl': {
        type: String,
        default: '',
      }
    },

    data() {
      const urlParams = new URLSearchParams(location.search.substring(1));

      const data = {
        deviceIsMobile: false,

        geolocation: {
          isAvailable: false,
          isBusy: false,
        },

        jobFeedLoaded: false,
        jobFeedError: false,

        jobs: [],

        list: {
          activePage: parseInt(urlParams.get('active-page')) || 0,
          resultsPerPage: 5,
          forwardEnabled: true,
          backwardEnabled: false,
          scrollTop: parseInt(urlParams.get('scroll')) || 0,
        },

        jobListMessageHTML : function(jobListMessage) {
          return decodeURIComponent(jobListMessage);
        }(this.jobListMessage),

        searchResults: {
          locations: {},
          orderedLocations: [],
        },

        selectedLocationId: urlParams.get('selected-location') || '',

        searchTerm: {
          input: urlParams.get('search') || '',
          query: urlParams.get('search') || '',
          latlng: {
            lat: parseFloat(urlParams.get('marker-lat')) || false,
            lng: parseFloat(urlParams.get('marker-lng')) || false,
          },
          marker: null,
          isGeolocation: (urlParams.get('geolocation') == 'true') || false,
        },

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
              zoomControl: false,
            }
          },
          currentBounds: (() => {
            if (urlParams.get('lat0') && urlParams.get('lng0') && urlParams.get('lat1') && urlParams.get('lng1')) {
              return new google.maps.LatLngBounds(
                new google.maps.LatLng(urlParams.get('lat0'), urlParams.get('lng0')),
                new google.maps.LatLng(urlParams.get('lat1'), urlParams.get('lng1'))
              );
            }
            else {
              return false;
            }
          })(),
        },

        placeHolderText: 'e.g. SW1A 2LW, Birmingham or Essex',

        mounted: false,

        screenOverlayActive: false,
      };

      switch(this.leg) {
        case 'prison-officer':
          data.vacanciesDataURL = 'https://s3.eu-west-2.amazonaws.com/hmpps-feed-parser/vacancies.json';
          //data.vacanciesDataURL = 'https://s3.eu-west-2.amazonaws.com/hmpps-feed-parser/prod/vacancies.json';
          break;

        case 'youth-custody':
          data.vacanciesDataURL = 'https://s3.eu-west-2.amazonaws.com/hmpps-feed-parser/youth-custody-vacancies.json';
          //data.vacanciesDataURL = 'https://s3.eu-west-2.amazonaws.com/hmpps-feed-parser/prod/youth-custody-vacancies.json';
          break;
      }

      return data;
    },

    methods: {

      alert(title, message){
        alert(title + "\n" + message);
      },

      calculateDistanceBetweenTwoLatLngPoints(lat1, lng1, lat2, lng2) {
        const toRadians = function(degrees) {
          return degrees * (Math.PI / 180);
        };

        const
          R = 6371 // kilometres
            * 0.621371 // kilometres per mile
          ,
          φ1 = toRadians(lat1),
          φ2 = toRadians(lat2),
          Δφ = toRadians(lat2 - lat1),
          Δλ = toRadians(lng2 - lng1)
        ;

        const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
          Math.cos(φ1) * Math.cos(φ2) *
          Math.sin(Δλ / 2) * Math.sin(Δλ / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const d = R * c;
        return d;
      },

      calculateActivePageFromLocationId(locationId) {
        const locations = this.orderedLocations;
        for (let i in locations) {
          i = parseInt(i);
          if (locations[i][0].locationId == locationId) {
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
        this.selectedLocationId = locationId;
        CustomMarker.changeSelectedMarkerByLocationId(locationId);

        const latlng = this.locations[locationId].latlng;
        this.recenterMap(latlng.lat, latlng.lng);
      },

      recenterMap(lat, lng) {
        this.map.object.panTo(new google.maps.LatLng(lat, lng));
      },

      scrollListToTop() {
        this.$refs.list.scrollTo(0, 0);
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
        window.dataLayer.push({
          event: 'map_marker_click',
          prisonName: locationId
        });
        self.focusOnSelectedJob(self, locationId);
        self.focusOnLocation(locationId);
        event.preventDefault();
        event.stopPropagation();
        return false;
      },

      handleVacancyClick(locationId) {
        window.dataLayer.push({
          event: 'job_list_element_click',
          prisonName: locationId
        });
        this.focusOnLocation(locationId);
      },

      onListScroll: debounce(function() {
        this.list.scrollTop = this.$refs.list.scrollTop;
      }, 250),

      addLocationMarkersToMap(locations) {
        for (let locationId in locations) {
          let location = locations[locationId];
          new CustomMarker(
            new google.maps.LatLng(location.latlng.lat, location.latlng.lng),
            this.map.object,
            {
              class: 'find-a-job__map-marker--location',
              locationId: locationId,
              clickCallback: this.handleMapMarkerClick.bind(null, this, locationId),
              prisonName: location.prisonName,
              selected: (locationId == this.selectedLocationId),
            }
          );
        }
      },

      initializeMap() {
        // Decide what the initial bounds for the map should be
        if (this.map.currentBounds) {
          // Zoom the map to current bounds
          this.fitMapToBounds(this.map.currentBounds, 0);
        }
        else if (this.searchTerm.latlng.lat && this.searchTerm.latlng.lng) {
          // Zoom the map to the current search term & nearby results
          this.zoomToNearbyResults(this.searchTerm.latlng.lat, this.searchTerm.latlng.lng);
        }
        else {
          // No bounds or search term were set – zoom to England
          this.zoomToEngland();
        }

        if (this.searchTerm.latlng.lat && this.searchTerm.latlng.lng) {
          this.updateSearchTermMarker(this.searchTerm.latlng.lat, this.searchTerm.latlng.lng);
        }

        // The first map 'idle' event will be triggered whilst initialising the map,
        // by us either panning the map to England or restoring the bounds from the URL state.
        // We don't want to persist this default state.
        // Therefore we don't want to update the current state for this first event.
        let ignoreFirstIdle = true;
        this.map.object.addListener('idle', () => {
          if (ignoreFirstIdle) {
            ignoreFirstIdle = false;
          }
          else {
            this.map.currentBounds = this.map.object.getBounds();
          }
        });
      },

      createMap() {
        this.map.object = new google.maps.Map(
          document.getElementsByClassName('find-a-job__map')[0],
          this.map.googleMaps.options
        );
      },

      loadVacanciesData() {
        axios.get(this.vacanciesDataURL, { responseType: 'json' })
          .then((response) => {
            this.jobFeedLoaded = true;
            this.jobs = response.data;
          })
          .catch((error) => {
            this.jobFeedError = true;
            console.log(error);

            window.dataLayer.push({
              event: 'job_feed_load_error',
              error_message: error.toString()
            });
          });
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
          {'class': 'find-a-job__map-marker--search-term'}
        );
      },

      zoomToNearbyResults(lat, lng) {
        const bounds = new google.maps.LatLngBounds();

        // Add user's search location to the bounds
        bounds.extend({lat: lat, lng: lng});

        // Add closest tenth of search results to the bounds
        const locations = this.orderedLocations;
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

      handleNewSearchLocation(latlng) {
        this.list.activePage = 0;
        this.scrollListToTop();

        if (latlng.lat == false && latlng.lng == false) {
          this.removeSearchTermMarker();
          this.zoomToEngland();
        }
        else {
          this.updateSearchTermMarker(latlng.lat, latlng.lng);
          this.zoomToNearbyResults(latlng.lat, latlng.lng);

          window.dataLayer.push({
            event: 'search_location_change',
            search_is_geolocation: this.searchTerm.isGeolocation
          });
        }
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

      buildQueryString(parameters) {
        const queryString = new URLSearchParams();
        Object.keys(parameters).map(k => {
          queryString.set(k, parameters[k]);
        });
        return queryString.toString();
      },

      resetSearch() {
        this.searchTerm.input = '';
        this.searchTerm.query = '';
        this.searchTerm.latlng = {lat: false, lng: false};
        this.searchTerm.isGeolocation = false;
        this.$refs.searchInput.focus();
      },

      search() {
        this.searchTerm.query = this.searchTerm.input;
        this.selectedLocationId = '';
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

      loadJobUrl(url) {
        this.screenOverlayActive = true;

        // We want to navigate to the job URL once the map has stopped moving
        // and after Vue has finished processing the DOM (on 'next tick')
        // Prior to this, the map's bounds will still be changing and therefore
        // history.replaceState will still be called.
        // It's only safe to navigate away from the page after that has happened.
        google.maps.event.addListenerOnce(this.map.object, 'idle', () => {
          this.$nextTick(() => {
            window.location.href = url;
          });
        });
      }
    },

    watch: {
      'searchTerm.latlng': function(val) {
        this.handleNewSearchLocation(val);
      },

      'jobs': function() {
        this.addLocationMarkersToMap(this.locations);
        this.initializeMap();

        // Scroll the jobs list to the expected scroll position
        // Wrapped in nextTick so it'll perform the scroll once the view has rendered
        this.$nextTick(() => {
          this.$refs.list.scrollTo(0,this.list.scrollTop);
        });
      },

      'currentState': function(state) {
        const queryString = this.buildQueryString(state);
        const stateUrl = (queryString) ? '?' + queryString : window.location.pathname;
        window.history.replaceState(null, null, stateUrl);
      }
    },

    computed: {
      numberOfResultPages: function () {
        return Math.ceil(this.orderedLocations.length / this.list.resultsPerPage);
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

      locations: function() {
        const jobs = this.jobsWithDistance;
        const locations = {};

        for (let i = 0; i < jobs.length; i++) {
          const locationId = jobs[i].prison_name;
          jobs[i].locationId = locationId;

          if (typeof locations[locationId] == 'undefined') {
            locations[locationId] = {
              prisonName: jobs[i].prison_name,
              latlng: {
                lat: jobs[i].prison_location.lat,
                lng: jobs[i].prison_location.lng
              },
              jobs: [jobs[i]]
            };
          } else {
            locations[locationId].jobs.push(jobs[i]);
          }
        }

        return locations;
      },

      orderedLocations: function() {
        const sortByDistance = function (a, b) {
          return a[0].distance - b[0].distance;
        };

        const sortByTownName = function (a, b) {
          const aTown = a[0].prison_location.town.toLowerCase(),
            bTown = b[0].prison_location.town.toLowerCase();
          return aTown.localeCompare(bTown);
        };

        const locations = [];
        for (const id in this.locations) {
          let jobs = this.locations[id].jobs;
          locations.push(jobs);
        }

        const searchTermIsSet = (this.searchTerm.latlng.lat && this.searchTerm.latlng.lng);
        if (searchTermIsSet) {
          locations.sort(sortByDistance);
        }
        else {
          locations.sort(sortByTownName);
        }

        return locations;
      },

      jobsWithDistance: function() {
        const searchLat = this.searchTerm.latlng.lat,
              searchLng = this.searchTerm.latlng.lng,
              searchLocationIsSet = (searchLat && searchLng);

        this.jobs.map((job) => {
          if (searchLocationIsSet) {
            let lat = job.prison_location.lat;
            let lng = job.prison_location.lng;

            job.distance = this.calculateDistanceBetweenTwoLatLngPoints(
              lat, lng,
              searchLat, searchLng
            );
          }
          else {
            job.distance = false;
          }
        });

        return this.jobs;
      },

      visibleJobs: function() {
        let selectedLocations = null;
        if (this.deviceIsMobile) {
          selectedLocations = this.orderedLocations.slice().splice(
            this.list.resultsPerPage * this.list.activePage,
            this.list.resultsPerPage
          );
        } else {
          selectedLocations = this.orderedLocations;
        }

        const results = [];
        for (const i in selectedLocations) {
          for (const j in selectedLocations[i]) {
            results.push(selectedLocations[i][j]);
          }
        }

        return results;
      },

      jobsAvailable: function() {
        return this.jobs.length + ' ' + this.jobTitle + ' jobs available: ';
      },

/*      jobListMessageUrlWithSearchTerm: function() {
        let queryString = '';
        if (this.currentState['marker-lat'] && this.currentState['marker-lng']) {
          queryString = '?' + this.buildQueryString({
            'search': this.currentState['search'],
            'geolocation': this.currentState['geolocation'],
            'marker-lat': this.currentState['marker-lat'],
            'marker-lng': this.currentState['marker-lng'],
          });
        }
        return this.jobListMessageUrl + queryString;
      },*/

      currentState: function() {
        const currentState = {
          'search': this.searchTerm.query,
          'geolocation': this.searchTerm.isGeolocation,
          'selected-location': this.selectedLocationId,
          'active-page': this.list.activePage,
          'scroll': this.list.scrollTop,
        };

        if (this.searchTerm.latlng.lat && this.searchTerm.latlng.lng) {
          currentState['marker-lat'] = this.searchTerm.latlng.lat;
          currentState['marker-lng'] = this.searchTerm.latlng.lng;
        }

        if (this.map.currentBounds) {
          currentState['lat0'] = this.map.currentBounds.getSouthWest().lat();
          currentState['lng0'] = this.map.currentBounds.getSouthWest().lng();
          currentState['lat1'] = this.map.currentBounds.getNorthEast().lat();
          currentState['lng1'] = this.map.currentBounds.getNorthEast().lng();
        }
        return currentState;
      },
    },

    created() {
      if ("geolocation" in navigator) {
        this.geolocation.isAvailable = true;
      }

      const updateDeviceIsMobile = (query) => {
        this.deviceIsMobile = !query.matches;
      };
      const nonMobile = window.matchMedia('(min-width: 768px)');
      updateDeviceIsMobile(nonMobile);
      nonMobile.addListener(updateDeviceIsMobile);
    },

    mounted() {
      this.createMap();
      this.loadVacanciesData();

      window.addEventListener('pagehide', ()=>{

        /*
         * Browsers like Firefox seem to reload the previous state of a page when using the back button
         * instead making a new request to the server to rebuild the previous page.
         *
         * Therefore the loading spinner needs to be disabled when exiting the page,
         * to make sure it isn't still active when the user uses the back button
         */
        this.screenOverlayActive = false;
      });
    }
  }
</script>
