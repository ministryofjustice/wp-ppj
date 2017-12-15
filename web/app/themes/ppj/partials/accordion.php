<?php

global $ppj_template_data;
$td = $ppj_template_data;

?>

<div class="l-full">
    <accordion numbered="<?= (!empty($td['numbered']) ? 'true' : '') ?>">

        <?php if (isset($td['records']) && is_array($td['records'])): ?>

            <?php foreach ($td['records'] as $el): ?>

                <accordion-element
                    title="<?= $el['title'] ?>"
                    subtitle="<?= $el['subtitle'] ?>"
                >
                    <?= $el['content'] ?>
                </accordion-element>

            <?php endforeach; ?>

        <?php endif ?>

    </accordion>

</div>
