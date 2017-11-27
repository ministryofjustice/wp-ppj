<?php
    get_header();
    $page_blocks = get_field('page_blocks');
    ppj::dump($page_blocks);
?>

    <page-header carousel-images="
    /app/themes/PPJ/dest/img/30-B3mC72t-copy_tablet.jpg,
    /app/themes/PPJ/dest/img/header.jpg,
    /app/themes/PPJ/dest/img/30-B3mC72t-copy_tablet.jpg,
    /app/themes/PPJ/dest/img/header.jpg">
        You, at your best<br/>Be a prison officer
    </page-header>

   <?php echo ppj::controller($page_blocks); ?>

    <div class="l-full">
        <contact></contact>
    </div>

<?php get_footer();