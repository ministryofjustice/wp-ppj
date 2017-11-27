<?php

global $ppj_model;
$m = $ppj_model;

?>

<div class="l-full">
    <div class="text-block">
        <h2 class="text-block__title">
            <?= $m['title'] ?>
        </h2>
        <h3 class="text-block__subtitle">
            <?= $m['subtitle']?>
        </h3>
        <div class="text-block__content">
            <?= $m['content']?>
        </div>
    </div>
</div>