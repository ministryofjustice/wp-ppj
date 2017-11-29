<?php

    global $ppj_template_data;
    $td = $ppj_template_data;
    $componentClass = 'text-and-image--';

    if (isset($td['style']) && $td['style'] !== 'default') {
        $classModifier = $componentClass . $td['style']; //str_replace(' ', '-', $td['style']);
    } else {
        $classModifier = '';
    }

?>

<div class="l-full">
    <div class="text-and-image <?= $classModifier ?>">
        <div class="text-and-image__wrapper">
            <div class="text-and-image__image-container">
                <img class="text-and-image__image"
                     src="<?= $td['image']['url']; ?>"
                />

                <?php if (isset($td['map'])): ?>
                    <div class="text-and-image__map">
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-and-image__text-container">

                <?php if ($td['title']): ?>
                    <h2 class="text-and-image__title">
                        <?= $td['title'] ?>
                    </h2>
                <?php endif; ?>

                <?php if ($td['subtitle']): ?>
                    <h3 class="text-block__subtitle">
                        <?= $td['subtitle']?>
                    </h3>
                <?php endif; ?>

                <?php if ($td['quote']): ?>
                    <h3 class="text-and-image__quote-container">
                        <div class="text-and-image__quote-mark"></div>
                        <div class="text-and-image__quote-text">
                            <?= $td['quote']?>
                        </div>
                    </h3>

                    <h3 class="text-and-image__quote-source">
                        <?= $td['quote_source']?>
                    </h3>
                <?php endif; ?>

                <div class="text-and-image__text">
                    <?= $td['content']?>
                </div>

                <div class="text-and-image__content">

                </div>
            </div>
        </div>
    </div>
</div>