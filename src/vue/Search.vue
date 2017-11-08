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
                                    :prison-name="searchResults.jobs[0].prisonName"
                                    :prison-city="searchResults.jobs[0].prisonCity"
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
                                            :position="job.position"
                                            :salary="job.salary"
                                            :prison-name="job.prisonName"
                                            :prison-city="job.prisonCity"
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
        {
            distance: "0.88 miles",
            position: "Prison Officer",
            salary: "£1,560",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "1.03 miles",
            position: "Prison Officer",
            salary: "£22,396",
            prisonName : "HMP Pentonville",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "1.15 miles",
            position: "Prison Officer",
            salary: "£22,396",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "0.88 miles",
            position: "Prison Officer",
            salary: "£29,981",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "1.30 miles",
            position: "Prison Officer",
            salary: "£26,950",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "0.88 miles",
            position: "Prison Officer",
            salary: "£41,560",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "1.03 miles",
            position: "Prison Officer",
            salary: "£32,396",
            prisonName : "HMP Pentonville",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "1.15 miles",
            position: "Prison Officer",
            salary: "£32,396",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "0.88 miles",
            position: "Prison Officer",
            salary: "£39,981",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "1.30 miles",
            position: "Prison Officer",
            salary: "£36,950",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },


        {
            distance: "0.88 miles",
            position: "Prison Officer",
            salary: "£19,560",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "1.03 miles",
            position: "Prison Officer",
            salary: "£32,396",
            prisonName : "HMP Pentonville",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "1.15 miles",
            position: "Prison Officer",
            salary: "£32,396",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "0.88 miles",
            position: "Prison Officer",
            salary: "£39,981",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "1.30 miles",
            position: "Prison Officer",
            salary: "£36,950",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "0.88 miles",
            position: "Prison Officer",
            salary: "£51,560",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "1.03 miles",
            position: "Prison Officer",
            salary: "£42,396",
            prisonName : "HMP Pentonville",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "1.15 miles",
            position: "Prison Officer",
            salary: "£42,396",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "0.88 miles",
            position: "Prison Officer",
            salary: "£49,981",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        },
        {
            distance: "1.30 miles",
            position: "Prison Officer",
            salary: "£46,950",
            prisonName : "HMP Belmarsh",
            prisonCity : "London",
            url: "/job-post.html"
        }
    ];

    function CustomMarker(latlng, map, args) {
        this.latlng = latlng;
        this.args = args;
        this.setMap(map);
    }

    CustomMarker.prototype = new google.maps.OverlayView();

    CustomMarker.prototype.draw = function() {

        var self = this;

        var div = this.div;

        if (!div) {

            div = this.div = document.createElement('div');

            div.className = 'marker';

            div.style.position = 'absolute';
            div.style.cursor = 'pointer';
            div.style.width = '20px';
            div.style.height = '20px';
            div.style.background = 'blue';

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
                    display: false,
                    postCode: '',
                    urlEncodedPostCode: '',
                    //googleMapAPIKey: 'AIzaSyD6bT-hldMJMz4jwUgJ2W1YA-bXpROvKHk',
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
                    zoom: 10,
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
            createMarker(lat, lng) {
                const latLng = new google.maps.LatLng(lat, lng);

                return new CustomMarker(
                    latLng,
                    this.map,
                    {
                        marker_id: '123'
                    }
                );
            },
            updateMapWithGeocoderResults(results, status) {
                const self = this;
                if (status == google.maps.GeocoderStatus.OK) {
                    const location = results[0].geometry.location;
                    const latLng = new google.maps.LatLng(location.lat(), location.lng());
                    const mapOptions = Object.assign({}, self.mapOptions, {center: latLng});
                    self.map = new google.maps.Map(document.getElementsByClassName('search__map')[0], mapOptions);
                    const marker = new google.maps.Marker({
                        map: self.map,
                        position: latLng
                    });
                    this.searchResults.display = true;
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            },
            search() {
                new google.maps.Geocoder().geocode(
                    { 'address': this.searchResults.postCode},
                    this.updateMapWithGeocoderResults
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