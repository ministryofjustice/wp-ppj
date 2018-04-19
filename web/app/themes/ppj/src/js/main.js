//import Waypoint from 'waypoint';
//Scrollpoints = require('scrollpoints');
import scrollpoints from 'scrollpoints';
import closestPolyfill from './polyfills/Element.closest';

closestPolyfill();

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

window.ppj.toggleAccordion = function(event) {
  event.preventDefault();
  event.stopPropagation();
  const className = 'accordion__list-element--open';
  const el = event.target.closest('.accordion__list-element');
  if (el.classList.contains(className)) {
    el.classList.remove(className);
  } else {
    el.classList.add(className);
    ga('send', 'event', 'Accordian', this.title, (this.open) ? 'open' : 'close', 7);
  }
};

// _wq needs to be initialized so that Wistia's Video API can be used
window._wq = window._wq || [];

//add callbacks to video play button for all Wistia videos
_wq.push({ id: '_all', onReady: function(video) {
  const videoPlayer = video.container.closest('.video-player');

  videoPlayer.querySelector('.video-player__play-button')
    .addEventListener('click', function(){
      videoPlayer.classList.add('video-player--playing');
      video.play();
    });
}});

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
