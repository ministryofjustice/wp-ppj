<?php
namespace ppj\LegNav;

/**
 * NotOnLegException is thrown when performing an action that requires
 * the current page to belong to a leg, but it doesn't.
 */
class NotOnLegException extends \Exception { }

/**
 * Determine if the specified page is a leg homepage
 *
 * @param int $pageId The page ID
 * @return bool
 */
function isLegHomepage($pageId) {
    return (bool) get_field('is_leg_homepage', $pageId);
}

/**
 * Determine if we're currently on a leg homepage
 *
 * @return bool
 */
function onLegHome() {
    return isLegHomepage(get_the_ID());
}

/**
 * Determine if we're currently on a leg page
 *
 * @return bool
 */
function onLeg() {
    if (onLegHome()) {
        return true;
    }

    $ancestors = get_post_ancestors(get_the_ID());
    foreach ($ancestors as $ancestor) {
        if (isLegHomepage($ancestor)) {
            return true;
        }
    }

    return false;
}

/**
 * Get the ID of the homepage for this leg.
 *
 * @return int
 * @throws NotOnLegException when used on a non-leg page
 */
function getLegHomepageId() {
    if (onLegHome()) {
        return get_the_ID();
    }

    $ancestors = get_post_ancestors(get_the_ID());
    foreach ($ancestors as $ancestor) {
        if (isLegHomepage($ancestor)) {
            return $ancestor;
        }
    }

    throw new NotOnLegException();
}

/**
 * Get the name of the current leg
 * Note: 'landing-page' will be returned if we're not on a leg
 *
 * @return string Name of the current leg, or 'landing-page'
 */
function legName() {
    try {
        $pageId = getLegHomepageId();
        return get_field('leg_name', $pageId);
    }
    catch (NotOnLegException $e) {
        return 'landing-page';
    }
}

/**
 * Get the homepage URL for the current leg
 * Note: the top-level landing page URL will be returned if we're not on a leg
 *
 * @return string Homepage URL
 */
function legHomeUrl() {
    try {
        $pageId = getLegHomepageId();
        return get_permalink($pageId);
    }
    catch (NotOnLegException $e) {
        return home_url('/');
    }
}
