<?php
use ppj\LegNav;

// the mobile nav always needs to show the site wide nav
$modifiedSiteWideNavItems = ppj\markCurrentlySelectedAncestorMenuItem(ppj\navMenuItems('site-wide'));

// get a handle on the link that represents the current leg
$currentLegItem = false;

// if the user is currently on a leg
//   then the leg nav is in the primary position
//   and the site wide nav is in the secondary position
if (LegNav\onLeg()) {
    $primaryNavMenuItems = ppj\markCurrentlySelectedMenuItem(ppj\navMenuItems( LegNav\legName() ));
    $secondaryNavMenuItems = $modifiedSiteWideNavItems;

    // identify the currently selected leg link
    // so it can be inserted into the primary menu
    foreach ($modifiedSiteWideNavItems as $item) {
        if ($item['selected']) {
            $currentLegItem = $item;
        }
    }

// otherwise just show the site wide nav in the primary position
} else {
    $primaryNavMenuItems = $modifiedSiteWideNavItems;
}

?>

<div class="mobile-nav">
    <div class="mobile-nav__overlay"></div>
    <button class="mobile-nav__close-button"
            aria-label="Close mobile mobile navigation menu button"
            onclick="ppj.closeNavMenu()">
        <span class="mobile-nav__close-button-text">Close mobile menu</span>
        <img class="mobile-nav__close-button-image"
             src="/app/themes/ppj/dest/img/svg/close.svg"
             alt="Close mobile mobile navigation menu button image"
        />
    </button>
    <nav class="mobile-nav__menu">
        <ul class="mobile-nav__menu-list mobile-nav__menu-list--primary">
            <?php
            if ($currentLegItem): ?>
                <li class="<?= 'mobile-nav__current-leg-element' ?>">
                    <a href="<?= $currentLegItem['url'] ?>"
                       class="mobile-nav__menu-list-element-link">
                        <span class="mobile-nav__current-leg-element-text"><?= $currentLegItem['title'] ?></span>
                    </a>
                </li>
            <?php endif;

            foreach ($primaryNavMenuItems as $item):
                $elementClass = "mobile-nav__menu-list-element";
                $elementClass.= ($item['selected']) ? ' mobile-nav__menu-list-element--selected' : '';
                ?>
                <li class="<?= $elementClass ?>">
                    <a href="<?= $item['url'] ?>"
                       class="mobile-nav__menu-list-element-link"
                    >
                        <span class="mobile-nav__menu-list-element-text"><?= $item['title'] ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if (isset($secondaryNavMenuItems)): ?>
            <ul class="mobile-nav__menu-list mobile-nav__menu-list--secondary">
                <?php foreach ($secondaryNavMenuItems as $item):
                    $elementClass = "mobile-nav__menu-list-element";
                    if (!$item['selected']): ?>
                        <li class="<?= $elementClass ?>">
                            <a href="<?= $item['url'] ?>"
                               class="mobile-nav__menu-list-element-link">
                                <span class="mobile-nav__menu-list-element-text"><?= $item['title'] ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </nav>
</div>
