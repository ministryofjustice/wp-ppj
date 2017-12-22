<?php

get_header();
$page_blocks = get_field('page_blocks');

?>
  <div class="page-blocks">
    <?php echo ppj\renderPageBlockData($page_blocks); ?>
  </div>

<?php get_footer();
