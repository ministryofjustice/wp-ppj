<?php

global $ppj_template_data;
$td = $ppj_template_data;
$textBlockBEMModifier = '';
$textBlockClassName = 'text-block-container';
$isIconBlocks = false;

// the default format of this new acf select field is different if it hasn't been saved
if (isset($td['width'])) {
    if (is_array($td['width'])) {
        $layout = 'l-' . $td['width'][0];
    } else {
        $layout = 'l-' . $td['width'];
    }
} else {
    $layout = 'l-full';
}

// compute the 'style' BEM modifier
if (isset($td['triple_text_blocks'])) {
    if (is_array( $td['triple_text_blocks'])) {
        $tripleTextBlockClass = "text-block__triple-text-block-container ";
        $textBlockBEMModifier = $textBlockClassName . '--triple';

        if (isset($td['triple_text_blocks'][0]['icon']) && $td['triple_text_blocks'][0]['icon']['url']) {
            $isIconBlocks = true;
        } else {
            $tripleTextBlockClass .= ($isIconBlocks) ? '' : 'text-block__triple-text-block-container--text-only';
        }
    }
} else {
    if (isset($td['style']) && !is_array($td['style'])) {
        $textBlockBEMModifier = $textBlockClassName . '--' . $td['style'];
    } else {
        $textBlockBEMModifier = '';
    }
}

if (isset($td['link']['title'])) {
    $textBlockBEMModifier .= ' ' . $textBlockClassName . '--has-cta-link';
}

?>
<div class="<?= $layout ?>"
     id="<?= urlencode(strtolower($td['title'])) ?>"
>
    <div class="<?= $textBlockClassName ?> <?= $textBlockBEMModifier ?>">
        <div class="text-block">
            <?php if ($td['title']) : ?>
                <h2 class="text-block__title">
                    <?= $td['title'] ?>
                </h2>
            <?php endif; ?>

            <?php if ($td['subtitle']) : ?>
                <h3 class="text-block__subtitle">
                    <?= $td['subtitle'] ?>
                </h3>
            <?php endif; ?>

            <?php if ($td['content']) : ?>
                <div class="text-block__content">
                    <?= $td['content'] ?>
                </div>
            <?php endif; ?>

            <?php if ($td['type'] == 'triple' && isset($td['triple_text_blocks'])): ?>

                <div class="<?= $tripleTextBlockClass ?>">
                    <?php foreach ($td['triple_text_blocks'] as $block) : ?>
                        <div class="text-block__triple-text-block">
                            <?php if ($isIconBlocks) : ?>
                                <img class="text-block__triple-text-block-icon" src="<?= $block['icon']['url'] ?>">

                                </img>
                            <?php endif; ?>
                            <div class="triple-text-block__text-container">
                                <?php if ($block['icon_text']) : ?>
                                    <div class="text-block__triple-text-block-icon-text">
                                        <?= $block['icon_text'] ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($block['content']) : ?>
                                    <div class="text-block__triple-text-block-content">
                                        <?= $block['content'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

            <?php if ($td['type'] == 'quote') : ?>
                <?php if ($td['quote']) : ?>
                    <div class="text-block__quote">
                        <div class="text-block__quote-mark"></div>

                        <div class="text-block__quote-content">
                            <?= $td['quote'] ?>
                        </div>

                        <?php if ($td['quote_source']) : ?>
                            <div class="text-block__quote-source">
                                <?= $td['quote_source'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>

        <?php if (isset($td['link']) && isset($td['link']['url']) && isset($td['link']['title'])) : ?>
            <div class="text-block__link-container">
                <a href="<?= $td['link']['url'] ?>" class="text-block__cta-link"><?= $td['link']['title'] ?></a>
            </div>
        <?php endif; ?>

    </div>
</div>
