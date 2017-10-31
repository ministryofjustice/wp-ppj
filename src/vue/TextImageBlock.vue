<template>
    <div class="text-and-image">
        <div class="text-and-image__main-section">
            <img
                    class="text-and-image__image"
                    :src="imgUrl" />
            <h2
                    class="text-and-image__title"
                    v-if="myTitle"
            >{{myTitle}}</h2>
            <h3
                    class="text-and-image__quote"
                    v-if="quote"
            >{{quote}}</h3>
            <h3
                    class="text-and-image__text"
                    v-if="text"
            >{{text}}</h3>
        </div>
        <div class="text-and-image__content">
            <slot></slot>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['my-title', 'text', 'img-url', 'quote']
    }
</script>
