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
  const body = document.getElementsByTagName('body')[0];
  body.classList.add('mobile-nav-is-open');
};

window.ppj.closeNavMenu = function() {
  const body = document.getElementsByTagName('body')[0];
  body.classList.remove('mobile-nav-is-open');
};

window.ppj.toggleAccordion = function(event) {
  event.preventDefault();
  event.stopPropagation();

  const listElement = event.target.closest('.accordion__list-element');
  const title = listElement.querySelector('.accordion__list-element-title')
    .textContent.replace(/\s+/g, ' ').trim(); // Squash multiple spaces & trim
  const className = 'accordion__list-element--open';

  var action;
  if (listElement.classList.contains(className)) {
    listElement.classList.remove(className);
    action = 'close';
  } else {
    listElement.classList.add(className);
    action = 'open';
  }

  ga('send', 'event', 'Accordian', title, action);
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

/**
 * function to close mobile nav menu if the screen
 * is resized such that the desktop nav is now visible.
 */
window.matchMedia('(min-width: 1024px)').addListener(function(data) {
  if (data.matches) {
    window.ppj.closeNavMenu();
  }
});

