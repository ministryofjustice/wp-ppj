<?php

global $ppj_template_data;
$td = $ppj_template_data;

/*
 * vue does not allow scripts to be embedded within a vue component - such as page-container
 * may need to enqueue scripts before page renders by searching which wsi
 */
?>
<!--<script src="//fast.wistia.com/embed/medias/--><?//= $td['id'] ?><!--.jsonp" async></script>-->

<!--<script src="//fast.wistia.com/assets/external/E-v1.js" async></script>-->
<!--<div class="wistia_embed wistia_async_--><?//= $td['id'] ?><!--" style="height:349px;width:620px">&nbsp;</div>-->

<iframe src="//fast.wistia.net/embed/iframe/<?= $td['id'] ?>"
        allowtransparency="true"
        frameborder="0"
        scrolling="no"
        class="wistia_embed"
        name="wistia_embed"
        allowfullscreen
        mozallowfullscreen
        webkitallowfullscreen
        oallowfullscreen
        msallowfullscreen
></iframe>
