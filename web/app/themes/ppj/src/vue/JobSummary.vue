<template>
    <div class="job-summary" v-cloak>
        <div
                class="job-summary__distance"
                v-if="distance"
        >
            <span
                    class="job-summary__distance-miles"
            >{{distance}}</span>
        </div>
        <div
                class="job-summary__position"
                v-if="position"
        >Prison Officer</div>
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
            >
                <a :href="prisonPageLink">{{prisonName}}</a>
            </div>
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
            , 'prison-page-link': {default: ''}
        }
    }
</script>