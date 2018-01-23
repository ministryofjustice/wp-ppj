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

    <div class="quote-mark">
        <svg class="svg" width="20px" height="20px">
            <use xlink:href="<?= get_stylesheet_directory_uri() . '/dest/img/svg/quote_icon.svg' ?>"></use>
        </svg>
    </div>

    <div class="quote-content">
        <?= $td['quote'] ?>
    </div>

    <?php if ($td['quote-source']) : ?>
        <div class="quote-source">
            <?= $td['quote-source'] ?>
        </div>
    <?php endif; ?>

  </div>
<?php endif; ?>
