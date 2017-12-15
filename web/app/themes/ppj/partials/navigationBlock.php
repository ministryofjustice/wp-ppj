<?php

global $ppj_template_data;
$td = $ppj_template_data;

?>

<div class="l-full">
    <div class="nav-block">
        <div class="nav-block__title">
            NEXT
        </div>
        <?php if (isset($td['links']) && is_array($td['links'])) : ?>
            <ul>
                <?php foreach ($td['links'] as $link) : ?>
                    <li class="nav-block__link">
                        <a href="<?= $link['page'] ?>">
                            <?= $link['link_text'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
