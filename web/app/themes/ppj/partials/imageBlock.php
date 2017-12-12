<?php

global $ppj_template_data;
$td = $ppj_template_data;

?>

<div class="l-<?= $td['width']; ?>">
    <div class="image-block">
        <div class="image-block__image-container">
            <img class="image-block__image"
                 src="<?= $td['image']['url']; ?>"
            />
        </div>
    </div>
</div>
