<?php
namespace ppj\LegNav;

/**
 * @param $name candidate name
 *
 * @return bool if $name is a valid leg name
 */
function isLeg($name) {
    return in_array($name, [
        'prison-officer',
        'youth-custody'
    ]);
}

/**
 * return the relative URL path,
 * minus any parameters
 * and in array form
 */
function getCleanRelativePathParts()
{
    $noParametersPath = explode('?', $_SERVER['REQUEST_URI'])[0];

    return array_values(array_filter(explode('/', $noParametersPath)));
}

/**
 * The site is being divided into legs.
 * One leg for each job type.
 *
 * This function returns the top level segment of the relative path
 * to derive the name of the leg.
 */
function getLegNameFromPath()
{
    if ($pathArray = getCleanRelativePathParts()) {
        if (isLeg($pathArray[0])) {
            return $pathArray[0];
        }
    }

    return 'landing-page';
}

/**
 * Simple function to determine if the currently viewed page
 * is a leg specific page
 *
 * @return bool
 */
function onLeg() {
    return (getLegNameFromPath() !== 'landing-page');
}

/**
 * Determines whether the current path is for a leg home page
 *
 * eg. if the current relative path is /prison-officer/
 * this function will return true
 *
 * @return bool
 */
function onLegHome() {
    $pathArray = getCleanRelativePathParts();

    return ((sizeof($pathArray) == 1) && isLeg($pathArray[0]));
}

/**
 * @return string the relative path for the current leg home page
 */
function getLegHomeRelativePath() {

    switch(getLegNameFromPath()) {
        case 'landing-page':
            return '/';

        case 'prison-officer':
            return '/prison-officer/';

        case 'youth-custody':
            return '/youth-custody/';
    }

}
