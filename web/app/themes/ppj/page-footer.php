<?php

$footerContent   = get_field('footer_content', 'option');
$footerCopyright = get_field('footer_copyright', 'option');

$facebookFooterContent = false;

switch (ppj\getLegNameFromPath()) {
    case 'prison-officer':
        $facebookFooterContent = get_field('facebook_content_prison_officer', 'option');
        break;

    case 'youth-custody':
        $facebookFooterContent = get_field('facebook_content_youth_custody', 'option');
        break;

    default:
        break;
}

// is_page_template('page.php') doesn't work as expected if the page uses the 'default' template
$isPage = (basename(get_page_template()) === 'page.php');

?>

        <div class="l-full l-full--footer">
            <?php if ( $isPage && $facebookFooterContent): ?>
                <div class="facebook-footer">
                    <div class="facebook-footer__container">
                        <div class="facebook-footer__icon">
                            <?php ppj\inlineSVG('facebook'); ?>
                        </div>
                        <div class="facebook-footer__content">
                            <?= $facebookFooterContent ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($footerContent || $footerCopyright): ?>
                <div class="footer">
                    <div class="footer__container">
                        <?= $footerContent; ?>
                        <div class="footer__logo"></div>
                        <div class="footer__copyright">
                            <span><?= $footerCopyright; ?></span>
                        </div>

                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div> <?php // page-container ?>
    <?= ppj\partial(null,'mobileNavigation') ?>
</div><?php // site-container ?>

<?php get_footer();
