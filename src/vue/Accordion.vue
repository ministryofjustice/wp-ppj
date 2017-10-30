<template>
    <div class="accordion">
        <h2 class="accordion__title">{{title}}</h2>
        <h3 class="accordion__subtitle">{{subtitle}}</h3>
        <ol class="accordion__list">
            <slot></slot>
        </ol>
    </div>
</template>
<script>
    import Vue from 'vue';
    const Element = {
        template:
            `<li class="accordion__list-element" :class="{ 'accordion__list-element--open': open }">
                <h4 class="accordion__list-element-title">{{title}}</h4>
                <button class="accordion__list-element-button" @click="toggleOpen"></button>
                <div class="accordion__list-element-content"><slot></slot></div>
            </li>`,
        props: ['title'],
        data() {
          return {
              open: false
          }
        },
        methods: {
            toggleOpen(el) {
                this.open = !this.open;
            }
        }
    };
    export default {
        props: ['title', 'subtitle'],
        childComponents: {
            Element
        }
    }
</script>