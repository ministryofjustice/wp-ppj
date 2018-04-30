<?php

global $ppj_template_data;

$td = $ppj_template_data;
$textBlockClassName = 'text-block-container';
$textBlockClasses = $textBlockClassName;
$multiTextBlockClassName = "text-block__multi-text-block-container";
$multiTextBlockClasses = $multiTextBlockClassName;
$multiTextBlocks = false;

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

$isValidRegularTextBlock = ($td['type'] == 'regular' && $td['content']);

$isDoubleTextBlock = ($td['type'] == 'double' && isset($td['double_text_blocks']) && is_array( $td['double_text_blocks']));
$isTripleTextBlock = ($td['type'] == 'triple' && isset($td['triple_text_blocks']) && is_array( $td['triple_text_blocks']));
$isValidMultiTextBlock = ($isDoubleTextBlock || $isTripleTextBlock);

$isValidAccordionBlock = ($td['type'] == 'accordion' && isset($td['accordion']) && is_array( $td['accordion']));

$isValidIconRowsBlock = ($td['type'] == 'icon rows' && isset($td['icon_rows']) && is_array( $td['icon_rows']));

// compute the 'style' BEM modifier
if ($isValidMultiTextBlock) {
    $textBlockClasses .= ' ' . $textBlockClassName . '--multi';

    if ($isTripleTextBlock) {
        $multiTextBlocks = $td['triple_text_blocks'];
        $multiTextBlockClasses .= ' ' . $multiTextBlockClassName .'--triple';

        if (isset($td['triple_text_blocks'][0]['icon'])) {
            $isIconBlocks = true;
        } else {
            $multiTextBlockClasses .= ($isIconBlocks) ? '' : ' ' .$multiTextBlockClassName .'--text-only';
        }

    } else {
        $multiTextBlocks = $td['double_text_blocks'];
        $multiTextBlockClasses .= ' ' . $multiTextBlockClassName .'--double';
    }
}

if (isset($td['style']) && !is_array($td['style'])) {
    $textBlockClasses .= ' ' . $textBlockClassName . '--' . $td['style'];
}

if (isset($td['link']['title'])) {
    $textBlockClasses .= ' ' . $textBlockClassName . '--has-cta-link';
}

?>
<div class="<?= $layout ?>"
     id="<?= urlencode(strtolower($td['title'])) ?>"
>
    <div class="<?= $textBlockClasses ?>">
        <div class="text-block">
            <?php if ($td['title']) : ?>
                <h2 class="text-block__title">
                    <?= $td['title'] ?>
                </h2>
            <?php endif; ?>

            <?php if ($td['lead_paragraph']) : ?>
                <p class="text-block__lead-paragraph">
                    <?= $td['lead_paragraph'] ?>
                </p>
            <?php endif; ?>

            <?php if ($isValidRegularTextBlock) { ?>

                <div class="text-block__content">
                    <?= $td['content'] ?>
                </div>

            <?php } elseif ($isValidMultiTextBlock) {?>

                <div class="<?= $multiTextBlockClasses ?>">
                    <?php foreach ($multiTextBlocks as $block) : ?>
                        <div class="text-block__multi-text-block">
                            <?php if (isset($block['icon']) && $block['icon'] != 'none' ) : ?>
                                <div class="text-block__multi-text-block-icon">
                                    <?php include(get_template_directory() . '/dest' . $block['icon']) ?>
                                </div>
                            <?php endif; ?>
                            <div class="text-block__multi-text-block-text-container">
                                <?php if (isset($block['icon_text']) && $block['icon_text']) : ?>
                                    <div class="text-block__multi-text-block-icon-text">
                                        <?= $block['icon_text'] ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($block['content']) && $block['content']) : ?>
                                    <div class="text-block__multi-text-block-content">
                                        <?php echo do_shortcode($block['content']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php } elseif ($isValidAccordionBlock) { ?>

                <div class="text-block__content">

                    <div class="accordion">
                        <ol class="accordion__list">
                            <?php foreach ($td['accordion'] as $el) : ?>
                                <li class="accordion__list-element">
                                    <div class="accordion__list-element-header" onclick="ppj.toggleAccordion(event)">
                                        <h4 class="accordion__list-element-title">
                                            <?= $el['title'] ?>
                                            <?php if (!empty($el['subtitle'])): ?>
                                                <span class="accordion__list-element-subtitle"><?= $el['subtitle'] ?></span>
                                            <?php endif; ?>
                                        </h4>
                                        <div class="accordion__list-element-button-container">
                                            <button class="accordion__list-element-button">
                                                <div class="accordion__list-element-button-bar accordion__list-element-button-bar--horizontal"></div>
                                                <div class="accordion__list-element-button-bar accordion__list-element-button-bar--vertical"></div>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="accordion__list-element-content"><?= $el['content'] ?></div>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </div>

                </div>

            <?php } elseif ($isValidIconRowsBlock) { ?>

                <div class="text-block__content">
                    <div class="text-block__icon-rows">
                        <?php foreach ($td['icon_rows'] as $row) : ?>

                            <div class="text-block__icon-rows-row">

                                <?php if (isset($row['icon']) && $row['icon'] != 'none' ) : ?>
                                    <div class="text-block__icon-rows-icon">
                                        <img src="<?= get_template_directory_uri() . '/dest' . $row['icon'] ?>" />
                                    </div>
                                <?php endif; ?>

                                <div class="text-block__icon-rows-content-container">

                                    <?php if (isset($row['title']) && $row['title'] != '' ) : ?>
                                        <div class="text-block__icon-rows-title">
                                            <?= $row['title'] ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($row['content']) && $row['content'] != '' ) : ?>
                                        <div class="text-block__icon-rows-content">
                                            <?= $row['content'] ?>
                                        </div>
                                    <?php endif; ?>

                                </div>

                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>

            <?php } ?>

        </div>

        <?php if (isset($td['link']) && isset($td['link']['url']) && isset($td['link']['title'])) : ?>
            <div class="text-block__cta-link-container">
                <a href="<?= $td['link']['url'] ?>" class="text-block__cta-link"><?= $td['link']['title'] ?></a>
            </div>
        <?php endif; ?>

    </div>
</div>
