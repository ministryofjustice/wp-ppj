<?php

global $ppj_template_data;
$td = $ppj_template_data;

?>

<div class="l-full">
    <div class="ordered-list" v-cloak>
        <h2 class="ordered-list__title"><?= $td['title'] ?></h2>
        <h3 class="ordered-list__subtitle"><?= $td['subtitle'] ?></h3>
        <?php if (isset($td['records']) && is_array($td['records'])): ?>
            <ol class="ordered-list__list">
                <?php foreach ($td['records'] as $el): ?>
                    <li class="ordered-list__list-element">
                        <div class="ordered-list__list-element-main-text"><?= $el['title'] ?></div>
                        <div class="ordered-list__list-element-subtext"><?= $el['subtext'] ?></div>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif ?>
    </div>
</div>
