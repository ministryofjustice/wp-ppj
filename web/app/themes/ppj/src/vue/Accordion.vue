<template>
    <div class="accordion"
         :class="{'accordion--numbered': numbered}"
         v-cloak
    >
        <h2
                class="accordion__title"
                v-if="title"
        >{{title}}</h2>
        <h3
                class="accordion__subtitle"
                v-if="subtitle"
        >{{subtitle}}</h3>
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

                    <div class="accordion__list-element-header" @click="toggleOpen">
                        <h4 class="accordion__list-element-title">{{title}} <span class="accordion__list-element-subtitle">{{subtitle}}</span></h4>
                        <div class="accordion__list-element-button-container">
                            <button class="accordion__list-element-button">
                                <div class="accordion__list-element-button-bar accordion__list-element-button-bar--horizontal"></div>
                                <div class="accordion__list-element-button-bar accordion__list-element-button-bar--vertical"></div>
                            </button>
                        </div>
                    </div>
                <div class="accordion__list-element-content"><slot></slot></div>
            </li>`,
        props: ['title', 'subtitle'],
        data() {
          return {
              open: false
          }
        },
        methods: {
            toggleOpen(el) {
              this.open = !this.open;
              ga('send', 'event', 'Accordian', this.title, (this.open) ? 'open' : 'close', 7);
            }
        }
    };
    export default {
        props: {
            title: String,
            subtitle: String,
            numbered: {type: String, default: ''}
        },
        childComponents: {
            Element
        }
    }
</script>
