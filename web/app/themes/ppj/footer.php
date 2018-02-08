<?php
$facebookContent = get_field('facebook_content', 'option');
$footerContent   = get_field('footer_content', 'option');
$footerCopyright = get_field('footer_copyright', 'option');

?>
<div class="l-full l-full--footer">
    <?php if ($facebookContent): ?>
        <div class="facebook-footer">
            <div class="facebook-footer__container">
                <div class="facebook-footer__icon">
                    <?php ppj\inlineSVG('facebook'); ?>
                </div>
                <div class="facebook-footer__content">
                    <?= $facebookContent ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($footerContent || $footerCopyright): ?>
        <div class="footer">
            <div class="footer__container">
                <?= $footerContent; ?>
                <div class="footer__copyright">
                    <span><?= $footerCopyright; ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

</page-container>
</div>
<?php wp_footer(); ?>
</body>
</html>
