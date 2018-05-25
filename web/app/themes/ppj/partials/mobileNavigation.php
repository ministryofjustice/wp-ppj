<?php
use ppj\LegNav;
?>

<div class="mobile-nav">
    <div class="mobile-nav__overlay"></div>
    <?php
    $legNavMenuItems = wp_get_nav_menu_items( LegNav\legName() );
    $modifiedLegNavMenuItems = ppj\markCurrentlySelectedMenuItem($legNavMenuItems);
    $currentLegItem = false;
    $isOnLeg = LegNav\onLeg();

    if ($isOnLeg) {
        $siteWideNavMenuItems = wp_get_nav_menu_items('site-wide-nav');
        $modifiedSiteWideNavItems = ppj\markCurrentlySelectedAncestorMenuItem($siteWideNavMenuItems);

        foreach ($modifiedSiteWideNavItems as $item) {
            if ($item['selected']) {
                $currentLegItem = $item;
            }
        }
    }
    ?>
    <button class="mobile-nav__close-button"
            onclick="ppj.closeNavMenu()">
    </button>
    <div class="mobile-nav__leg-nav">
        <div class="mobile-nav__menu">
            <ul class="mobile-nav__menu-list">
                <?php
                if ($currentLegItem): ?>
                    <li class="<?= 'mobile-nav__current-leg-element' ?>">
                        <a href="<?= $currentLegItem['url'] ?>"
                           class="mobile-nav__menu-list-element-link">
                            <span class="mobile-nav__current-leg-element-text"><?= $currentLegItem['title'] ?></span>
                        </a>
                    </li>
                <?php endif;

                foreach ($modifiedLegNavMenuItems as $item):
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
            <?php if ($isOnLeg): ?>
                <ul class="mobile-nav__menu-list mobile-nav__menu-list--site-wide">
                    <?php foreach ($modifiedSiteWideNavItems as $item):
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
        </div>
    </div>
</div>
