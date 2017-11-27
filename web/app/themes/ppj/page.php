<?php

get_header();
$page_blocks = get_field('page_blocks');
//ppj::dump($page_blocks);

?>

   <?php echo ppj::controller($page_blocks); ?>

    <div class="l-full">
        <contact></contact>
    </div>

<?php get_footer();