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
                      v-for="(job, index) in searchResults.visibleSearchResults"
                      :key="job.jobLocationGroupId"
                      @click="focusOnJobLocationGroup(job.jobLocationGroupId)"
                  >
                    <job-summary :distance="job.distance"
                                 :distance-time="job.distanceTime"
                                 :position="job.title"
                                 :salary="job.salary"
                                 :prison-name="job.prison_name"
                                 :prison-city="job.organizationCity"
                                 :prison-page-link="job.url"
                                 :url="job.url"
                                 :selected="job.jobLocationGroupId == searchResults.selectedJobLocationGroupId"
                    >
                    </job-summary>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="search__list-view" v-show="searchResults.activeView == 1">
            <ul class="search__list-view-list">
              <li v-for="(job, index) in searchResults.visibleSearchResults"
                  :key="index">
                <job-summary :distance="job.distance"
                             :distance-time="job.distanceTime"
                             :position="job.title"
                             :salary="job.salary"
                             :prison-name="job.prison_name"
                             :prison-city="job.organizationCity"
                             :prison-page-link="job.url"
                             :url="job.url">
                </job-summary>
              </li>
            </ul>
            <div class="search__pagination">
              <button class="search__pagination-direction"
                      :class="{'search__pagination-direction--active': (backwardEnabled == true)}"
                      @click.stop.prevent="showPreviousPage"
              > <
              </button>

              <button class="search__pagination-direction"
                      :class="{'search__pagination-direction--active': (forwardEnabled == true)}"
                      @click.stop.prevent="showNextPage"
              > >
              </button>

              <button v-for="n in numberOfResultPages"
                      class="search__pagination-link"
                      :class="{'search__pagination-link--active': (searchResults.listView.activePage == (n - 1))}"
                      @click.stop.prevent="showPage(n-1)"
              >{{ n }}
              </button>
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
          searchTermMarker: {},
          selectedJobLocationGroupId: '',
          visibleJobLocationGroup: null,
          visibleSearchResults: dummyJobs
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

    computed: {
      numberOfResultPages: function () {
        const num = Math.ceil(this.searchResults.jobs.length / this.searchResults.listView.resultsPerPage);
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
      }
    },

    watch: {
      selectedJobLocationGroupId: function (selectedJobLocationGroupId) {
        if (selectedJobLocationGroupId) {
          this.searchResults.visibleJobLocationGroup = searchResults.jobLocationGroups[selectedJobLocationGroupId];
        }
      },

      'searchResults.activeView': function () {
        console.log('watch - searchResults.activeView');
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

      setVisibleSearchResults() {

        if (window.innerWidth < 768) {
          const
            listView = this.searchResults.listView,
            startIndex =
              listView.activePage
              * listView.resultsPerPage,
            endIndex = startIndex + listView.resultsPerPage
          ;
          this.searchResults.visibleSearchResults = this.searchResults.jobs.slice(startIndex, endIndex);
        } else {
          this.searchResults.visibleSearchResults = this.searchResults.jobs;
        }
      },

      focusOnJobLocationGroup(groupId) {
        this.updateSelectedJobLocationGroupId(groupId);
        CustomMarker.changeSelectedMarkerByGroupId(groupId);
        this.setVisibleSearchResults();
      },

      recenterMap(latLngStr) {

      },

      updateMapWithJobLocationGroupMarkers(jobLocationGroups) {
        const markerArgs = [];
        for (let group in jobLocationGroups) {
          markerArgs.push({
            class: 'search__map-marker--job-location-group',
            solid: true,
            amount: jobLocationGroups[group].length,
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
        console.log('updateJobsDistance');
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
      },

      createJobLocationGroups() {
        console.log('createJobLocationGroups');

        const jobs = this.searchResults.jobs;
        const jobLocationGroups = {};
        let closestJobLocationGroupDistance = Number.MAX_SAFE_INTEGER;
        let closestJobLocationGroupId = null;

        for (let i = 0; i < jobs.length; i++) {
          const latLngStr = jobs[i].prison_location.lat + ',' + jobs[i].prison_location.lng;

          if (typeof jobLocationGroups[latLngStr] == 'undefined') {
            jobLocationGroups[latLngStr] = [jobs[i]];
          } else {
            jobLocationGroups[latLngStr].push(jobs[i]);
          }

          jobs[i].jobLocationGroupId = latLngStr;

          if (jobs[i].distance < closestJobLocationGroupDistance) {
            closestJobLocationGroupDistance = jobs[i].distance;
            closestJobLocationGroupId = latLngStr;
          }
        }
        this.searchResults.jobLocationGroups = jobLocationGroups;
        this.searchResults.selectedJobLocationGroupId = closestJobLocationGroupId;
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
        console.log('processGeocoderResults');
        if (status == google.maps.GeocoderStatus.OK) {
          console.log('processGeocoderResults status is ok');
          const
            location = results[0].geometry.location,
            lat = location.lat(),
            lng = location.lng()
          ;

          this.updateJobsDistance(lat, lng);
          this.setVisibleSearchResults();
          this.updateSearchTermMarker(lat, lng);
        } else {
          // TODO handle no connection to google geocoder api
          alert('Geocode was not successful for the following reason: ' + status);
        }
      },

      search() {
        console.log('search', this.searchResults.searchTerm);
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

    mounted() {

      if (this.isDeviceMobile()) {
        this.mapOptions.zoom = 6;
      }

      this.createMap();

      if (this.searchResults.searchTerm) {
        this.search();
      } else {
        this.setVisibleSearchResults();
      }
    }
  }
</script>
