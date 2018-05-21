//import Waypoint from 'waypoint';
//Scrollpoints = require('scrollpoints');
import 'nodelist-foreach-polyfill';
import 'details-element-polyfill';

import scrollpoints from 'scrollpoints';
import closestPolyfill from './polyfills/Element.closest';

closestPolyfill();

if ('dataLayer' in window == false) {
  window.dataLayer = [];
}

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

window.ppj.toggleAccordion = (event) => {
  const element = event.target;
  const gtmEvent = {
    event: 'accordion_toggle',
    title: element.getAttribute('data-title'),
    open: element.open
  };
  window.dataLayer.push(gtmEvent);
};

document.querySelectorAll('.accordion details').forEach((details) => {
  details.addEventListener('toggle', window.ppj.toggleAccordion);
});


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
 *
 * @type {MediaQueryList} to match nonMobile devices like phones and tablets
 */
const nonMobiles = window.matchMedia('(min-width: 1024px)');

/**
 * Close mobile nav if screen becomes >= 1024px wide
 * The desktop nav will become visible, so mobile nav must be closed
 */
nonMobiles.addListener(function(data) {
  if (data.matches) {
    window.ppj.closeNavMenu();
  }
});

/**
 * Set the ARIA hidden attribute value for matching elements
 *
 * @param cssSelector string valid CSS selector
 * @param hidden boolean
 */
window.ppj.setAriaHiddenAttribute = function(cssSelector, hidden) {
  const inactiveLinks = document.querySelectorAll(cssSelector);
  for(let link of inactiveLinks) {
    link.setAttribute('aria-hidden', hidden);
  }
};

/**
 *
 * On mobile devices only the selected nav link is visible.
 * This function ensures that the other links are
 * 'invisible' to screen readers also
 *
 * On non mobile devices, all the nav links should be 'visible'
 * to screen readers.
 *
 * @param mobileDevicesMediaQuery
 */
function setAriaHiddenForNonVisibleSiteWideNavLinks(mobileDevicesMediaQuery) {
  console.log('setAria');
  const cssSelector = '.site-wide-nav__menu-list-element:not(.site-wide-nav__menu-list-element--selected)';
  if (mobileDevicesMediaQuery.matches) {
    window.ppj.setAriaHiddenAttribute(cssSelector, false);
  } else {
    window.ppj.setAriaHiddenAttribute(cssSelector, true);
  }
}

// Set ARIA hidden attributes for site-wide-nav links on transition between mobile and non-mobile viewport sizes
nonMobiles.addListener(setAriaHiddenForNonVisibleSiteWideNavLinks);

// Set ARIA hidden attributes for site-wide-nav links on page load
setAriaHiddenForNonVisibleSiteWideNavLinks(nonMobiles);



