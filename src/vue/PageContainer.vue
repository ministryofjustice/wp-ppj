<template>
    <div class="page-container js"
         :class="{'page-container--nav-menu-open' : menuOpen}">
        <slot></slot>
    </div>
</template>
<script>
    import Vue from 'vue';

    export default {
        data() {
            return {
                menuOpen: false
            }
        }
    }
</script>