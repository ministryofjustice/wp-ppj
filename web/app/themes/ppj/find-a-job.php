<?php
/**
 * Template Name: Find a job
 */

include 'page-header.php';
?>

<div class="page-blocks">
    <div class="l-full find-a-job-container">
        <find-a-job
                job-feed-url="<?= get_field('job_feed_url')?>"
        ></find-a-job>
    </div>
</div>

<?php

include 'page-footer.php'

?>
