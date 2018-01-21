<?php

global $ppj_template_data;
$td = $ppj_template_data;

$title = (isset($td['title'])) ? $td['title'] : '';

?>

<div class="l-full">
    <search default-search-term="<?= $td['default_search_term'] ?>"
            title="<?= $title ?>"
    ></search>
</div>
