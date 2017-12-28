<template>
    <div class="job-summary"
         :class="{'job-summary--selected': selected}"
    >
        <div class="job-summary__position"
             v-if="position"
        >Prison Officer</div>
        <div class="job-summary__distance"
             v-if="formattedDistance"
        >
          {{formattedDistance}}
        </div>
        <div class="job-summary__salary"
             v-if="salary"
        >{{salary}}</div>
        <div class="job-summary__prison"
             v-if="prisonName || prisonCity"
        >
            <div class="job-summary__prison-name"
                 v-if="prisonName"
            >
              {{prisonName}}
            </div>
          <div class="job-summary__prison-city"
          >London</div>
        </div>
        <a class="job-summary__link"
           v-if="url"
           :href="url"
        >view job</a>
    </div>
</template>
<script>
    export default {
        props: {
            'distance': {default: 0}
            , 'distance-time': {default: ''}
            , 'position': {default: ''}
            , 'salary': {default: ''}
            , 'prison-name': {default: ''}
            , 'prison-city': {default: ''}
            , 'url': {default: '/job-post.html'}
            , 'prison-page-link': {default: ''}
            , 'selected': {default: false}
        },
      data() {
        return {
          formattedDistance: ' '
        }
      },
      watch: {
          distance: function() {
            if (this.distance) {
              this.formattedDistance = parseFloat(this.distance).toFixed(1) + ' mi.';
            } else {
              this.formattedDistance =  ' ';
            }
          }
      }
    }
</script>
