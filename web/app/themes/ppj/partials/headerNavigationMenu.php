<?php
$navMenuItems = wp_get_nav_menu_items( ppj\getLegNameFromPath() );

$logoTargetUrl = ppj\getLogoTargetURL();

$filteredNavMenuItems = [];
if (isset($navMenuItems) && !!$navMenuItems ) {
    foreach ( $navMenuItems as $item ) {
        $same = ( isset( $post ) && $post->ID == $item->object_id );

        $filteredNavMenuItems[] = [
            'title'    => $item->title,
            'url'      => $item->url,
            'selected' => $same
        ];
    }
}
?>

<div class="l-site-header">
    <div class="header__site-header">
        <div class="header__site-header-content">
            <a href="<?= $logoTargetUrl ?>" class="header__logo"></a>
            <div class="header__nav-link js" onclick="ppj.openNavMenu()">menu</div>

            <div class="header__nav-menu">
                <button class="header__nav-menu-close-button js" onclick="ppj.closeNavMenu()"></button>
                <ul class="header__nav-menu-list">
                    <?php foreach ($filteredNavMenuItems as $item): ?>
                        <li class="header__nav-menu-list-element <?= ($item['selected']) ? 'header__nav-menu-list-element--selected' : ''?>">
                            <a href="<?= $item['url'] ?>">
                                <span class="header__nav-menu-list-element-text"><?= $item['title'] ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
