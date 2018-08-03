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
    var ppjUserSurvey = {};

    ppjUserSurvey.activateSurvey = function() {
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

    ppjUserSurvey.cookieName = 'ppjUserSurvey';

    /*
     * The survey should only be shown for a specified percentage of users.
     * This calculation is being done in Javascript so that
     * HTML caching does not enforce the same calculation result
     * for all users until the cache expires
     *
     * The survey should not be shown to the same user (browser) again
     * for 30 days. A cookie is used to facilitate this.
     */
    ppjUserSurvey.cookieSet = !!Cookies.get(ppjUserSurvey.cookieName);

    console.log('ppjUserSurveyCookieSet', ppjUserSurvey.cookieSet);

    if (!ppjUserSurvey.cookieSet) {
        var randNumber = Math.floor(Math.random() * 100) + 1;
        if (<?= $displayPercentage ?> >= randNumber) {

            // Create a cookie to specify the earliest time the survey can be shown again
            Cookies.set(ppjUserSurvey.cookieName, 'do not show survey', {expires: 30});

            // Load the Google Forms survey in an iframe on page load ...
            window.addEventListener('load', function() {

                // ... after the specified delay
                setTimeout(ppjUserSurvey.activateSurvey, <?= $popupDelayMilliseconds ?>)
            });
        }
    }

</script>
