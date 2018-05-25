<?php
global $post;

$modifiedSiteWideNavItems = ppj\markCurrentlySelectedAncestorMenuItem(wp_get_nav_menu_items('site-wide-nav'));
$logoTargetUrl = ppj\getLegHomeRelativePath();

?>

<div class="header__site-header">
    <div class="site-wide-nav">
        <ul class="site-wide-nav__menu-list">
            <?php foreach ($modifiedSiteWideNavItems as $item):
                $listElementClass = 'site-wide-nav__menu-list-element';
                $listElementClass .= ($item['selected']) ? ' site-wide-nav__menu-list-element--selected' : '';
                ?>
                <li class="<?= $listElementClass ?>">
                    <a href="<?= $item['url'] ?>"
                       class="site-wide-nav__menu-list-element-link">
                        <span class="site-wide-nav__menu-list-element-text"><?= $item['title'] ?></span>
                    </a>
                    <span class="site-wide-nav__open-mobile-nav" onclick="ppj.openNavMenu()">menu</span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="leg-specific-nav">
        <a href="<?= $logoTargetUrl ?>" class="leg-specific-nav__logo"></a>

        <?php if (ppj\onLeg()):
            $legNavMenuItems = wp_get_nav_menu_items( ppj\getLegNameFromPath() );
            $modifiedLegNavMenuItems = ppj\markCurrentlySelectedMenuItem($legNavMenuItems);
        ?>

        <div class="leg-specific-nav__menu">
            <ul class="leg-specific-nav__menu-list">
                <?php foreach ($modifiedLegNavMenuItems as $item): ?>
                    <li class="leg-specific-nav__menu-list-element
                               <?= ($item['selected']) ?
                                     'leg-specific-nav__menu-list-element--selected' :
                                     ''
                               ?>">
                        <a href="<?= $item['url'] ?>">
                            <span class="leg-specific-nav__menu-list-element-text"><?= $item['title'] ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</div>

