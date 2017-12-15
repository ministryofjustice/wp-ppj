<?php

global $ppj_template_data;
$td = $ppj_template_data;

?>

<tabs>

    <?php if (isset($td['records']) && is_array($td['records'])): ?>

        <?php for ($i = 0; $i < count($td['records']); $i++):
            $el = $td['records'][$i];
            ?>

            <tab tab-title="<?= $el['title'] ?>"
                 tab-id="<?= $i ?>"
            >
                <?= ppj\renderPageBlockData($el['page_blocks']) ?>
            </tab>

        <?php endfor; ?>

    <?php endif ?>

</tabs>

