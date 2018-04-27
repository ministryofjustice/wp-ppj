<template>

  <div class="find-a-job"
       v-cloak
       :class="{'find-a-job--job-selected': searchResults.selectedJobLocationGroupId}"
  >
    <div class="find-a-job__header">
      <h2 class="find-a-job__title">{{ titleText }}</h2>
      <p class="find-a-job__prompt">Enter location (postcode, town or region)</p>
      <form class="find-a-job__form" @submit.prevent="search" @reset.prevent="resetSearch">
        <input type="text"
               class="find-a-job__input"
               :placeholder="placeHolderText"
               v-model="searchTerm.input"
               ref="searchInput" />
        <div class="find-a-job__button-clear-search-container">
          <button class="find-a-job__button-clear-search"
                  type="reset"
                  :class="{'find-a-job__button-clear-search--enabled': searchTerm.input}"
          >&#10005;</button>
        </div>

        <button class="find-a-job__button-search"
                type="submit"
                :disabled="searchTerm.input == ''">
          <div class="find-a-job__button-search-circle"></div>
          <div class="find-a-job__button-search-rectangle"></div>
        </button>
      </form>

      <div class="find-a-job__geolocation"
           :class="{
                'find-a-job__geolocation--is-busy': (geoLocationIsBusy == true),
                'find-a-job__geolocation--is-active': searchTerm.isGeolocationSearch
            }">
        <a class="find-a-job__geolocation-button"
           v-if="geoLocationIsAvailable"
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
      <div class="find-a-job__jobs-available-container">
        <div class="find-a-job__jobs-available">{{ jobsAvailable }}</div>
      </div>

      <div class="find-a-job__view-list-container">

        <ul class="find-a-job__view-list">

          <li class="find-a-job__view-list-element"
              :data-group-id="job.jobLocationGroupId"
              v-for="(job, index) in visibleSearchResults"
              :key="index"
              v-on:click="handleVacancyClick(job.jobLocationGroupId)">
            <job-summary :distance="job.distance"
                         :distance-time="job.distanceTime"
                         :position="job.role"
                         :prison-city="job.prison_location.town"
                         :prison-name="job.prison_name"
                         :prison-page-link="job.url"
                         :salary="job.salary"
                         :selected="job.jobLocationGroupId == searchResults.selectedJobLocationGroupId"
                         :title="job.title"
                         :url="job.url"
            >
            </job-summary>
          </li>
          <li v-if="jobListMessage">
            <div class="find-a-job__job-list-message">
              <a :href="jobListMessageUrl">{{ jobListMessage }}</a>
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
              {{searchResults.listView.activePage + 1}}
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
      'jobFeedUrl': {
        default: '',
        type: String
      },
      'jobListMessage': {
        default: '',
        type: String
      },
      'jobListMessageUrl': {
        default: '',
        type: String
      },
      'jobTitle': {
        default: '',
        type: String
      }
    },

    data() {
      return {
        deviceIsMobile: false,

        geoLocationIsAvailable: false,
        geoLocationIsBusy: false,
        geoLocationIsActive: false,

        vacanciesDataURL: this.jobFeedUrl,

        searchResults: {
          urlEncodedPostCode: '',
          googleMapAPIKey: 'AIzaSyDDplfBkLzNA3voskfGyExYnQ46MJ0VtpA',
          listView: {
            activePage: 0,
            resultsPerPage: 5,
            forwardEnabled: true,
            backwardEnabled: false
          },
          jobs: [],
          jobLocationGroups: {},
          orderedJobLocationGroups: [],
          selectedJobLocationGroupId: '',
          visibleJobLocationGroup: null,
        },

        searchTerm: {
          input: this.storeGet('searchTerm.query') || '',
          query: null,
          latlng: null,
          marker: null,
          isGeolocation: false
        },

        mapSrc: '',

        maxZoom: 18,
        minZoom: 4,

        mapOptions: {
          disableDefaultUI: false,
          streetViewControl: false,
          fullscreenControl: false,
          mapTypeControl: false,
          gestureHandling: 'greedy',
          zoomControl: false
        },

        titleText: this.title,

        placeHolderText: 'e.g. SW1A 2LW, Birmingham or Essex',

        mounted: false,
      };
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

      updateSelectedJobLocationGroupId(groupId) {
        this.searchResults.selectedJobLocationGroupId = groupId;
      },

      calculateActivePageFromGroupId(groupId) {
        const groups = this.searchResults.orderedJobLocationGroups;
        for (let i in groups) {
          i = parseInt(i);
          if (groups[i][0].jobLocationGroupId == groupId) {
            this.searchResults.listView.activePage = Math.floor(i / this.searchResults.listView.resultsPerPage);
            return;
          }
        }
      },

      convertGroupIdToCoords(groupId) {
        const array = groupId.split(',');
        return {lat: array[0], lng: array[1]};
      },

      zoomTo(level) {
        if (level < this.minZoom) level = this.minZoom;
        if (level > this.maxZoom) level = this.maxZoom;
        this.map.setZoom(level);
      },

      zoomBy(amount) {
        this.zoomTo(this.map.getZoom() + amount);
      },

      focusOnJobLocationGroup(groupId) {
        this.updateSelectedJobLocationGroupId(groupId);
        CustomMarker.changeSelectedMarkerByGroupId(groupId);

        const coords = this.convertGroupIdToCoords(groupId);
        this.recenterMap(coords.lat, coords.lng);
      },

      recenterMap(lat, lng) {
        this.map.panTo(new google.maps.LatLng(lat, lng));
      },

      handleMapMarkerClick(self, groupId, event) {
        if (self.deviceIsMobile) {
          self.calculateActivePageFromGroupId(groupId);
        } else {
          document.querySelector(`.find-a-job__view-list-element[data-group-id='${groupId}']`)
            .scrollIntoView({ behavior: 'smooth' });
        }
        self.focusOnJobLocationGroup(groupId);
        event.preventDefault();
        event.stopPropagation();
        return false;
      },

      handleVacancyClick(groupId) {
        this.focusOnJobLocationGroup(groupId);
      },

      updateMapWithJobLocationGroupMarkers(jobLocationGroups) {
        const markerArgs = [];
        for (let group in jobLocationGroups) {
          markerArgs.push({
            class: 'find-a-job__map-marker--job-location-group',
            solid: true,
            amount: jobLocationGroups[group].jobs.length,
            groupId: group,
            clickCallback: this.handleMapMarkerClick.bind(null, this, group),
            prisonName: jobLocationGroups[group].prisonName
          });
        }
        for (let i in markerArgs) {
          if (markerArgs[i].groupId == this.searchResults.selectedJobLocationGroupId) {
            markerArgs[i].selected = true;
          }
          const latLngArr = markerArgs[i].groupId.split(',');
          const latLng = new google.maps.LatLng(latLngArr[0], latLngArr[1]);
          const marker = new CustomMarker(
            latLng,
            this.map,
            markerArgs[i]
          );
        }
      },

      updateJobsDistance(lat, lng) {

        // calculate distance
        for (let i = 0; i < this.searchResults.jobs.length; i++) {

          const newDistance = this.calculateDistanceBetweenTwoLatLngPoints(
            lat,
            lng,
            this.searchResults.jobs[i].prison_location.lat,
            this.searchResults.jobs[i].prison_location.lng
          );

          this.searchResults.jobs[i].distance = newDistance;
        }

        this.searchResults.jobs.sort(function (a, b) {
          return a.distance - b.distance;
        });

        this.searchResults.orderedJobLocationGroups = [];
        for (const group in this.searchResults.jobLocationGroups) {
          const newDistance = this.calculateDistanceBetweenTwoLatLngPoints(
            lat,
            lng,
            this.searchResults.jobLocationGroups[group].jobs[0].prison_location.lat,
            this.searchResults.jobLocationGroups[group].jobs[0].prison_location.lng
          );
          this.searchResults.jobLocationGroups[group].distance = newDistance;
          this.searchResults.orderedJobLocationGroups.push(this.searchResults.jobLocationGroups[group].jobs);
        }

        this.searchResults.orderedJobLocationGroups.sort(function (a, b) {
          return a[0].distance - b[0].distance;
        });
      },

      createJobLocationGroups() {

        // iterate over the jobs and put them in the correct jobLocationGroup
        const jobs = this.searchResults.jobs;
        const jobLocationGroups = {};
        let closestJobLocationGroupDistance = Number.MAX_SAFE_INTEGER;
        let closestJobLocationGroupId = null;

        for (let i = 0; i < jobs.length; i++) {
          const latLngStr = jobs[i].prison_location.lat + ',' + jobs[i].prison_location.lng;

          if (typeof jobLocationGroups[latLngStr] == 'undefined') {
            jobLocationGroups[latLngStr] = {
              prisonName: jobs[i].prison_name,
              jobs: [jobs[i]]
            };
          } else {
            jobLocationGroups[latLngStr].jobs.push(jobs[i]);
          }

          jobs[i].jobLocationGroupId = latLngStr;

          if (jobs[i].distance < closestJobLocationGroupDistance) {
            closestJobLocationGroupDistance = jobs[i].distance;
            closestJobLocationGroupId = latLngStr;
          }
        }
        this.searchResults.jobLocationGroups = jobLocationGroups;
        this.searchResults.selectedJobLocationGroupId = closestJobLocationGroupId;

        // initialize orderedJobLocationGroups array
        this.searchResults.orderedJobLocationGroups = [];
        for (const id in this.searchResults.jobLocationGroups) {
          this.searchResults.orderedJobLocationGroups.push(this.searchResults.jobLocationGroups[id].jobs);
        }

        // initially sort the job location groups by town name
        this.searchResults.orderedJobLocationGroups.sort(function (a, b) {
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

        this.updateMapWithJobLocationGroupMarkers(this.searchResults.jobLocationGroups);
      },

      createMap() {
        this.map = new google.maps.Map(
          document.getElementsByClassName('find-a-job__map')[0],
          this.mapOptions
        );
        this.zoomToEngland();
      },

      zoomToEngland() {
        const england = new google.maps.LatLngBounds(
          new google.maps.LatLng(49.8647411, -6.418545799999947),
          new google.maps.LatLng(55.81165979999999, 1.7629159000000527)
        );
        this.map.fitBounds(england, 0);
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
          this.map,
          {class: 'find-a-job__map-marker--search-term'}
        );
      },

      zoomToNearbyResults(lat, lng) {
        var bounds = new google.maps.LatLngBounds();

        // Add user's search location to the bounds
        bounds.extend({lat: lat, lng: lng});

        // Add closest tenth of search results to the bounds
        const locations = this.searchResults.orderedJobLocationGroups;
        const minVisible = Math.ceil(locations.length / 10);
        const boundLocations = locations.slice(0, minVisible);
        boundLocations.forEach((location) => {
          let ll = {
            lat: location[0].prison_location.lat,
            lng: location[0].prison_location.lng
          };
          bounds.extend(ll);
        });

        // Fit the map to the bounds
        this.map.fitBounds(bounds);
      },

      handleNewSearchLocation(latlng) {
        this.searchResults.listView.activePage = 0;

        if (latlng == null) {
          this.removeSearchTermMarker();
          this.zoomToEngland();
        }
        else {
          this.updateSearchTermMarker(latlng.lat, latlng.lng);
          this.updateJobsDistance(latlng.lat, latlng.lng);
          this.zoomToNearbyResults(latlng.lat, latlng.lng);
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

      resetSearch() {
        this.searchTerm.input = '';
        this.searchTerm.query = '';
        this.searchTerm.latlng = null;
        this.searchTerm.isGeolocationSearch = false;
        this.$refs.searchInput.focus();

        this.removeSearchTermMarker();
      },

      search() {
        this.searchTerm.query = this.searchTerm.input;
        this.searchTerm.isGeolocationSearch = false;

        if (this.searchTerm.query) {
          new google.maps.Geocoder().geocode(
            {'address': this.searchTerm.query + ', UK'},
            this.processGeocoderResults
          );
          this.$refs.searchInput.blur();
        }
      },

      useGeoLocation() {
        this.geoLocationIsBusy = true;
        navigator.geolocation.getCurrentPosition(
          position => {
            this.searchTerm.latlng = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            this.searchTerm.query = '';
            this.searchTerm.input = '';
            this.searchTerm.isGeolocationSearch = true;
            this.geoLocationIsBusy = false;
            this.geoLocationIsActive = true;
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
        this.searchResults.listView.activePage = i
      },

      showNextPage() {
        if (this.searchResults.listView.activePage < (this.numberOfResultPages - 1)) {
          this.searchResults.listView.activePage++
        }
      },

      showPreviousPage() {
        if (this.searchResults.listView.activePage > 0) {
          this.searchResults.listView.activePage--
        }
      },

      showFirstPage() {
        this.showPage(0);
      },

      showLastPage() {
        this.showPage(this.numberOfResultPages - 1);
      },

      handleGotVacanciesData(response) {
        this.searchResults.jobs = response.data;
      },

      updateIsDeviceMobile() {
        this.deviceIsMobile = this.isDeviceMobile();
      },

      handleScreenResize() {
        this.updateIsDeviceMobile();
      },

      storeSave(key, value) {
        localStorage.setItem('ppj.find-a-job:' + key, value);
      },

      storeGet(key) {
        return localStorage.getItem('ppj.find-a-job:' + key);
      },

      restorePageData() {
        if (this.searchTerm.input) {
          this.search();
        }
      }

    },

    watch: {
      'searchTerm.query': function(val) {
        this.storeSave('searchTerm.query', val);
      },
      'searchTerm.latlng': function(val) {
        this.handleNewSearchLocation(val);
      }
    },

    computed: {
      numberOfResultPages: function () {
        const num = Math.ceil(this.searchResults.orderedJobLocationGroups.length / this.searchResults.listView.resultsPerPage);
        return num;
      },

      defaultSearchResults: function () {
        return this.searchResults.jobs;
      },

      backwardEnabled: function () {
        return (this.searchResults.listView.activePage > 0);
      },

      forwardEnabled: function () {
        return (this.searchResults.listView.activePage < (this.numberOfResultPages - 1));
      },

      visibleSearchResults: function() {
        if (this.mounted) {

          if (Object.keys(this.searchResults.jobLocationGroups).length === 0) {
            this.createJobLocationGroups();
          }

          let selectedJobLocationGroups = null;
          if (this.deviceIsMobile) {
            selectedJobLocationGroups = this.searchResults.orderedJobLocationGroups.slice().splice(
              this.searchResults.listView.resultsPerPage * this.searchResults.listView.activePage,
              this.searchResults.listView.resultsPerPage
            );
          } else {
            selectedJobLocationGroups = this.searchResults.orderedJobLocationGroups;
          }

          const results = [];
          for (const i in selectedJobLocationGroups) {
            for (const j in selectedJobLocationGroups[i]) {
              results.push(selectedJobLocationGroups[i][j]);
            }
          }

          return results;
        }

      },

      jobsAvailable: function() {
        return this.searchResults.jobs.length + ' ' + this.jobTitle + ' jobs available: ';
      }
    },

    created() {
      this.updateIsDeviceMobile();

      if ("geolocation" in navigator) {
        this.geoLocationIsAvailable = true;
      }
    },

    mounted() {

      const self = this;
      this.createMap();
      this.mounted = true;

      window.addEventListener('resize', this.handleScreenResize);

      console.log('job list message: ' , this.jobListMessage);
      console.log('job list message URL: ' , this.jobListMessageURL);

      axios.get(this.vacanciesDataURL,  { responseType: 'json' })
        .then(self.handleGotVacanciesData)
        .catch(function (error) {
          console.log(error);
        });

      this.restorePageData();
    }
  }
</script>
