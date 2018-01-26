<?php

global $ppj_template_data;
$td = $ppj_template_data;
$className = 'quote';

$originBEMModifier = $className . '--' . $td['origin'];
$styleBEMModifier = $className . '--' . $td['style'];
$noBorderBEMModifier = $className . '--' . $td['no-border'];
$positionBEMModifier = $className . '--' . $td['position'];

$BEMModifierClasses =
    $originBEMModifier
    . ' ' . $positionBEMModifier
    . ' ' . $styleBEMModifier
    . ' ' . $noBorderBEMModifier;
?>
<?php if ($td['quote']) : ?>

  <div class="<?= $className ?> <?= $BEMModifierClasses ?>">

    <div class="quote__mark">
        <?php ppj\inlineSVG('quote_icon'); ?>
    </div>

    <div class="quote__content">
        <?= $td['quote'] ?>
    </div>

    <?php if ($td['quote-source']) : ?>
        <div class="quote__source">
            <?= $td['quote-source'] ?>
        </div>
    <?php endif; ?>

  </div>
<?php endif; ?>
