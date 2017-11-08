<template>
    <div class="job-summary">
        <div
                class="job-summary__distance"
                v-if="distance"
        >{{distance}} <span v-if="distanceTime">({{distanceTime}})</span></div>
        <div
                class="job-summary__position"
                v-if="position"
        >{{position}}</div>
        <div
                class="job-summary__salary"
                v-if="salary"
        >{{salary}}</div>
        <div
                class="job-summary__prison"
                v-if="prisonName || prisonCity"
        >
            <div
                    class="job-summary__prison-name"
                    v-if="prisonName"
            >{{prisonName}}</div>
            <div
                    class="job-summary__prison-city"
                    v-if="prisonCity"
            >{{prisonCity}}</div>
        </div>
        <a
                class="job-summary__link"
                v-if="url"
                :href="url"
        >view job</a>
    </div>
</template>
<script>
    export default {
        props: {
            'distance': {default: ''}
            , 'distance-time': {default: ''}
            , 'position': {default: ''}
            , 'salary': {default: ''}
            , 'prison-name': {default: ''}
            , 'prison-city': {default: ''}
            , 'url': {default: '/job-post.html'}
        }
    }
</script>