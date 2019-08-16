<?php
use ppj\LegNav;
global $post;

$modifiedSiteWideNavItems = ppj\markCurrentlySelectedAncestorMenuItem(ppj\navMenuItems('site-wide'));
$logoTargetUrl = LegNav\legHomeUrl();
$templateDirectory = get_template_directory_uri();
$logoSrc = $templateDirectory . "/dist/img/svg/logo.svg";
$logoAltText = "HM Prison and Probation Service - Prison and Probation Jobs home";

// The logo for the landing page and the Prison Officer leg is the same,
// however a specific logo is required for the Youth Custody leg
if (ppj\LegNav\legName() == 'youth-custody') {
    $logoSrc =  $templateDirectory . "/dist/img/svg/ycs_logo.svg";
    $logoAltText = "Youth Custody Service - Youth Justice Worker home";
}

?>

<header class="header__site-header">
    <nav class="site-wide-nav">
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
                </li>
            <?php endforeach; ?>
        </ul>
        <button class="site-wide-nav__open-mobile-nav" onclick="ppj.openNavMenu()">menu</button>
    </nav>

    <nav class="leg-specific-nav">

        <a href="<?= $logoTargetUrl ?>" class="leg-specific-nav__logo"><img src="<?= $logoSrc ?>" alt="<?= $logoAltText ?>"/></a>

        <?php if (LegNav\onLeg()):
            $legNavMenuItems = ppj\navMenuItems( LegNav\legName() );
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
    </nav>
</header>

