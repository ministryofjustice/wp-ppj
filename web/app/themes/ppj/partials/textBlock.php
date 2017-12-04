<?php

global $ppj_template_data;
$td = $ppj_template_data;

?>

<div class="l-full">
    <div class="text-block">
        <?php if ($td['type'] == 'regular'): ?>

            <?php if ($td['title']): ?>
                <h2 class="text-block__title">
                    <?= $td['title'] ?>
                </h2>
            <?php endif; ?>

            <?php if ($td['subtitle']): ?>
                <h3 class="text-block__subtitle">
                    <?= $td['subtitle']?>
                </h3>
            <?php endif; ?>

            <?php if ($td['content']): ?>
                <div class="text-block__content">
                    <?= $td['content']?>
                </div>
            <?php endif; ?>

        <?php endif; ?>

        <?php if ($td['type'] == 'quote'): ?>

            <?php if ($td['quote']): ?>

                <div class="text-block__quote">
                    <div class="text-block__quote-mark"></div>

                    <div class="text-block__quote-content">
                        <?= $td['quote']?>
                    </div>

                    <?php if ($td['quote_source']): ?>
                        <div class="text-block__quote-source">
                            <?= $td['quote_source']?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>
        
    </div>
</div>