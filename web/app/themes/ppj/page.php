<?php

get_header();
$page_blocks = get_field('page_blocks');

?>

    <?php echo ppj\renderPageBlockData($page_blocks); ?>

<?php get_footer();