<template>
    <div class="ordered-list">
        <h2 class="ordered-list__title">{{title}}</h2>
        <h3 class="ordered-list__subtitle">{{subtitle}}</h3>
        <ol class="ordered-list__list">
            <slot></slot>
        </ol>
    </div>
</template>
<script>
    import Vue from 'vue';
    const Element = {
        template:
            `<li class="ordered-list__list-element">
                <div class="ordered-list__list-element-main-text">{{mainText}}</div>
                <div class="ordered-list__list-element-subtext">{{subtext}}</div>
            </li>`,
        props: ['main-text', 'subtext']
    };
    export default {
        props: ['title', 'subtitle'],
        childComponents: {
            Element
        }
    }
</script>