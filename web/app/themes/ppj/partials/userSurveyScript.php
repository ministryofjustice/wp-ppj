<?php

// Only show the survey if it is active
$activateSurvey = get_field('activate_survey', 'option');
if (!$activateSurvey) {
    return;
}

// Only show the survey on the specified pages
$activePages = get_field('active_pages', 'option');
$isActivePage = empty($activePages) || in_array(get_the_ID(), $activePages);
if (!$isActivePage) {
    return;
}

$displayPercentage = get_field('display_percentage', 'option');
$popupDelayMilliseconds = 1000 * get_field('popup_delay', 'option');
$surveyURL = get_field('survey_url', 'option');

?>
<div class="screen-overlay screen-overlay--user-feedback-survey">
    <div class="screen-overlay__content">
        <div class="screen-overlay__close-button">Close</div>
        <div class="screen-overlay__iframe-container"></div>
    </div>
</div>
<script>
    var userSurvey = {};

    userSurvey.cookieName = 'ppjUserSurvey';

    userSurvey.createCookie = function(name, value, days) {
        console.log('creating cookie');
        var expires;

        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
            expires = "; expires=" + date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
    };

    userSurvey.readCookie = function(name) {
        var nameEQ = encodeURIComponent(name) + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ')
                c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0)
                return decodeURIComponent(c.substring(nameEQ.length, c.length));
        }
        return null;
    };

    userSurvey.eraseCookie = function(name) {
        userSurvey.createCookie(name, "", -1);
    };

    userSurvey.activateSurvey = function() {
        var body = document.querySelector('body');
        var overlay = document.querySelector('.screen-overlay--user-feedback-survey');
        var iframe = document.createElement('iframe');

        body.classList.add('site-overlay-is-active');
        iframe.src = '<?= $surveyURL ?>';
        overlay.querySelector('.screen-overlay__iframe-container').appendChild(iframe);
        overlay.classList.add('screen-overlay--active');

        // Add callback to close overlay when user clicks the overlay
        // This also includes the close button by default
        overlay.addEventListener('click', function() {
            overlay.classList.remove('screen-overlay--active');
            body.classList.remove('site-overlay-is-active');
        });
    };

    /*
     * The survey should only be shown for a specified percentage of users.
     * This calculation is being done in Javascript so that
     * HTML caching does not enforce the same calculation result
     * for all users until the cache expires
     *
     * The survey should not be shown to the same user (browser) again
     * for 30 days. A cookie is used to facilitate this.
     */
    var userSurveyCookieSet = !!userSurvey.readCookie(userSurvey.cookieName);

    console.log('userSurveyCookieSet', userSurveyCookieSet);

    if (!userSurveyCookieSet) {
        var randNumber = Math.floor(Math.random() * 100) + 1;
        if (<?= $displayPercentage ?> >= randNumber) {

            // Create a cookie to specify the earliest time the survey can be shown again
            userSurvey.createCookie(userSurvey.cookieName, 'do not show survey', 30);

            // Load the Google Forms survey in an iframe on page load ...
            window.addEventListener('load', function() {

                // ... after the specified delay
                setTimeout(userSurvey.activateSurvey, <?= $popupDelayMilliseconds ?>)
            });
        }
    }

</script>
