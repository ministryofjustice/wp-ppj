<template>
    <div class="role-intro">
        <h2 class="role-intro__main-title">{{mainTitle}}</h2>
        <h2 class="role-intro__subtitle">{{subtitle}}</h2>
        <div class="role-intro__main-section">
            <div class="role-intro__position-block">
                <div
                        class="role-intro__position"
                        v-if="position"
                >{{position}}</div>
                <div
                        class="role-intro__salary-range"
                        v-if="salaryRange"
                >{{salaryRange}}</div>
                <div
                        class="role-intro__location"
                        v-if="location"
                >{{location}}</div>
            </div>
            <img
                    class="role-intro__image"
                    :src="imgUrl" />
        </div>
        <div class="role-intro__content">
            <slot></slot>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['main-title', 'subtitle', 'position', 'salary-range', 'location', 'img-url']
    }
</script>
