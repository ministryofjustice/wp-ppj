<template>
    <div class="header"
         :class="{
           'header--nav-menu-open': menuOpen,
           'header--style-light': (headerStyle == 'light')
         }"
         v-cloak
    >

        <div class="header__img-container"
             v-if="backgroundImage == true"
        >
            <slick ref="slick" :options="slickOptions">

                <img class="header__carousel-image"
                     v-for="(url, index) in carouselImageURLs"
                     :src="url"
                />
            </slick>
        </div>
        <div class="header__overlay">
            <a href="/" class="header__logo"></a>
            <div class="header__text"
                 v-if="hasDefaultSlot"
            ><slot></slot></div>
            <nav-link v-on:request-open-nav-menu="openNavMenu"></nav-link>

        </div>
        <nav-menu v-on:request-close-nav-menu="closeNavMenu"></nav-menu>
    </div>
</template>
<script>
    import Vue from 'vue';

    import Slick from 'vue-slick';

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
                    <li class="header__nav-menu-list-element"><a href="role.html">About the role</a></li>
                    <li class="header__nav-menu-list-element"><a href="apply.html">How to apply</a></li>
                    <li class="header__nav-menu-list-element"><a href="prison-search.html">Prison search (demo only)</a></li>
                    <li class="header__nav-menu-list-element"><a href="/hmp-pentonville.html">HMP Pentonville (demo only)</a></li>
                    <li class="header__nav-menu-list-element"><a href="/job-post.html">Job post (demo only)</a></li>
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
              foo: true,
              slickOptions: {
                  arrows: false,
                  autoplay: true,
                  autoplaySpeed: 5000,
                  dots: true,
                  //fade: true,
                  slidesToShow: 1,
              }
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
            'nav-link': NavLink,
            Slick
        },

        methods: {
            openNavMenu() {
                this.menuOpen = true;
                document.getElementsByTagName('body')[0].style.overflow = 'hidden';
            },
            closeNavMenu(){
                this.menuOpen = false;
                document.getElementsByTagName('body')[0].style.overflow = '';
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
            },
            next() {
                this.$refs.slick.next();
            },
            prev() {
                this.$refs.slick.prev();
            },
            reInit() {
                // Helpful if you have to deal with v-for to update dynamic lists
                this.$nextTick(() => {
                    this.$refs.slick.reSlick();
                });
            },
        },

        mounted() {
            this.startCarouselTransition();
        },

        childComponents: {

        }
    }
</script>