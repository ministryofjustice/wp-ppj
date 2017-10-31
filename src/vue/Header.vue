<template>
    <div class="header"
         :class="{'header--nav-menu-open': menuOpen}">
        <div class="header__logo"></div>
        <div class="header__text"><slot></slot></div>
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
                console.log('sending request-open-nav-menu');
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
                    <li class="header__nav-menu-list-element"><a href="/apply.html">Apply <span class="header__nav-menu-list-element-subtext">I'm ready to join</span></a></li>
                    <li class="header__nav-menu-list-element"><a href="/role.html">Working as a Prison Officer</a></li>
                    <li class="header__nav-menu-list-element"><a href="">Find a role</a></li>
                    <li class="header__nav-menu-list-element"><a href="">Is it right for me</a></li>
                    <li class="header__nav-menu-list-element"><a href="">Working in a prison setting</a></li>
                    <li class="header__nav-menu-list-element"><a href="">Application steps</a></li>
                    <li class="header__nav-menu-list-element"><a href="">Eligibility Checker</a></li>
                </ul>
            </div>`,
        methods: {
            closeNavMenu: function() {
                this.$emit('request-close-nav-menu', true);
            }
        }
    };

    export default {
        props: ['title', 'subtitle'],

        data() {
          return {
              menuOpen: false
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
            }
        },

        childComponents: {

        }
    }
</script>