<template>
  <div class="job-summary"
       :class="{'job-summary--selected': selected}">
    <div class="job-summary__content">
      <div class="job-summary__row">
        <div v-if="prisonName || formattedPartTime">
          <div class="job-summary__prison-name" v-if="prisonName">
            {{prisonName}}
            <div class="job-summary__tag job-summary__tag--part-time" v-if="formattedPartTime">
                {{formattedPartTime}}
            </div>
          </div>
        </div>
        <div class="job-summary__salary" v-if="formattedSalary">
             {{formattedSalary}}
        </div>
      </div>
      <div class="job-summary__row">
        <div class="job-summary__prison-city" v-if="prisonName || prisonCity">
            {{prisonCity}}
            <span class="job-summary__distance" v-if="formattedDistance">
                {{formattedDistance}}
            </span>
        </div>
        <a class="job-summary__link"
           v-if="url"
           :href="url"
           @click.prevent="linkClickHandler"
        >
          view job & apply
        </a>
      </div>
        <div class="job-summary__expiry"  v-if="formattedExpiry">
            Deadline:
            <span class="job-summary__expiry__date" v-if="formattedExpiry[1]=='normal'">
              {{formattedExpiry[0]}}
            </span>
            <span class="job-summary__expiry__date--soon" v-if="formattedExpiry[1]=='imminent'">
              {{formattedExpiry[0]}}
            </span>
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
      'part_time'   : { 'default': false },
      'closing_date': { 'default': '' },
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

      linkClickHandler: function(event) {
        this.pushViewJobClickEventToGtm();
        this.$emit('job-link-clicked', event.target.href); //uses target href in case of changes to url by GTM after load
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

      formattedExpiry: function() {
        if(this.closing_date) {
          const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];
          let today = new Date();
          let d = new Date(this.closing_date.substring(6,12) + "-" +  this.closing_date.substring(3, 5) + "-" + this.closing_date.substring(0, 2));
          let diffTime = Math.abs(d - today);
          let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
          let deadlineType = "normal";
          let expiry = d.getDate() + " " + month[d.getMonth()];
          if (diffDays == 0) {
            deadlineType = "imminent";
            expiry = "Today";
          } else if (diffDays == 1) {
            deadlineType = "imminent";
            expiry = "Tomorrow";
          }
          return [expiry,deadlineType];
        }
      },

      formattedPartTime: function(){
        if(this.part_time) {
          return "Part time only";
        }
      },

      formattedSalary: function() {

        if(isNaN(this.salary)) {
          if(this.salary.includes('-')){
            var salary_range = this.salary.split('-');

            if(salary_range.length == 2) {
              if(isNaN(salary_range[0]) == false && isNaN(salary_range[1]) == false) {
                return '£' + Intl.NumberFormat().format(salary_range[0]) + ' - £' + Intl.NumberFormat().format(salary_range[1]);
              }
              else {
                return '';
              }
            }

          }
          return '';
        }
        else {
          return '£' + Intl.NumberFormat().format(this.salary);
        }
      },
    },
  }
</script>
