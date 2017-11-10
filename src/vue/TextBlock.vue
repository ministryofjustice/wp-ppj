<template>
    <div class="text-block">
        <h2
                class="text-block__title"
                v-if="mainTitle"
        >{{ mainTitle }}</h2>
        <h3
                class="text-block__subtitle"
                v-if="subtitle"
        >{{subtitle}}</h3>
        <div class="text-block__content">
            <slot></slot>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['main-title', 'subtitle']
    }
</script>
