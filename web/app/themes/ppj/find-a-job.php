<?php
/**
 * Template Name: Find a job
 */

include 'page-header.php';
?>

<div class="l-full find-a-job-container">
    <find-a-job
            job-feed-url="<?= get_field('job_feed_url')?>"
    ></find-a-job>
</div>

<?php

include 'page-footer.php'

?>
