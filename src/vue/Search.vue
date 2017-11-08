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
                        @click.stop.prevent="search">
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
                            <job-summary
                                    :distance="searchResults.jobs[0].distance"
                                    :position="searchResults.jobs[0].position"
                                    :salary="searchResults.jobs[0].salary"
                                    :prison-name="searchResults.jobs[0].organisationName"
                                    :prison-city="searchResults.jobs[0].organisationCity"
                                    :url="searchResults.jobs[0].url"
                            >
                            </job-summary>
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
                                            :prison-name="job.organisationName"
                                            :prison-city="job.organisationCity"
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
    const dummyJobs = [
        // page 1
        {
            distance: "0.88 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£31,981",
            organisationName : "HMYOI Feltham",
            organisationCity : "London",
            url: "/job-post.html",
            link: 'https://justicejobs.tal.net/vx/lang-en-GB/mobile-0/appcentre-1/brand-2/candidate/so/pm/1/pl/3/opp/12797-201709-Prison-Officer-HMYOI-Feltham/en-GB',
            lat: 51.4415566,
            lng: -0.4362452
        },
        {
            distance: "1.03 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£22,396",
            organisationName : "HMP Pentonville",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.5449765,
            lng: -0.1182413
        },
        {
            distance: "1.15 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£22,396",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        },
        {
            distance: "0.88 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£29,981",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        },
        {
            distance: "1.30 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£26,950",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        },

        // page 2
        {
            distance: "0.88 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£41,560",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        },
        {
            distance: "1.03 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£32,396",
            organisationName : "HMP Pentonville",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.5449765,
            lng: -0.1182413
        },
        {
            distance: "1.15 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£32,396",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        },
        {
            distance: "0.88 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£39,981",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        },
        {
            distance: "1.30 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£36,950",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        },

        // page 3
        {
            distance: "0.88 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£19,560",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        },
        {
            distance: "1.03 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£32,396",
            organisationName : "HMP Pentonville",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.5449765,
            lng: -0.1182413
        },
        {
            distance: "1.15 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£32,396",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        },
        {
            distance: "0.88 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£39,981",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        },
        {
            distance: "1.30 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£36,950",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        },

        // page 4
        {
            distance: "0.88 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£51,560",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        },
        {
            distance: "1.03 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£42,396",
            organisationName : "HMP Pentonville",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.5449765,
            lng: -0.1182413
        },
        {
            distance: "1.15 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£42,396",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        },
        {
            distance: "0.88 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£49,981",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        },
        {
            distance: "1.30 miles",
            distanceTime: '', position: "Prison Officer",
            salary: "£46,950",
            organisationName : "HMP Belmarsh",
            organisationCity : "London",
            url: "/job-post.html",
            lat: 51.4961652,
            lng: 0.0910377
        }
    ];

    function CustomMarker(latlng, map, args) {
        this.latlng = latlng;
        this.args = args;
        this.setMap(map);
    }

    function styleMarker(div, args) {
        div.style.position = 'absolute';
        div.style.cursor = 'pointer';
        div.style.width = '10px';
        div.style.height = '10px';
        div.style.border = '2px solid black';
        div.style.borderRadius = '10px';

        if (args.solid) {
            div.style.background = 'black';
        } else {

        }
    }

    CustomMarker.prototype = new google.maps.OverlayView();

    CustomMarker.prototype.draw = function() {
        console.log('drawing marker');

        var self = this;

        var div = this.div;

        if (!div) {

            div = this.div = document.createElement('div');

            div.className = 'search_job-marker';

            styleMarker(div, self.args);

            if (typeof(self.args.marker_id) !== 'undefined') {
                div.dataset.marker_id = self.args.marker_id;
            }

            google.maps.event.addDomListener(div, "click", function(event) {
                alert('You clicked on a custom marker!');
                google.maps.event.trigger(self, "click");
            });

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


    export default {
        data() {
            return {
                searchResults: {
                    activeView: 0,
                    display: true, // TODO revert
                    postCode: 'SW1H 1AJ',
                    urlEncodedPostCode: '',
                    googleMapAPIKey: 'AIzaSyDDplfBkLzNA3voskfGyExYnQ46MJ0VtpA',
                    listView: {
                        activePage: 0,
                        resultsPerPage: 5,
                        forwardEnabled: true,
                        backwardEnabled: false
                    },
                    jobs: dummyJobs
                },
                mapSrc: '',
                mapOptions: {
                    zoom: 8,
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

        },
        methods: {
            updateMapWithJobMarkers(jobs){
                for (let i = 0; i < jobs.length; i++) {
                    const latLng = new google.maps.LatLng(jobs[i].lat, jobs[i].lng);
                    new CustomMarker(latLng, this.map, {marker_id: '123', solid: true});
                }
            },
            updateJobsWithDistanceMatrixData(elements) {
                for (let i = 0; i < elements.length; i++) {
                    const el = elements[i];
                    const job = this.searchResults.jobs[i];
                    job.distance = el.distance.text;
                    job.distanceTime = el.duration.text;
                }
            },
            handleDistanceMatrixData(response, status) {
                console.log('handleDistanceMatrixData');
                if (status != google.maps.DistanceMatrixStatus.OK) {
                    //$('#result').html(err);
                } else {
                    var origin = response.originAddresses[0];
                    var destination = response.destinationAddresses[0];
                    if (response.rows[0].elements[0].status === "ZERO_RESULTS") {
                        //$('#result').html("Better get on a plane. There are no roads between " + origin + " and " + destination);
                        console.log('distance matrix: zero results');
                    } else {
                        var distance = response.rows[0].elements[0].distance;
                        var distance_value = distance.value;
                        var distance_text = distance.text;
                        var miles = distance_text.substring(0, distance_text.length - 3);
                        //$('#result').html("It is " + miles + " miles from " + origin + " to " + destination);

                        this.updateJobsWithDistanceMatrixData(response.rows[0].elements);
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
                if (status == google.maps.GeocoderStatus.OK) {
                    const location = results[0].geometry.location;
                    const latLng = new google.maps.LatLng(location.lat(), location.lng());
                    const mapOptions = Object.assign({}, this.mapOptions, {center: latLng});
                    this.map = new google.maps.Map(document.getElementsByClassName('search__map')[0], mapOptions);
                    new CustomMarker(latLng, this.map, {marker_id: '123', solid: false});

                    const locationString = '' + location.lat() + ',' + location.lng();
                    this.updateJobsWithGeocoderData(locationString);

                    this.updateMapWithJobMarkers(this.searchResults.jobs);

                    this.searchResults.display = true;
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            },
            search() {
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