<template>
        <div class="search">
            <h2 class="search__title">{{ titleText }}</h2>
            <form class="search__form">
                <input
                    type="text"
                    class="search__input"
                    :placeholder="placeHolderText"
                    editable="editable"
                    v-model="searchResults.postCode"
                />
                <button class="search__button-search"
                        @click.stop.prevent="search"
                        :disabled="searchResults.postCode == ''"
                >
                    <div class="search__button-search-circle"></div>
                    <div class="search__button-search-rectangle"></div>
                </button>
                <div class="search__results" v-show="searchResults.display">
                    <div class="search__view-controls">
                        <button
                                class="search__button-view"
                                :class="{'search__button-view--active': (searchResults.activeView == 0)}"
                                @click.stop.prevent="showMapView"
                        >Map view</button>
                        <button
                                class="search__button-view"
                                :class="{'search__button-view--active': (searchResults.activeView == 1)}"
                                @click.stop.prevent="showListView"
                        >List view</button>
                    </div>
                    <div class="search__view-container">
                        <div class="search__view-map" v-show="searchResults.activeView == 0">
                            <div class="search__map"></div>
                            <ul class="search__view-list-list">
                                <li
                                        v-for="(job, index) in searchResults.jobLocationGroups[searchResults.selectedJobLocationGroup]"
                                        :key="index"
                                >
                                    <job-summary
                                            :distance="job.distance"
                                            :distance-time="job.distanceTime"
                                            :position="job.position"
                                            :salary="job.salary"
                                            :prison-name="job.organizationName"
                                            :prison-city="job.organizationCity"
                                            :prison-page-link="job.organizationPageLink"
                                            :url="job.url"
                                    >
                                    </job-summary>
                                </li>
                            </ul>
                        </div>
                        <div class="search__view-list" v-show="searchResults.activeView == 1">
                            <ul class="search__view-list-list">
                                <li
                                        v-for="(job, index) in visibleSearchResults"
                                        :key="index"
                                >
                                    <job-summary
                                            :distance="job.distance"
                                            :distance-time="job.distanceTime"
                                            :position="job.position"
                                            :salary="job.salary"
                                            :prison-name="job.organizationName"
                                            :prison-city="job.organizationCity"
                                            :url="job.url"
                                    >
                                    </job-summary>
                                </li>
                            </ul>
                            <div class="search__pagination">
                                <button class="search__pagination-direction"
                                        :class="{'search__pagination-direction--active': (backwardEnabled == true)}"
                                        @click.stop.prevent="showPreviousPage"
                                > < </button>

                                <button class="search__pagination-direction"
                                        :class="{'search__pagination-direction--active': (forwardEnabled == true)}"
                                        @click.stop.prevent="showNextPage"
                                > > </button>

                                <button v-for="n in numberOfResultPages"
                                        class="search__pagination-link"
                                        :class="{'search__pagination-link--active': (searchResults.listView.activePage == (n - 1))}"
                                        @click.stop.prevent="showPage(n-1)"
                                >{{ n }}</button>
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

    function getFirstElementInObject(obj) {
        return obj[Object.keys(obj)[0]];
    }

    export default {
        data() {
            return {
                searchResults: {
                    activeView: 0,
                    display: false,
                    postCode: 'SW1H 1AJ',
                    urlEncodedPostCode: '',
                    googleMapAPIKey: 'AIzaSyDDplfBkLzNA3voskfGyExYnQ46MJ0VtpA',
                    listView: {
                        activePage: 0,
                        resultsPerPage: 5,
                        forwardEnabled: true,
                        backwardEnabled: false
                    },
                    jobs: dummyJobs,
                    jobLocationGroups: {},
                    selectedJobLocationGroup: '',
                    visibleJobLocationGroup: null
                },
                mapSrc: '',
                mapOptions: {
                    zoom: 9,
                    center: new google.maps.LatLng(0.0,0.0),
                    disableDefaultUI: false
                },
                titleText: 'Search for vacancies near you',
                placeHolderText: 'Enter your post code'
            }
        },
        computed: {
            numberOfResultPages: function() {
                const num = Math.ceil(this.searchResults.jobs.length / this.searchResults.listView.resultsPerPage);
                return num;
            },
            visibleSearchResults: function() {
                const
                    listView = this.searchResults.listView,
                    startIndex =
                          listView.activePage
                        * listView.resultsPerPage,
                    endIndex = startIndex + listView.resultsPerPage
                ;
                return this.searchResults.jobs.slice(startIndex, endIndex);
            },
            backwardEnabled: function() {
                return (this.searchResults.listView.activePage > 0);
            },
            forwardEnabled: function() {
                return (this.searchResults.listView.activePage < (this.numberOfResultPages - 1));
            }
        },
        watch: {
            selectedJobLocationGroup: function(selectedJobLocationGroup) {
                if (selectedJobLocationGroup) {
                    this.searchResults.visibleJobLocationGroup = searchResults.jobLocationGroups[selectedJobLocationGroup];
                }
            }
        },
        methods: {
            updateSelectedJobLocationGroup(id) {
                this.searchResults.selectedJobLocationGroup = id;
            },
            updateMapWithJobLocationGroupMarkers(jobLocationGroups) {
                const markerArgs = [];
                for (let group in jobLocationGroups) {

                    markerArgs.push({
                            class: 'search__map-marker--job-location-group',
                            solid: true,
                            amount: jobLocationGroups[group].length,
                            groupId: group,
                            clickCallback: this.updateSelectedJobLocationGroup.bind(this, group)
                    });
                }
                for (let i in markerArgs) {
                    if (i == 0) {
                        markerArgs[i].selected = true;
                        this.updateSelectedJobLocationGroup(markerArgs[i].groupId);
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
            updateJobsWithDistanceMatrixData(jobs, elements) {
                for (let i = 0; i < elements.length; i++) {
                    const el = elements[i];
                    const job = jobs[i];
                    job.distance = el.distance.text;
                    job.distanceTime = el.duration.text;
                }
            },
            createJobLocationGroups(jobs) {
                const jobLocationGroups = {};
                for (let i = 0; i < jobs.length; i++) {
                    const latLngStr = jobs[i].lat + ',' + jobs[i].lng;
                    if (typeof jobLocationGroups[latLngStr] == 'undefined') {
                        jobLocationGroups[latLngStr] = [jobs[i]];
                    } else {
                        jobLocationGroups[latLngStr].push(jobs[i]);
                    }
                }
                return jobLocationGroups;
            },
            handleDistanceMatrixData(response, status) {
                if (status != google.maps.DistanceMatrixStatus.OK) {
                    // TODO handle no distance matrix response
                } else {
                    if (response.rows[0].elements[0].status === "ZERO_RESULTS") {
                        console.error('distance matrix: zero results'); // TODO handle zero results
                    } else {
                        const sr = this.searchResults;
                        this.updateJobsWithDistanceMatrixData(sr.jobs, response.rows[0].elements);
                        sr.jobLocationGroups = this.createJobLocationGroups(sr.jobs);
                        sr.selectedJobLocationGroup = getFirstElementInObject(sr.jobLocationGroups);
                        sr.display = true;

                        // create map now that containing div is visible
                        this.map = new google.maps.Map(
                            document.getElementsByClassName('search__map')[0]
                            , this.mapOptions
                        );

                        // create map markers
                        new CustomMarker(this.mapOptions.center, this.map, {class: 'search__map-marker--datum'});
                        this.updateMapWithJobLocationGroupMarkers(sr.jobLocationGroups);
                    }
                }
            },
            updateJobsWithGeocoderData(origin) {
                const jobLatLngStrs = [];
                for (let job of this.searchResults.jobs) {
                    jobLatLngStrs.push(job.lat + ',' + job.lng);
                }
                var service = new google.maps.DistanceMatrixService();
                service.getDistanceMatrix(
                    {
                        origins: [origin],
                        destinations: jobLatLngStrs,
                        travelMode: google.maps.TravelMode.DRIVING,
                        unitSystem: google.maps.UnitSystem.IMPERIAL,
                        avoidHighways: false,
                        avoidTolls: false
                    }, this.handleDistanceMatrixData);
            },
            processGeocoderResults(results, status) {
                console.log('processGeocoderResults');
                if (status == google.maps.GeocoderStatus.OK) {
                    console.log('processGeocoderResults status is ok');
                    const location = results[0].geometry.location;
                    const latLng = new google.maps.LatLng(location.lat(), location.lng());

                    // create map
                    this.mapOptions = Object.assign({}, this.mapOptions, {center: latLng});

                    this.updateJobsWithGeocoderData(location.lat() + ',' + location.lng());
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            },
            search() {
                console.log('search', this.searchResults.postCode);
                new google.maps.Geocoder().geocode(
                    { 'address': this.searchResults.postCode},
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
        }
    }
</script>