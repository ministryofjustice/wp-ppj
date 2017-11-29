<template>
    <div class="tabs" v-cloak>
        <ul class="tabs__nav">
            <li class="tabs__nav-link"
                v-for="(tabTitle, index) in tabTitles"
                :class="{'tabs__nav-link--active': (activeTab == index)}"
                @click="displayTab(index)"
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
                 v-show="active"
                 >
                 <slot></slot>
            </div>`,
        props: [
            'tab-title',
            'tab-id'
        ],
        data() {
            return {
                active: (this.tabId == 0)
            }
        },
        mounted() {
            bus.$emit('tab-mounted', this.tabTitle);
        },
        watch: {
            '$parent.activeTab': function(index) {
                this.active = (index == this.tabId);
            }
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
            displayTab: function(index) {
                this.activeTab = index;
            }
        },
        beforeMount() {
            const tabTitles = this.tabTitles;
            bus.$on('tab-mounted', function(val) {
                tabTitles.push(val);
            })
        }

    }
</script>