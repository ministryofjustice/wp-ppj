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
            >
                <img class="header__carousel-image"
                     v-if="processedHeaderImageData"
                     :src='processedHeaderImageData.url'
                     :srcset='processedHeaderImageData.srcset'
                     :alt='processedHeaderImageData.alt'
                />
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
                        :class="{'header__nav-menu-list-element--selected' : item.selected}">
                        <a :href="item.url">
                          <span class="header__nav-menu-list-element-text">{{item['title']}}</span>
                        </a>
                    </li>
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
            'header-image': {
              default: ''
            },
            'menu-data': {
                default: {}
            }
        },

        data() {
          return {
              menuOpen: false
          }
        },

        computed:  {
          'hasDefaultSlot': function() {
            return !!this.$slots.default
          },

          'processedHeaderImageData': function() {
            if (this.headerImage) {
              const data = JSON.parse(this.headerImage);
              const img = {};

              // construct src
              img.url = data.url;

              const home = (this.headerStyle === 'home') ? '-home' : '';

              // construct srcset
              let srcset = '';
              for (const bp of ['mobile', 'portrait', 'landscape', 'large']) {
                const
                  url = data.sizes['header-' + bp + home],
                  width = data.sizes['header-' + bp + home + '-width']
                ;
                srcset += url + ' ' + width + 'w, ';
              }
              img.srcset = srcset;

              // construct alt
              img.alt = data.alt;

              return img;
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
        }
    }
</script>
