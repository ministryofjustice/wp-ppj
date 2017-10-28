<template>
    <div class="linked-nodes">
        <!--<h2 class="linked-nodes__title">Application steps</h2>-->
        <LNTitle>Application steps</LNTitle>
        <h3 class="linked-nodes__subtitle">Find out everything you need to know about joining the prison and probation service</h3>
        <ol class="linked-nodes__list">
            <li class="linked-nodes__list-element">
                <div class="linked-nodes__list-element-main-text">Complete online application form</div>
                <div class="linked-nodes__list-element-subtext">10 minutes</div>
            </li>
            <li class="linked-nodes__list-element">
                <div class="linked-nodes__list-element-main-text">Judgement and numeracy tests</div>
                <div class="linked-nodes__list-element-subtext">24 minutes</div>
            </li>
            <li class="linked-nodes__list-element">
                <div class="linked-nodes__list-element-main-text">Notification of pass or failure</div>
                <div class="linked-nodes__list-element-subtext">1 day</div>
            </li>
            <li class="linked-nodes__list-element">
                <div class="linked-nodes__list-element-main-text">Book prison visit</div>
                <div class="linked-nodes__list-element-subtext">Up to 3 weeks</div>
            </li>
            <li class="linked-nodes__list-element">
                <div class="linked-nodes__list-element-main-text">Recruitment Assessment Day</div>
                <div class="linked-nodes__list-element-subtext">1 day</div>
            </li>
            <li class="linked-nodes__list-element">
                <div class="linked-nodes__list-element-main-text">Vetting process</div>
                <div class="linked-nodes__list-element-subtext">Up to 12 weeks</div>
            </li>
        </ol>
    </div>
</template>
<script>
    import Vue from 'vue';
//    const LNTitle = Vue.component('LNTitle',{
//      template: '<h2 class="linked-nodes__title">Application steps</h2>'
//    });
    const LNTitle = {
        template: '<h2 class="linked-nodes__title">Application steps</h2>'
    };
    export default {
        props: ['title'],
        components: {
            LNTitle
        }
    }
</script>