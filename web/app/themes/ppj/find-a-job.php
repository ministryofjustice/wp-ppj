<?php
/**
 * Template Name: Find a job
 */

include 'page-header.php';

?>

<div class="find-a-job-container">
    <find-a-job
        job-title="<?= get_field('job_title') ?>"
        leg="<?= ppj\LegNav\legName()?>"
    ></find-a-job>
</div>

<?php include 'page-footer.php' ?>
