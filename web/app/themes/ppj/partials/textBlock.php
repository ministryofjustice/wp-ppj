<?php

global $ppj_template_data;
$td = $ppj_template_data;

?>

<div class="l-full">
    <div class="text-block">
        <h2 class="text-block__title">
            <?= $td['title'] ?>
        </h2>
        <h3 class="text-block__subtitle">
            <?= $td['subtitle']?>
        </h3>
        <div class="text-block__content">
            <?= $td['content']?>
        </div>
    </div>
</div>