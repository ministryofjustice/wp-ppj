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
                            <div class="search__job-post">
                                <div class="search__job-post-distance">0.88 miles</div>
                                <div class="search__job-post-salary">£31,560</div>
                                <div class="search__job-post-prison">
                                    <div class="search__job-post-prison-name">HMP Belmarsh</div>
                                    <div class="search__job-post-prison-city">London</div>
                                </div>
                                <a class="search__job-post-link">view job</a>
                            </div>
                        </div>
                        <ul class="search__view-list" v-show="searchResults.activeView == 1">
                            <li class="search__job-post">
                                <div class="search__job-post-distance">0.88 miles</div>
                                <div class="search__job-post-position">Prison Officer</div>
                                <div class="search__job-post-salary">£31,560</div>
                                <div class="search__job-post-prison">
                                    <div class="search__job-post-prison-name">HMP Belmarsh</div>
                                    <div class="search__job-post-prison-city">London</div>
                                </div>
                                <a class="search__job-post-link">view job</a>
                            </li>
                            <li class="search__job-post">
                                <div class="search__job-post-distance">1.03 miles</div>
                                <div class="search__job-post-position">Prison Officer</div>
                                <div class="search__job-post-salary">£22,396</div>
                                <div class="search__job-post-prison">
                                    <div class="search__job-post-prison-name">HMP Pentonville</div>
                                    <div class="search__job-post-prison-city">London</div>
                                </div>
                                <a class="search__job-post-link">view job</a>
                            </li>
                            <li class="search__job-post">
                                <div class="search__job-post-distance">1.15 miles</div>
                                <div class="search__job-post-position">Prison Officer</div>
                                <div class="search__job-post-salary">£22,396</div>
                                <div class="search__job-post-prison">
                                    <div class="search__job-post-prison-name">HMP Belmarsh</div>
                                    <div class="search__job-post-prison-city">London</div>
                                </div>
                                <a class="search__job-post-link">view job</a>
                            </li>
                            <li class="search__job-post">
                                <div class="search__job-post-distance">0.88 miles</div>
                                <div class="search__job-post-position">Prison Officer</div>
                                <div class="search__job-post-salary">£29,981</div>
                                <div class="search__job-post-prison">
                                    <div class="search__job-post-prison-name">HMP Belmarsh</div>
                                    <div class="search__job-post-prison-city">London</div>
                                </div>
                                <a class="search__job-post-link">view job</a>
                            </li>
                            <li class="search__job-post">
                                <div class="search__job-post-distance">1.30 miles</div>
                                <div class="search__job-post-position">Prison Officer</div>
                                <div class="search__job-post-salary">£26,950</div>
                                <div class="search__job-post-prison">
                                    <div class="search__job-post-prison-name">HMP Belmarsh</div>
                                    <div class="search__job-post-prison-city">London</div>
                                </div>
                                <a class="search__job-post-link">view job</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
</template>

<script>
    export default {
        data() {
            return {
                searchResults: {
                    activeView: 0,
                    display: true,
                    postCode: '',
                    urlEncodedPostCode: '',
                    googleMapAPIKey: 'AIzaSyD6bT-hldMJMz4jwUgJ2W1YA-bXpROvKHk',

                },
                mapSrc: '',
                titleText: 'Search for vacancies near you',
                placeHolderText: 'Enter your post code'
            }
        },
        watch: {
            'searchResults.postCode': function(val) {
                this.searchResults.urlEncodedPostCode = encodeURIComponent(val);
                console.log('updated urlEncodedPostCode ', this.searchResults.urlEncodedPostCode);
            },
            'searchResults.urlEncodedPostCode': function(val) {
                if (val) {
                    const result = "https://www.google.com/maps/embed/v1/place?q="
                        + val
                        + "&key=" + this.searchResults.googleMapAPIKey;
                    this.mapSrc = result;
                    console.log('updated mapSrc', this.mapSrc);
                }
            }
        },
        methods: {
            search() {
                this.searchResults.display = true;
            },
            showMapView() {
                console.log('show map view');
                this.searchResults.activeView = 0;
            },
            showListView() {
                console.log('show list view');
                this.searchResults.activeView = 1;
            }
        },
        mounted() {
            console.log('mounted, setting post code to SW1H 9AJ');
            this.searchResults.postCode = 'SW1H 9AJ';
        }
    }
</script>

<style>

</style>