<template>
    <div class="header"
         :class="{
           'header--nav-menu-open': menuOpen,
           'header--style-light': (headerStyle == 'light')
         }"
    >
        <a href="/" class="header__logo"></a>
        <div class="header__img-container"
             v-if="backgroundImage == true"
        >
            <img class="header__carousel-image"
                 v-for="(url, index) in carouselImageURLs"
                 :src="url"
                 :class="[{'header__carousel-image--active': (index == selectedCarouselImg) }]"
            />
            <div class="header__carousel-controls-container"
                 v-if="carouselImageURLs.length > 1">
                <button class="header__carousel-button"
                        v-for="(url, index) in carouselImageURLs"
                        :class="[{'header__carousel-button--active': (index == selectedCarouselImg) }]"
                        @click="changeCarouselImage(index)"
                >
                </button>
            </div>
        </div>

        <div class="header__text"
             v-if="hasDefaultSlot"
        ><slot></slot></div>
        <nav-link v-on:request-open-nav-menu="openNavMenu"></nav-link>
        <nav-menu v-on:request-close-nav-menu="closeNavMenu"></nav-menu>
    </div>
</template>
<script>
    import Vue from 'vue';

    const NavLink = {
        template:
            `<div class="header__nav-link js" @click="openNavMenu">
                <div></div>
                <div></div>
                <div></div>
            </div>`,

        methods: {
            openNavMenu: function() {
                this.$emit('request-open-nav-menu', true);
            }
        }
    };

    const NavMenu = {
        template:
            ` <div class="header__nav-menu">
                <button class="header__nav-menu-close-button js" @click="closeNavMenu"></button>
                <ul class="header__nav-menu-list">
                    <li class="header__nav-menu-list-element"><a href="/">Home</a></li>
                    <li class="header__nav-menu-list-element"><a href="apply.html">Apply <span class="header__nav-menu-list-element-subtext">I'm ready to join</span></a></li>
                    <li class="header__nav-menu-list-element"><a href="role.html">Working as a Prison Officer</a></li>
                    <li class="header__nav-menu-list-element"><a href="prison-search.html">Prison search</a></li>
                    <li class="header__nav-menu-list-element"><a href="/hmp-pentonville.html">HMP Pentonville</a></li>
                    <li class="header__nav-menu-list-element"><a href="/job-post.html">Job post</a></li>
                </ul>
            </div>`,

        methods: {
            closeNavMenu: function() {
                this.$emit('request-close-nav-menu', true);
            }
        }
    };

    export default {
        props: {
            'title': {
                default: ''
            },
            'subtitle': {
                default: ''
            },
            'header-style': {
                default: 'dark'
            },
            'background-image': {
                default: true
            },
            'carousel-images': {
                default: ''
            }
        },

        data() {
          return {
              menuOpen: false,
              selectedCarouselImg: 0,
              carouselIntervalId: -1,
              foo: true
          }
        },

        computed:  {
            hasDefaultSlot: function() {
                return !!this.$slots.default
            },
            carouselImageURLs: function() {
                const urls = this.carouselImages.split(',');
                return urls;
            }
        },

        components: {
            'nav-menu': NavMenu,
            'nav-link': NavLink
        },

        methods: {
            openNavMenu() {
                this.menuOpen = true;
            },
            closeNavMenu(){
                this.menuOpen = false;
            },
            changeCarouselImage(index) {
                if (this.selectedCarouselImg == index) {
                    this.startCarouselTransition();
                } else {
                    this.selectedCarouselImg = index;
                    clearInterval(this.carouselIntervalId);
                }
            },
            startCarouselTransition(){
                if (this.carouselImageURLs.length > 1) {
                    this.carouselIntervalId = setInterval(()=>{
                        this.selectedCarouselImg =
                            (this.selectedCarouselImg + 1) % this.carouselImageURLs.length
                    }, 5000)
                }
            }
        },

        mounted() {
            this.startCarouselTransition();
        },

        childComponents: {

        }
    }
</script>