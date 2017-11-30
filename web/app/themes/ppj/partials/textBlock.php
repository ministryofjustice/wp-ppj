<?php

global $ppj_template_data;
$td = $ppj_template_data;

?>

<div class="l-full">
    <div class="text-block">
        <?php if ($td['title']): ?>
        <h2 class="text-block__title">
            <?= $td['title'] ?>
        </h2>
        <?php endif; ?>

        <?php if ($td['subtitle']): ?>
        <h3 class="text-block__subtitle">
            <?= $td['subtitle']?>
        </h3>
        <?php endif; ?>

        <?php if ($td['content']): ?>
        <div class="text-block__content">
            <?= $td['content']?>
        </div>
        <?php endif; ?>
        
    </div>
</div>