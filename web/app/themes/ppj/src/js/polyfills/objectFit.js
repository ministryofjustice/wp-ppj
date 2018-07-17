/**
 * If object-fit is not supported (eg on IE 11), we cannot use <img> tags
 * as the image being used is not guaranteed to be of the same ratio as its container
 * which would lead to a distorted image being presented to the user.
 *
 * Instead a background image will be set on the <img> container
 * and the background image will have `background-size set` to cover,
 * and the existing <img> will be set to `display: none` in CSS.
 *
 * The URL of the background image to be used is stored in the
 * data attribute `data-bg-img-url` on the <img> container.
 */
export default function() {
  var img = document.createElement('img');
  if (typeof img.style.objectFit == 'undefined') {
    document.querySelector('body').classList.add('object-fit-not-supported');
    var imgElements = document.querySelectorAll('[data-bg-img-url]');
    for(var i = 0; i < imgElements.length; i++) {
      imgElements[i].style.backgroundImage = "url('" + imgElements[i].getAttribute('data-bg-img-url') + "')";
    }
  }
};
