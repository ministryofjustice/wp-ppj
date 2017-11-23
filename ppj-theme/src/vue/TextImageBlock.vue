<template>
    <div class="text-and-image"
         :class="typeClass"
         v-cloak
    >
        <div class="text-and-image__wrapper">
            <div class="text-and-image__image-container">
                <img class="text-and-image__image"
                     :src="imgUrl"
                     v-if="imgUrl"
                />
                <div class="text-and-image__map"
                    v-if="coords"
                >
                </div>
            </div>
            <div class="text-and-image__text-container">

                <h2 class="text-and-image__title"
                    v-if="mainTitle"
                >{{mainTitle}}</h2>

                <h3 class="text-block__subtitle"
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
    import CustomMarker from '../js/CustomMarker';

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
        data() {
            return {
                updatedOnce: false
            }
        },
        computed: {
            typeClass: function() {
                if (this.type) {
                    return 'text-and-image--' + this.type;
                } else {
                    return ''
                }
            },
            googleMapUrl: function() {

            },
            latLng: function() {
                if (this.coords) {
                    return this.coords.split(',');
                } else {
                    return false;
                }
            }
        },
        methods: {
            mountMap: function() {
                const div = document.getElementsByClassName('text-and-image__map')[0];
                if (typeof div !== 'undefined' && this.latLng) {
                    const latLng = new google.maps.LatLng(this.latLng[0], this.latLng[1]);

                    // create map
                    this.map = new google.maps.Map(div, {
                        zoom: 12,
                        center: latLng,
                        disableDefaultUI: true
                    });

                    // create custom marker
                    new CustomMarker(
                        latLng,
                        this.map,
                        {
                            selected: true
                        }
                    );
                }
            }
        },
        updated() {
            if (!this.updatedOnce) {
                this.updatedOnce = true;
                this.mountMap();
            }
        }
    };

    export default textImageBlock;
</script>
