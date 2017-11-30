<template>
<!--    <script :src="wistia.src" async="true"></script>
    <script src="//fast.wistia.com/assets/external/E-v1.js" async="true"></script>
    <div :class="wistia.class" style="height:349px;width:620px">&nbsp;</div>-->

    <div class="video-player" v-cloak>
        <!--<div class="video-player__play-button"></div>-->
        <iframe v-if="src && (type == 'youtube')"
                :src="src"
                webkitallowfullscreen='true'
                mozallowfullscreen='true'
                allowfullscreen='true'
        >
            <p style="text-align:center">Iframes are required to view this content.</p>
        </iframe>

        <div v-if="id && (type == 'wistia')">

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            host: {
                type: String,
                default: 'youtube'
            },
            id: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                youtube: {
                    src: ''
                },
                wistia: {
                    src: "//fast.wistia.com/embed/medias/" + this.id + ".jsonp",
                    class: "wistia_embed wistia_async_" + this.id
                }
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
