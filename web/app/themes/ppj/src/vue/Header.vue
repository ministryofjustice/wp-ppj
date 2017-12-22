<template>
    <div class="header"
         :class="{
           'header--nav-menu-open': menuOpen,
           'header--style-dark': (headerStyle == 'dark')
         }"
         v-cloak
    >
        <div class="l-site-header">
            <div class="header__site-header">
              <div class="header__site-header-content">
                <a href="/" class="header__logo"></a>
                <nav-link v-on:request-open-nav-menu="openNavMenu"></nav-link>
                <nav-menu :menu-data='menuData' v-on:request-close-nav-menu="closeNavMenu"></nav-menu>
              </div>
            </div>
        </div>
        <div class="header__hero">
            <div class="header__img-container"
                 v-if="(backgroundImage == true) && (carouselImageURLs.length > 0)"
            >
                <img class="header__carousel-image"
                     v-if="(carouselImageURLs.length ==  1)"
                     :src="carouselImageURLs[0]"
                />
                <slick ref="slick" :options="slickOptions" v-if="(carouselImageURLs.length > 1)">

                    <img class="header__carousel-image"
                         v-for="(url, index) in carouselImageURLs"
                         :src="url"
                    />
                </slick>
            </div>
            <div class="header__overlay">
                <div class="l-full">
                  <div class="header__text-container">
                      <div class="header__text"
                           v-if="hasDefaultSlot"
                      ><slot></slot></div>
                      <div class="header__subtext"
                           v-if="headerSubtext">
                          {{headerSubtext}}
                      </div>
                  </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
    import Vue from 'vue';
    import Slick from 'vue-slick';

    const NavLink = {
        template:
            `<div class="header__nav-link js" @click="openNavMenu">
                <!--<div></div>
                <div></div>
                <div></div>-->
                MENU
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
                    <li v-for="item in menuData"
                        class="header__nav-menu-list-element"
                        :class="{'header__nav-menu-list-element--selected' : item.selected}"
                    ><a :href="item.url">{{item['title']}}</a></li>
                </ul>
            </div>`,

        methods: {
            closeNavMenu: function() {
                this.$emit('request-close-nav-menu', true);
            }
        },

        mounted() {
        },

        props: ['menu-data']
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
            'header-subtext': {
                default: ''
            },
            'background-image': {
                default: true
            },
            'carousel-images': {
                default: ''
            },
            'menu-data': {
                default: {}
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
                  slidesToShow: 1,
              }
          }
        },

        computed:  {
            hasDefaultSlot: function() {
                return !!this.$slots.default
            },
            carouselImageURLs: function() {
                if (this.carouselImages) {
                    const urls = this.carouselImages.split(',');
                    return urls;
                } else {
                    return [];
                }
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
