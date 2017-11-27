<?php

    global $ppj_model;
    $m = $ppj_model;
    $componentClass = 'text-and-image--';

    if (isset($m['style']) && $m['style'] !== 'default') {
        $classModifier = $componentClass . str_replace(' ', '-', $m['style']);
    } else {
        $classModifier = '';
    }

?>

<div class="l-full">
    <div class="text-and-image <?= $classModifier ?>">
        <div class="text-and-image__wrapper">
            <div class="text-and-image__image-container">
                <img class="text-and-image__image"
                     src="<?= $m['image']['url']; ?>"
                />

                <?php if (isset($m['map'])): ?>
                    <div class="text-and-image__map">
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-and-image__text-container">

                <?php if ($m['title']): ?>
                    <h2 class="text-and-image__title">
                        <?= $m['title'] ?>
                    </h2>
                <?php endif; ?>

                <?php if ($m['subtitle']): ?>
                    <h3 class="text-block__subtitle">
                        <?= $m['subtitle']?>
                    </h3>
                <?php endif; ?>

                <?php if ($m['quote']): ?>
                    <h3 class="text-and-image__quote-container">
                        <div class="text-and-image__quote-mark"></div>
                        <div class="text-and-image__quote-text">
                            <?= $m['quote']?>
                        </div>
                    </h3>

                    <h3 class="text-and-image__quote-source">
                        <?= $m['quote_source']?>
                    </h3>
                <?php endif; ?>

                <div class="text-and-image__text">
                    <?= $m['content']?>
                </div>

                <div class="text-and-image__content">

                </div>
            </div>
        </div>
    </div>
</div>