<?php

global $ppj_template_data;
$td = $ppj_template_data;

?>
<div class="role-intro" v-cloak>
    <h2 class="role-intro__main-title"><?= $td['title'] ?></h2>
    <h2 class="role-intro__subtitle"><?= $td['subtitle'] ?></h2>
    <div class="role-intro__main-section">
        <div class="role-intro__position-block">
            <div
                class="role-intro__position"
            ><?= $td['position'] ?></div>
            <div
                class="role-intro__salary-range"
            ><?= $td['salary_range'] ?></div>
            <div
                class="role-intro__location"
            ><?= $td['location'] ?></div>
        </div>

        <img class="role-intro__image"
             src="<?= $td['image']['url']; ?>"
        />
    </div>
    <div class="role-intro__content">
        <?= $td['content'] ?>
    </div>
</div>