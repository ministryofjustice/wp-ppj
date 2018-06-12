<template>
  <div class="job-summary"
       :class="{'job-summary--selected': selected}">
    <div class="job-summary__content">
      <div class="job-summary__row">
        <div class="job-summary__prison-name"
             v-if="prisonName">{{prisonName}}
        </div>

        <div class="job-summary__salary"
             v-if="formattedSalary">{{formattedSalary}}
        </div>
      </div>
      <div class="job-summary__row">
        <div class="job-summary__prison-city"
             v-if="prisonName || prisonCity">
          {{prisonCity}}

          <span class="job-summary__distance"
                v-if="formattedDistance">
            {{formattedDistance}}
          </span>
        </div>
        <a class="job-summary__link"
           :href="url"
           v-if="url"
           @click="pushViewJobClickEventToGtm"
        >
          view job & apply
        </a>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    props: {
      'distance'    : { 'default': 0 },
      'prison-city' : { 'default': '' },
      'prison-name' : { 'default': '' },
      'salary'      : { 'default': '' },
      'selected'    : { 'default': false },
      'url'         : { 'default': '' },
    },

    methods: {
      pushViewJobClickEventToGtm: function() {
        const gtmEvent = {
          event: 'view_job_click',
          prisonName: this.prisonName
        };
        window.dataLayer.push(gtmEvent);
      }
    },

    computed: {
      formattedDistance: function () {

        let distanceStr = '';
        const suffix = ' miles';

        if (this.distance > 10) {
          distanceStr = Math.round(parseFloat(this.distance)) + suffix;
        } else if (this.distance > 0) {
          distanceStr = parseFloat(this.distance).toFixed(1) + suffix;
        } else {
          distanceStr = '';
        }

        return distanceStr;
      },

      formattedSalary: function() {
        return '£' + Intl.NumberFormat().format(this.salary)
      },
    },
  }
</script>
