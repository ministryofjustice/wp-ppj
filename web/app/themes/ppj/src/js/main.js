//import Waypoint from 'waypoint';
//Scrollpoints = require('scrollpoints');
import scrollpoints from 'scrollpoints';

import VideoPlayer from '../vue/VideoPlayer.vue';

window.ppjNavTo = function(href, callback) {
  console.log('navTo');
  if (typeof callback !== 'undefined') {
    callback()
  }
  window.location = href;
};

window.ppj.openNavMenu = function() {
  document.getElementsByTagName('body')[0].style.overflow = 'hidden';
  document.getElementsByClassName('header')[0].classList.add('header--nav-menu-open');
};

window.ppj.closeNavMenu = function() {
  document.getElementsByTagName('body')[0].style.overflow = '';
  document.getElementsByClassName('header')[0].classList.remove('header--nav-menu-open');
};

window.ppj.toggleAccordion = function(buttonEl) {
  const className = 'accordion__list-element--open';
  const el = buttonEl.closest('.accordion__list-element');
  if (el.classList.contains(className)) {
    el.classList.remove(className);
  } else {
    el.classList.add(className);
    ga('send', 'event', 'Accordian', this.title, (this.open) ? 'open' : 'close', 7);
  }
};

window.addEventListener('load', function() {

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

