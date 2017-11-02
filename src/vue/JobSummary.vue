<template>
    <div class="job-summary">
        <div
                class="job-summary__distance"
                v-if="distance"
        >0.88 miles</div>
        <div
                class="job-summary__position"
                v-if="position"
        >Prison Officer</div>
        <div
                class="job-summary__salary"
                v-if="salary"
        >£31,560</div>
        <div
                class="job-summary__prison"
                v-if="prisonName || prisonCity"
        >
            <div
                    class="job-summary__prison-name"
                    v-if="prisonName"
            >HMP Belmarsh</div>
            <div
                    class="job-summary__prison-city"
                    v-if="prisonCity"
            >London</div>
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
            , 'position': {default: ''}
            , 'salary': {default: ''}
            , 'prison-name': {default: ''}
            , 'prison-city': {default: ''}
            , 'url': {default: '/job-post.html'}
        }
    }
</script>