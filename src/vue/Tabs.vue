<template>
    <div class="tabs">
        <ul class="tabs__nav">
            <li class="tabs__nav-link"
                v-for="(tabTitle, index) in tabTitles"
                :class="{'tabs__nav-link--active': (activeTab == index)}"
            >{{tabTitle}}</li>
        </ul>
        <div class="tabs__content-panel">
            <slot></slot>
        </div>
    </div>
</template>
<script>
    import Vue from 'vue';
    const bus = new Vue();

    const tab = {
        template:
            `<div
                 class="tabs__tab"
                 :class="{'tabs__tab--active': active}"
                 >
                 <slot></slot>
            </div>`,
        props: ['tab-title'],
        data() {
            return {
                active: false
            }
        },
        mounted() {
            bus.$emit('tab-mounted', this.tabTitle);
        }
    };

    export default {
        data() {
            return {
                activeTab: 0,
                tabTitles: [],
                loaded: true
            }
        },
        childComponents: {
            Tab: tab
        },
        methods: {
        },
        beforeMount() {
            const tabTitles = this.tabTitles;
            bus.$on('tab-mounted', function(val) {
                tabTitles.push(val);
            })
        }

    }
</script>