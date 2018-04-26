<?php
global $post;

$siteWideNavMenuItems = wp_get_nav_menu_items('site-wide-nav');
$modifiedSiteWideNavItems = ppj\markCurrentlySelectedAncestorMenuItem($siteWideNavMenuItems);
?>

<div class="l-site-header">
    <div class="header__site-header">
        <div class="header__site-wide-nav">
            <ul class="header__site-wide-nav-menu-list">
                <?php foreach ($modifiedSiteWideNavItems as $item):
                    $listElementClass = 'header__site-wide-nav-menu-list-element';
                    $listElementClass .= ($item['selected']) ? ' header__site-wide-nav-menu-list-element--selected' : '';
                    ?>
                    <li class="<?= $listElementClass ?>">
                        <a href="<?= $item['url'] ?>"
                           class="header__site-wide-nav-menu-list-element-link">
                            <span class="header__site-wide-nav-menu-list-element-text"><?= $item['title'] ?></span>
                        </a>
                        <span class="header__nav-link js" onclick="ppj.openNavMenu()">menu</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php if (ppj\onLeg()):
            $logoTargetUrl = ppj\getLegHomeRelativePath();
            $legNavMenuItems = wp_get_nav_menu_items( ppj\getLegNameFromPath() );
            $modifiedLegNavMenuItems = ppj\markCurrentlySelectedMenuItem($legNavMenuItems);
            ?>

            <div class="header__leg-nav">
                <a href="<?= $logoTargetUrl ?>" class="header__logo"></a>

                <div class="header__nav-leg-menu">
                    <button class="header__nav-menu-close-button js" onclick="ppj.closeNavMenu()"></button>
                    <ul class="header__nav-leg-menu-list">

                        <?php foreach ($modifiedLegNavMenuItems as $item): ?>
                            <li class="header__nav-leg-menu-list-element <?= ($item['selected']) ? 'header__nav-leg-menu-list-element--selected' : ''?>">
                                <a href="<?= $item['url'] ?>">
                                    <span class="header__nav-leg-menu-list-element-text"><?= $item['title'] ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
