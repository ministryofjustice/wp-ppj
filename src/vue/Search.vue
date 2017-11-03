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
                            <div class="search__map">
                                <iframe width="280"
                                        height="180"
                                        frameborder="0"
                                        :src="mapSrc"
                                        allowfullscreen></iframe>
                            </div>
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
                                <button class="search__pagination-link"
                                        :class="{'search__pagination-link--active': (searchResults.listView.activePage == 0)}"
                                >1</button>
                                <button class="search__pagination-link"
                                        :class="{'search__pagination-link--active': (searchResults.listView.activePage == 1)}"
                                >2</button>
                                <button class="search__pagination-link"
                                        :class="{'search__pagination-link--active': (searchResults.listView.activePage == 2)}"
                                >3</button>
                                <button class="search__pagination-link"
                                        :class="{'search__pagination-link--active': (searchResults.listView.activePage == 3)}"
                                >4</button>
                                <button class="search__pagination-direction"
                                        :class="{'search__pagination-direction--active': (forwardEnabled == true)}"
                                        @click.stop.prevent="showNextPage"
                                > > </button>
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
        }
    ];

    export default {
        data() {
            return {
                searchResults: {
                    activeView: 0,
                    display: false,
                    postCode: '',
                    urlEncodedPostCode: '',
                    googleMapAPIKey: 'AIzaSyD6bT-hldMJMz4jwUgJ2W1YA-bXpROvKHk',
                    listView: {
                        activePage: 0,
                        resultsPerPage: 5,
                        forwardEnabled: true,
                        backwardEnabled: false
                    },
                    jobs: dummyJobs
                },
                mapSrc: '',
                titleText: 'Search for vacancies near you',
                placeHolderText: 'Enter your post code'
            }
        },
        computed: {
            numberOfResultPages: function() {
                const num = Math.ceil(this.searchResults.jobs.length / this.searchResults.listView.resultsPerPage);
                console.log('num of result pages', num);
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
            'searchResults.postCode': function(val) {
                this.searchResults.urlEncodedPostCode = encodeURIComponent(val);
            },
            'searchResults.urlEncodedPostCode': function(val) {
                if (val) {
                    const result = "https://www.google.com/maps/embed/v1/place?q="
                        + val
                        + "&key=" + this.searchResults.googleMapAPIKey;
                    this.mapSrc = result;
                }
            }
        },
        methods: {
            search() {
                this.searchResults.display = true;
            },
            showMapView() {
                this.searchResults.activeView = 0;
            },
            showListView() {
                this.searchResults.activeView = 1;
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
            //this.searchResults.postCode = 'SW1H 9AJ';
        }
    }
</script>