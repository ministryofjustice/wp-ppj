<?php

$activateSurvey = get_field('activate_survey', 'option');
$popupDelay = get_field('popup_delay', 'option');
$surveyCode = get_field('survey_code', 'option');
$activePages = get_field('active_pages', 'option');
$isActivePage = empty($activePages) || in_array(get_the_ID(), $activePages);

if ($activateSurvey && $isActivePage && $popupDelay && $surveyCode): ?>
    <script>
        function ppjsm(t,e,s,o){
            var n,c,l;t.SMCX=t.SMCX||[],e.getElementById(o)||(n=e.getElementsByTagName(s),c=n[n.length-1],l=e.createElement(s),l.type="text/javascript",l.async=!0,l.id=o,l.src=["https:"===location.protocol?"https://":"http://","widget.surveymonkey.com/collect/website/js/<?= $surveyCode ?>.js"].join(""),c.parentNode.insertBefore(l,c));
        }
        setTimeout(ppjsm.bind(null, window,document,"script","smcx-sdk"), (<?= (int) $popupDelay ?> * 1000));
    </script>
<?php endif; ?>
