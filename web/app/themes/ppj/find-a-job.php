<?php
/**
 * Template Name: Find a job
 */

include 'page-header.php';
?>

<div class="page-blocks">
    <div class="l-full search">
        <search
                job-feed-url="<?= get_field('job_feed_url')?>"
        ></search>
    </div>
</div>

<?php

include 'page-footer.php'

?>
