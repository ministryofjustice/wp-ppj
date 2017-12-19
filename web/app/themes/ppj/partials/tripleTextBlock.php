<?php

global $ppj_template_data;
$td = $ppj_template_data;

$isIconBlocks = ((isset($td['text_blocks'][0]['icon']) && $td['text_blocks'][0]['icon']['url']) ? true : false);

?>
<div class="l-full"
     id="<?= urlencode(strtolower($td['title'])) ?>"
>

    <div class="triple-text-block <?= ($isIconBlocks ? '' : 'triple-text-block--text-only') ?>">
        <?php if ($td['title']) : ?>
            <h2 class="triple-text-block__title">
                <?= $td['title'] ?>
            </h2>
        <?php endif; ?>

        <div class="triple-text-block__text-blocks-container">
            <?php foreach ($td['text_blocks'] as $block) : ?>
                <div class="triple-text-block__text-block">
                    <?php if ($isIconBlocks) : ?>
                        <img class="triple-text-block__icon" src="<?= $block['icon']['url'] ?>">

                        </img>
                    <?php endif; ?>
                    <div class="triple-text-block__text-container">
                        <?php if ($block['icon_text']) : ?>
                            <div class="triple-text-block__icon-text">
                                <?= $block['icon_text'] ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($block['content']) : ?>
                            <div class="triple-text-block__content">
                                <?= $block['content'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>
