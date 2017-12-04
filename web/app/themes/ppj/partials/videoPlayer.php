<?php

    global $ppj_template_data;
    $td = $ppj_template_data;

    //ppj\dump($td);
?>

    <div class="video-player video-player--<?= $td['host'] ?>">
        <?php if (isset($td['title'])): ?>
            <h3><?= $td['title'] ?></h3>
        <?php endif; ?>
        <div class="video-player__video-container">
            <!--<div class="video-player__play-button"></div>-->
            <?php
            switch($td['host']) {

                case 'youtube':
                    echo ppj\partial($td, 'videoPlayer', 'youtube');
                    break;

                case 'wistia':
                    echo ppj\partial($td, 'videoPlayer', 'wistia');
                    break;

                case 'raptMedia':
                    echo ppj\partial($td, 'videoPlayer', 'raptMedia');
                    break;

                default:
                    error_log('video type not recognized');
            }
            ?>
        </div>
    </div>
