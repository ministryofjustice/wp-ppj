<template>
  <div class="search" v-cloak>
    <h2 class="search__title">{{ titleText }}</h2>
    <p class="search_prompt">Enter location (postcode, town or region)</p>
    <div class="search__form">
      <input type="text"
             class="search__input"
             :placeholder="placeHolderText"
             editable="editable"
             v-model="searchResults.searchTerm"
             @blur.stop.prevent="handleSearchInputBlur"
             @focus.stop.prevent="handleSearchInputFocus"
             @keypress="handleSearchInputKeyPress"
      />
      <div class="search__button-clear-search-container">
        <button class="search__button-clear-search"
                :class="{'search__button-clear-search--enabled': searchResults.clearSearchAvailable}"
                :disable="!searchResults.clearSearchAvailable"
                @click.stop.prevent="handleClearSearchClick"
        >&#10005;</button>
      </div>

      <button class="search__button-search"
              @click.stop.prevent="search"
              :disabled="searchResults.searchTerm == ''">
        <div class="search__button-search-circle"></div>
        <div class="search__button-search-rectangle"></div>
      </button>
    </div>

      <div class="search__geolocation"
           :class="{'search__geolocation--is-busy': (geoLocationIsBusy == true)}"
      >
        <a class="search__geolocation-button"
           v-if="geoLocationIsAvailable"
           @click.stop.prevent="useGeoLocation"
        ><img class="search__geolocation-icon"
            src="/app/themes/ppj/dest/img/svg/geolocation.svg"
            alt="Icon for geolocation button"><span>Use my current location</span></a>
      </div>

    <div class="search__results" v-show="searchResults.display">

      <div class="search__view-container">
        <div class="search__map-view" v-show="searchResults.activeView == 0">
          <div class="search__map-container">
            <div class="search__square-box">
              <div class="search__map"></div>
            </div>
          </div>
          <div class="search__view-list-container">
            <div class="search__jobs-available"><span>{{searchResults.jobs.length }}</span> jobs available:</div>
            <div class="search__rectangle">

              <ul class="search__view-list">
                <li class="search__view-list-element"
                    :data-group-id="job.jobLocationGroupId"
                    v-for="(job, index) in visibleSearchResults"
                    :key="index"
                    @click="handleVacancyClick(job.jobLocationGroupId)">
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
              </ul>
            </div>
            <div class="search__pagination"
                 v-if="deviceIsMobile">
              <a class="search__pagination-skip-link"
                 :class="{'search__pagination-skip-link--enabled': (backwardEnabled == true)}"
                 @click.stop.prevent="showFirstPage">
                first</a>
              <button class="search__pagination-direction"
                      :class="{'search__pagination-direction--enabled': (backwardEnabled == true)}"
                      @click.stop.prevent="showPreviousPage"> <
              </button>
              <div class="search__pagination-page-numbers-container">
                <div class="search__pagination-current-page-number">
                  {{searchResults.listView.activePage + 1}}
                </div>
                <div class="search__pagination-of">of</div>
                <div class="search__pagination-total-pages">
                  {{numberOfResultPages}}
                </div>
              </div>
              <button class="search__pagination-direction"
                      :class="{'search__pagination-direction--enabled': (forwardEnabled == true)}"
                      @click.stop.prevent="showNextPage"> >
              </button>
              <a class="search__pagination-skip-link"
                 :class="{'search__pagination-skip-link--enabled': (forwardEnabled == true)}"
                 @click.stop.prevent="showLastPage"
              >last</a>
            </div>
          </div>
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
      'default-search-term': {
        default: '',
        type: String
      },
      'title': {
        default: '',
        type: String
      }
    },

    data() {
      return {
        deviceIsMobile: false,

        geoLocationIsAvailable: false,

        geoLocationIsBusy: false,

        vacanciesDataURL: 'https://s3.eu-west-2.amazonaws.com/hmpps-feed-parser/vacancies.json',

        searchResults: {
          activeView: 0,
          display: true,//false,
          clearSearchAvailable: false,
          postCode: this.defaultSearchTerm,//'',//'SW1H 1AJ',
          urlEncodedPostCode: '',
          googleMapAPIKey: 'AIzaSyDDplfBkLzNA3voskfGyExYnQ46MJ0VtpA',
          listView: {
            activePage: 0,
            resultsPerPage: 5,
            forwardEnabled: true,
            backwardEnabled: false
          },
          jobs: dummyJobs,
          orderBy: 'distance',
          jobLocationGroups: {},
          orderedJobLocationGroups: [],
          searchTerm: '',
          searchTermMarker: {},
          selectedJobLocationGroupId: '',
          visibleJobLocationGroup: null,
        },

        mapSrc: '',

        defaultZoomLevel: 7,

        mapOptions: {
          zoom: 7,
          center: new google.maps.LatLng(52.4832138, -1.5947146),
          disableDefaultUI: false,
          streetViewControl: false,
          fullscreenControl: false,
          mapTypeControl: false,
          gestureHandling: 'greedy',
        },

        titleText: this.title,

        placeHolderText: 'e.g. SW1A 2LW, Birmingham or Essex',

        mounted: false,
      }
    },

    methods: {

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

      zoom() {
        if (this.map.getZoom() == this.defaultZoomLevel) {
          this.map.setZoom(this.defaultZoomLevel + 2);
        } else {

        }
      },

      focusOnJobLocationGroup(groupId) {
        this.updateSelectedJobLocationGroupId(groupId);
        CustomMarker.changeSelectedMarkerByGroupId(groupId);

        const coords = this.convertGroupIdToCoords(groupId);
        this.recenterMap(coords.lat, coords.lng);
        this.zoom();
      },

      recenterMap(lat, lng) {
        this.map.panTo(new google.maps.LatLng(lat, lng));
      },

      handleMapMarkerClick(groupId) {
        if (this.deviceIsMobile) {
          this.calculateActivePageFromGroupId(groupId);
        } else {
          document.querySelector(`.search__view-list-element[data-group-id='${groupId}']`)
            .scrollIntoView({ behavior: 'smooth' });
        }
        this.focusOnJobLocationGroup(groupId);
      },

      handleVacancyClick(groupId) {
        this.focusOnJobLocationGroup(groupId);
      },

      updateMapWithJobLocationGroupMarkers(jobLocationGroups) {
        const markerArgs = [];
        for (let group in jobLocationGroups) {
          markerArgs.push({
            class: 'search__map-marker--job-location-group',
            solid: true,
            amount: jobLocationGroups[group].jobs.length,
            groupId: group,
            clickCallback: this.handleMapMarkerClick.bind(this, group)
          });
        }
        for (let i in markerArgs) {
          if (markerArgs[i].groupId == this.searchResults.selectedJobLocationGroupId) {
            markerArgs[i].selected = true;
          }
          const latLngArr = markerArgs[i].groupId.split(',');
          const latLng = new google.maps.LatLng(latLngArr[0], latLngArr[1]);
          new CustomMarker(
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
            jobLocationGroups[latLngStr] = {jobs: [jobs[i]]};
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
        this.mapOptions.zoom = this.defaultZoomLevel;
        this.map = new google.maps.Map(
          document.getElementsByClassName('search__map')[0]
          , this.mapOptions
        );
      },

      updateSearchTermMarker(lat, lng) {
        if (typeof this.searchResults.searchTermMarker.markerDiv == 'undefined') {
          this.searchResults.searchTermMarker.markerDiv = new google.maps.Marker({
            position: {lat, lng},
            map: this.map,
            label: this.searchTerm
          });
        } else {
          this.searchResults.searchTermMarker.markerDiv.setPosition(new google.maps.LatLng(lat, lng));
        }
      },

      handleNewSearchLocation(lat, lng) {
        this.searchResults.listView.activePage = 0;
        this.updateJobsDistance(lat, lng);
        this.updateSearchTermMarker(lat, lng);
        this.recenterMap(lat, lng);
      },

      processGeocoderResults(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          console.log('processGeocoderResults status is ok');

          this.handleNewSearchLocation(
            results[0].geometry.location.lat(),
            results[0].geometry.location.lng()
          );

        } else {
          // TODO handle no connection to google geocoder api
          alert('Geocode was not successful for the following reason: ' + status);
        }
      },

      handleSearchInputBlur() {
        if (this.searchResults.searchTerm) {
          this.searchResults.clearSearchAvailable = true;
        }
      },

      handleSearchInputFocus() {
        this.searchResults.clearSearchAvailable = false;
      },

      handleSearchInputKeyPress(e) {
        if(e.keyCode === 13){
          e.preventDefault();
          this.search();
        }
      },

      handleClearSearchClick() {
        this.searchResults.searchTerm = '';
        this.searchResults.clearSearchAvailable = false;
        document.getElementsByClassName('search__input')[0].focus();
      },

      search() {
        new google.maps.Geocoder().geocode(
          {'address': 'UK ' + this.searchResults.searchTerm},
          this.processGeocoderResults
        );
        document.getElementsByClassName('search__input')[0].blur();
      },

      useGeoLocation() {
        this.searchResults.searchTerm = '';
        this.geoLocationIsBusy = true;
        navigator.geolocation.getCurrentPosition(position => {
          console.log(position.coords.latitude, position.coords.longitude);
          this.searchResults.searchTerm = position.coords.latitude + ',' + position.coords.longitude;
          this.handleNewSearchLocation(position.coords.latitude, position.coords.longitude);
          this.geoLocationIsBusy = false;
        });
      },

      showMapView() {
        this.searchResults.activeView = 0;
      },

      showListView() {
        this.searchResults.activeView = 1;
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

      }
    },

    created() {
      if (this.isDeviceMobile()) {
        this.deviceIsMobile = true;
        this.mapOptions.zoom = 6;
      }

      if ("geolocation" in navigator) {
        this.geoLocationIsAvailable = true;
      }
    },

    mounted() {
      const self = this;
      this.createMap();
      this.mounted = true;

      axios.get(this.vacanciesDataURL)
        .then( response => {
          // TODO revert
          //self.searchResults.jobs = response.data;
          //self.createJobLocationGroups();
          //self.updateMapWithJobLocationGroupMarkers(self.searchResults.jobLocationGroups);

          if (self.searchResults.searchTerm) {
            self.search();
          } else {

          }
        })
        .catch(function (error) {
          console.log(error);
        });
    }
  }
</script>
