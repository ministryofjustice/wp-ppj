<?php

global $ppj_model;
$m = $ppj_model;

?>

<div class="call-to-action">
    <ul class="call-to-action__list">
        <?php foreach($m as $el) : ?>
            <li class="call-to-action__list-element">
                <a href="<?= $el['page_link'] ?>"><?= $el['link_text'] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>