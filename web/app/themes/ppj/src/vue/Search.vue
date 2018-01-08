<template>
  <div class="search" v-cloak>
    <h2 class="search__title">{{ titleText }}</h2>
    <p>Location (postcode, town, region)</p>
    <form class="search__form">
      <input type="text"
             class="search__input"
             :placeholder="placeHolderText"
             editable="editable"
             v-model="searchResults.searchTerm"/>

      <button class="search__button-search"
              @click.stop.prevent="search"
              :disabled="searchResults.searchTerm == ''">
        <div class="search__button-search-circle"></div>
        <div class="search__button-search-rectangle"></div>
      </button>

      <div class="search__results" v-show="searchResults.display">

        <div class="search__view-container">
          <div class="search__map-view" v-show="searchResults.activeView == 0">
            <div class="search__map-container">
              <div class="search__square-box">
                <div class="search__map"></div>
              </div>
            </div>
            <div class="search__view-list-container">
              <div class="search__rectangle">

                <ul class="search__view-list">
                  <li class="search__view-list-element"
                      v-for="(job, index) in visibleSearchResults"
                      :key="job.jobLocationGroupId"
                      @click="focusOnJobLocationGroup(job.jobLocationGroupId)">
                    <job-summary :distance="job.distance"
                                 :distance-time="job.distanceTime"
                                 :position="job.title"
                                 :salary="job.salary"
                                 :prison-name="job.prison_name"
                                 :prison-city="job.organizationCity"
                                 :prison-page-link="job.url"
                                 :url="job.url"
                                 :selected="job.jobLocationGroupId == searchResults.selectedJobLocationGroupId">
                    </job-summary>
                  </li>
                </ul>

                <div class="search__pagination"
                     v-if="deviceIsMobile">
                  <button class="search__pagination-direction"
                          :class="{'search__pagination-direction--active': (backwardEnabled == true)}"
                          @click.stop.prevent="showPreviousPage"> <
                  </button>
                  {{searchResults.listView.activePage + 1}} of {{numberOfResultPages}}
                  <button class="search__pagination-direction"
                          :class="{'search__pagination-direction--active': (forwardEnabled == true)}"
                          @click.stop.prevent="showNextPage"> >
                  </button>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
  import dummyJobs from '../js/dummyJobs';
  import CustomMarker from '../js/CustomMarker';

  export default {
    props: {
      'default-search-term': {
        default: '',
        type: String
      }
    },

    data() {
      return {
        deviceIsMobile: false,

        searchResults: {
          activeView: 0,
          display: true,//false,
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

        mapOptions: {
          zoom: 7,
          center: new google.maps.LatLng(52.4832138, -1.5947146),
          disableDefaultUI: false,
          streetViewControl: false,
          mapTypeControl: false,
        },

        titleText: 'Search for jobs',

        placeHolderText: 'Enter location'
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

      computeVisibleSearchResults(orderedJobLocationGroups, jobLocationGroupsPerPage, activePageNumber) {
        const selectedJobLocationGroups = orderedJobLocationGroups.slice().splice(
          jobLocationGroupsPerPage * activePageNumber,
          jobLocationGroupsPerPage
        );
        const visibleSearchResults = [];
        for (const i in selectedJobLocationGroups) {
          for (const j in selectedJobLocationGroups[i]) {
            visibleSearchResults.push(selectedJobLocationGroups[i][j]);
          }
        }
        return visibleSearchResults;
      },

      focusOnJobLocationGroup(groupId) {
        this.updateSelectedJobLocationGroupId(groupId);
        CustomMarker.changeSelectedMarkerByGroupId(groupId);
      },

      recenterMap(latLngStr) {

      },

      updateMapWithJobLocationGroupMarkers(jobLocationGroups) {
        const markerArgs = [];
        for (let group in jobLocationGroups) {
          markerArgs.push({
            class: 'search__map-marker--job-location-group',
            solid: true,
            amount: jobLocationGroups[group].jobs.length,
            groupId: group,
            clickCallback: this.focusOnJobLocationGroup.bind(this, group)
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

      },

      createMap() {
        this.map = new google.maps.Map(
          document.getElementsByClassName('search__map')[0]
          , this.mapOptions
        );
        this.createJobLocationGroups();
        this.updateMapWithJobLocationGroupMarkers(this.searchResults.jobLocationGroups);
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

      processGeocoderResults(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          console.log('processGeocoderResults status is ok');
          const
            location = results[0].geometry.location,
            lat = location.lat(),
            lng = location.lng()
          ;

          this.updateJobsDistance(lat, lng);
          this.updateSearchTermMarker(lat, lng);
        } else {
          // TODO handle no connection to google geocoder api
          alert('Geocode was not successful for the following reason: ' + status);
        }
      },

      search() {
        new google.maps.Geocoder().geocode(
          {'address': this.searchResults.searchTerm},
          this.processGeocoderResults
        );
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
        let visibleSearchResults = [];
        if (this.deviceIsMobile) {
          visibleSearchResults = this.computeVisibleSearchResults(
            this.searchResults.orderedJobLocationGroups,
            this.searchResults.listView.resultsPerPage,
            this.searchResults.listView.activePage
          )
        } else {
          visibleSearchResults = this.searchResults.jobs;
        }
        return visibleSearchResults;
      }
    },

    watch: {

    },

    mounted() {

      if (this.isDeviceMobile()) {
        this.deviceIsMobile = true;
        this.mapOptions.zoom = 6;
      }

      this.createMap();

      if (this.searchResults.searchTerm) {
        this.search();
      } else {

      }
    }
  }
</script>
