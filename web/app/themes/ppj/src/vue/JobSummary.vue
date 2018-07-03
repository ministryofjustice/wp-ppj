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
             v-if="prisonName || prisonCity">{{prisonCity}}<span class="job-summary__distance"  v-if="formattedDistance"> {{formattedDistance}}
          </span>
        </div>

        <a class="job-summary__link"
           v-if="url"
           :href="url"
           @click="linkClickHandler()"
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
      },

      linkClickHandler: function() {
        this.$emit('jobLinkClickedEvent');
        this.pushViewJobClickEventToGtm();

        /*
         * NB: This is fix for the issue whereby clicking the 'View Job & Apply' link
         * in Chrome did not navigate to the location specified by the href attribute
         *
         * This is probably due to a race condition caused by some combination of
         * the linked to site responding slowly and the triggered history.replaceState action
         * happening asynchronously.
         *
         * By adding the following timeout function to explicitly navigate to the supplied url,
         * the correct navigation happens, despite the fact that the timeout is set to happen
         * zero milliseconds later. Presumably in a single threaded environment, the earliest
         * this function gets executed is after the replaceState operation.
         * UPDATE: with the new loading spinner changes, it is now necessary to wait around 800
         * milliseconds.
         *
         * This went unnoticed for a while due to Google Tag Manager presumably slowing
         * some part of the execution sufficiently such that this race condition didn't exist.
         */
        setTimeout(()=>{
          window.location.href = this.url;
        }, 800);
      },

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
