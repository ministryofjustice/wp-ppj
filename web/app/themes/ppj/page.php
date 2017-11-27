<?php
    get_header();
    $callToActionModel = get_field('call_to_action');
?>

    <page-header carousel-images="
    /app/themes/PPJ/dest/img/30-B3mC72t-copy_tablet.jpg,
    /app/themes/PPJ/dest/img/header.jpg,
    /app/themes/PPJ/dest/img/30-B3mC72t-copy_tablet.jpg,
    /app/themes/PPJ/dest/img/header.jpg">
        You, at your best<br/>Be a prison officer
    </page-header>

<!--    <div class="call-to-action">
        <ul class="call-to-action__list">
            <li class="call-to-action__list-element"><a href="role.html">About the role</a></li>
            <li class="call-to-action__list-element"><a href="apply.html">How to apply</a></li>
            <li class="call-to-action__list-element"><a href="">Search for jobs</a></li>
        </ul>
    </div>-->
    <?php echo ppj::partial($callToActionModel, 'callToAction'); ?>

    <div class="l-full">
        <text-block
            main-title="No two days are the same"
            subtitle="Follow a career where you do something different every day."
        >
        </text-block>
    </div>
    <div class="l-full">
        <text-image-block
            type="inlaid-text"
            main-title="Challenge yourself"
            img-url="/app/themes/PPJ/dest/img/12-h61JnTq-copy.jpg"
        >
            Working in a prison is unique, it’s not for everyone. You will have to handle different challenges everyday, from keeping order to helping with rehabilitation.
        </text-image-block>
    </div>
    <div class="l-full">
        <text-image-block
            main-title="Get real rewards"
            img-url="/app/themes/PPJ/dest/img/12-h61JnTq-copy.jpg"
        >
            Turning lives around is hard. You may work behind closed doors, but by creating a safe environment for prisoners you will help build better communities everywhere.
        </text-image-block>
    </div>
    <div class="l-full">
        <text-block
            main-title="Be part of a team"
        >
            Prison officers feel like family not just colleagues.
            They understand the role, support you and have your back when you need it most.
        </text-block>
    </div>

    <div class="l-half">
        <text-image-block
            quote="Working in a prison is no ordinary job, it is a very rewarding career"
            quote-source="Steve, Prison Officer HMP Norwich"
            img-url="/app/themes/PPJ/dest/img/12-h61JnTq-copy.jpg"
        >
        </text-image-block>
    </div>

    <div class="l-full">
        <text-block main-title="Do something extraordinary">
            <p>Escort a prisoner. Work with vulnerable offenders. Deal with an incident. Working in a prison can be unpredictable, challenging and rewarding too.</p>
            <div class="enormous">£28K</div>
            <p class="medium"><strong>starting salary</strong> for a prison officer on a 39 hour week</p>
            <div class="enormous">25 days</div>
            <p class="medium"><strong>annual holiday</strong></p>
            <div class="enormous">10 weeks</div>
            <p class="medium"><strong>paid training</strong></p>
            <a class="text-block__link">Learn about life as a prison officer </a>
        </text-block>
    </div>

    <div class="l-half">
        <text-image-block
            quote="Every day we are able to change lives for the better"
            text="Bev, Governor, HMP Norwich"
            img-url="/app/themes/PPJ/dest/img/12-h61JnTq-copy.jpg"
        >
        </text-image-block>
    </div>

    <div class="l-full">
        <text-block main-title="What do you need to apply?">
            <ul>
                <li>be reasonably fit, numerate and with good eyesight</li>
                <li>have a right to work in the UK</li>
                <li>be honest, strong-minded and enjoy helping others</li>
            </ul>
            <p>You don’t need any formal qualifications although working as a prison officer gives you lots of opportunities to make the most of those you do have.</p>
            <a class="text-block__link">How to apply to be prison officer</a>
        </text-block>
    </div>

    <div class="l-full">
        <search></search>
    </div>

    <div class="l-full">
        <text-block main-title="See more prison and probation jobs">
            <a class='text-block__link'>Probation officer</a>
            <a class='text-block__link'>Probation service officer</a>
        </text-block>
    </div>

    <div class="l-full">
        <contact></contact>
    </div>

<?php get_footer();