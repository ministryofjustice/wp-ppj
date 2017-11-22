<template>
    <div class="video-player" v-cloak>
        <!--<div class="video-player__play-button"></div>-->
        <iframe v-if="src"
                :src="src"
                webkitallowfullscreen='true'
                mozallowfullscreen='true'
                allowfullscreen='true'
        >
            <p style="text-align:center">Iframes are required to view this content.</p>
        </iframe>
    </div>
</template>

<script>
    export default {
        props: {
            src: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
            }
        },
        watch: {
        },
        methods: {
        },
        mounted() {
        }
    }
</script>
