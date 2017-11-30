<?php

    global $ppj_template_data;
    $td = $ppj_template_data;

    //ppj\dump($td);
?>
<div class="video-player">
    <!--<div class="video-player__play-button"></div>-->
    <?php
    switch($td['host']) {

        case 'youtube':
            echo ppj\partial($td, 'videoPlayer', 'youtube');
            break;

        case 'wistia':
            echo ppj\partial($td, 'videoPlayer', 'wistia');
            break;

        default:
            error_log('video type not recognized');
    }
    ?>
</div>
