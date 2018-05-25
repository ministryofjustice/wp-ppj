<?php
namespace ppj\LegNav;
/**
 * PPJ Leg Navigation
 *
 * This website consists of multiple 'legs', which is a term used to describe a
 * collection of pages related to a particular job role.
 *
 * For example, the 'Prison Officer' leg of the site consists of multiple pages:
 *   - Home
 *   - Rewards and Benefits
 *   - Find A Job
 *   - ...and so on...
 *
 * Non-leg pages are referred to as belonging to a 'landing-page' pseudo-leg.
 * For example, legName() would return 'landing-page' on the following pages:
 *   - Landing page (i.e. website homepage at URL '/')
 *   - Terms & Conditions
 *   - Cookie Policy
 *   - ...and so on...
 *
 * The functions in this namespace support the 'leg' concept and help with
 * navigating throughout the site.
 *
 * The most useful functions to be aware of are:
 *   - onLegHome()
 *   - onLeg()
 *   - legName()
 *   - legHomeUrl()
 */

/**
 * NotOnLegException is thrown when performing an action that requires
 * the current page to belong to a leg, but it doesn't.
 */
class NotOnLegException extends \Exception { }

/**
 * Determine if the current page is a leg homepage
 *
 * @return bool
 */
function onLegHome() {
    return isLegHomepage(get_the_ID());
}

/**
 * Determine if the current page belongs to a leg
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
 * Determine if the specified page is a leg homepage
 *
 * @param int $pageId The page ID
 * @return bool
 */
function isLegHomepage($pageId) {
    return (bool) get_field('is_leg_homepage', $pageId);
}

/**
 * Get the page ID of the current leg's homepage
 *
 * @return int
 * @throws NotOnLegException when called on a non-leg page
 */
function legHomepageId() {
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
        $pageId = legHomepageId();
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
        $pageId = legHomepageId();
        return get_permalink($pageId);
    }
    catch (NotOnLegException $e) {
        return home_url('/');
    }
}
