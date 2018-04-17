<?php
/**
 * Template Name: Find a job
 */

include 'page-header.php';
//ppj\dump('job_list_message ' . get_field('job_list_message'));
?>

<div class="find-a-job-container">
    <find-a-job
            job-feed-url="<?= get_field('job_feed_url')?>"
            job-list-message="<?= get_field('job_list_message') ?>"
            job-list-message-url="<?= get_field('job_list_message_url') ?>"
    ></find-a-job>
</div>

<?php

include 'page-footer.php'

?>
