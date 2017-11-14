<template>
    <div class="text-and-image"
         :class="typeClass"
    >
        <div class="text-and-image__wrapper">
            <div class="text-and-image__image-container">
                <img class="text-and-image__image"
                     :src="imgUrl"
                     v-if="imgUrl"
                />
                <div class="text-and-image__map"
                        v-if="googleMapUrl"
                >
                </div>
            </div>
            <div class="text-and-image__text-container">

                <h2 class="text-and-image__title"
                    v-if="mainTitle"
                >{{mainTitle}}</h2>

                <h3
                        class="text-block__subtitle"
                        v-if="subtitle"
                >{{subtitle}}</h3>

                <h3 class="text-and-image__quote-container"
                    v-if="quote"
                >
                    <div class="text-and-image__quote-mark"></div>
                    <div class="text-and-image__quote-text">{{quote}}</div>
                </h3>

                <h3 class="text-and-image__quote-source"
                    v-if="quoteSource"
                >{{quoteSource}}</h3>

                <div class="text-and-image__text">
                    <slot></slot>
                </div>

                <div class="text-and-image__content">

                </div>
            </div>
        </div>
    </div>
</template>
<script>
    const textImageBlock = {
        props: [
            'main-title',
            'subtitle',
            'text',
            'img-url',
            'quote',
            'quote-source',
            'type',
            'google-map-embed-search-parameter',
            'coords'
        ],
        computed: {
            typeClass: function() {
                if (this.type) {
                    return 'text-and-image--' + this.type;
                } else {
                    return ''
                }
            },
            googleMapUrl: function() {
                //'google-map-embed-search-parameter'
                console.log('google map url');
            }
        },
        methods: {
            mountMap: function() {
                console.log('mountMap');
                const div = document.getElementsByClassName('text-and-image__map')[0];

                if (typeof div !== 'undefined') {
                    const googleMapsEmbedAPIKey = 'AIzaSyCJFyhP2efu2_Uqrci-BrxjrjDi5dHsegk'; // TODO this should come from another file
                    const zoomLevel = 14;

                    //const latLng = new google.maps.LatLng(location.lat(), location.lng());

                    const mapOptions = {
                        zoom: 9,
                        center: new google.maps.LatLng(51.5449765,-0.1182413),
                        disableDefaultUI: true
                    };

                    this.map = new google.maps.Map(div, mapOptions);

                    return '//www.google.com/maps/embed/v1/place?q='
                        + this.googleMapEmbedSearchParameter
                        + '&zoom=' + zoomLevel
                        + '&key=' + googleMapsEmbedAPIKey
                } else {
                    return false;
                }
            }
        },
        created() {
            console.log('created');

//            window.addEventListener(
//                'load',
//                function() {
//                    console.log('asdf');
//                },//this.mountMap,
//                false
//            );

            document.addEventListener(
                'DOMContentLoaded',
                function() {
                    console.log('asdf');
                },//this.mountMap,
                false
            );
        },
        mounted() {
            console.log('mounted');
            console.dir(document.getElementsByClassName('text-and-image__map')[0]);

        },
        ready() {
            console.log('ready');
            console.dir(document.getElementsByClassName('text-and-image__map')[0]);
        }
    };

    document.addEventListener(
        'DOMContentLoaded',
        function() {
            console.log('document.addEventListener');
            console.dir(document.getElementsByClassName('text-and-image__map')[0]);
        },//this.mountMap,
        false
    );
    export default textImageBlock;
</script>
