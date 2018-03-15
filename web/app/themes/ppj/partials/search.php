<?php

global $ppj_template_data;
$td = $ppj_template_data;

$title = (isset($td['title'])) ? $td['title'] : '';

?>

<div class="l-full">
    <search title="<?= $title ?>"
            job-feed-url="<?= $td['job_feed_url'] ?>"
    ></search>
</div>
