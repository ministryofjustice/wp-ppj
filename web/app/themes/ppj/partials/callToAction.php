<?php

global $ppj_template_data;
$td = $ppj_template_data;
//ppj::dump($td);
?>

<?php if (isset($td['records']) && is_array($td['records'])):
//error_log('got call to action links');
    ?>
    <div class="call-to-action">
        <ul class="call-to-action__list">
            <?php foreach ($td['records'] as $el) : ?>
                <li class="call-to-action__list-element">
                    <a href="<?= $el['page_link'] ?>"><?= $el['link_text'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif ?>
