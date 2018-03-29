//import Waypoint from 'waypoint';
//Scrollpoints = require('scrollpoints');
import scrollpoints from 'scrollpoints';
import Vue from 'vue';

import Accordion from '../vue/Accordion.vue';
import JobSummary from '../vue/JobSummary.vue';
import Search from '../vue/Search.vue';
import TextBlock from '../vue/TextBlock.vue';
import VideoPlayer from '../vue/VideoPlayer.vue';

window.ppj = {};

window.ppjNavTo = function(href, callback) {
  console.log('navTo');
  if (typeof callback !== 'undefined') {
    callback()
  }
  window.location = href;
};

window.ppj.handleImageLoaded = function() {
  document.getElementsByClassName('header__image')[0].classList.add('header__image--loaded');
};

window.ppj.openNavMenu = function() {
  document.getElementsByTagName('body')[0].style.overflow = 'hidden';
  document.getElementsByClassName('header')[0].classList.add('header--nav-menu-open');
};

window.ppj.closeNavMenu = function() {
  document.getElementsByTagName('body')[0].style.overflow = '';
  document.getElementsByClassName('header')[0].classList.remove('header--nav-menu-open');
};

window.addEventListener('load', function() {

  // if (document.querySelectorAll('#site-container').length > 0) {
  //
  //   Vue.component('accordion', Accordion);
  //   Vue.component('accordion-element', Accordion.childComponents.Element);
  //   Vue.component('job-summary', JobSummary);
  //   Vue.component('search', Search);
  //   Vue.component('text-block', TextBlock);
  //   Vue.component('video-player', VideoPlayer);
  //
  //   var vm = new Vue({
  //     el: '#site-container',
  //     methods: {
  //       pageLoaded: function () {
  //         this.$emit('pageLoaded');
  //       }
  //     }
  //   });
  //
  // }

  const scrollPointConfig = {
    when: 'entering'
  };

  // fade content in on scroll
  const els = document.querySelectorAll('.l-full, .l-half');
  for (let i in els) {
    scrollpoints.add(
      els[i],
      (el) => {
        el.style.opacity = 1
      },
      scrollPointConfig
    )
  }

  // hide html until Vue has rendered
  const html = document.getElementsByTagName('html')[0];
  html.style.opacity = 1;


}, false);

