<?php

include 'page-header.php';

$page_blocks = get_field('page_blocks');

?>

  <div class="page-blocks">
    <?php echo ppj\renderPageBlockData($page_blocks); ?>
  </div>

<?php

include 'page-footer.php'

?>

