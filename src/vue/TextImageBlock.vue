<template>
    <div class="text-and-image"
         :class="typeClass"
    >
        <div class="text-and-image__wrapper">
            <div class="text-and-image__image-container">
                <img class="text-and-image__image"
                     :src="imgUrl" />
            </div>
            <div class="text-and-image__text-container">

                <h2 class="text-and-image__title"
                    v-if="mainTitle"
                >{{mainTitle}}</h2>

                <h3 class="text-and-image__quote"
                    v-if="quote"
                >{{quote}}</h3>

                <h3 class="text-and-image__quote-source"
                    v-if="quoteSource"
                >{{quoteSource}}</h3>

                <h3 class="text-and-image__text">
                    <slot></slot>
                </h3>

                <div class="text-and-image__content">

                </div>
            </div>

        </div>
    </div>
</template>
<script>
    export default {
        props: ['main-title', 'text', 'img-url', 'quote', 'quote-source', 'type'],
        computed: {
            typeClass: function() {
                if (this.type) {
                    return 'text-and-image--' + this.type;
                } else {
                    return ''
                }
            }
        }
    }
</script>
