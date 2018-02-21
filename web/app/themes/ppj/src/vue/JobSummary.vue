<template>
  <div class="job-summary"
       :class="{'job-summary--selected': selected}">
    <div class="job-summary__content">
      <div class="job-summary__position"
           v-if="false">
        {{formattedRole}}
      </div>
      <div class="job-summary__prison-name"
           v-if="prisonName">{{prisonName}}
      </div>
      <div class="job-summary__distance"
           v-if="formattedDistance">
        {{formattedDistance}}
      </div>
      <div class="job-summary__salary"
           v-if="formattedSalary">{{formattedSalary}}
      </div>
      <div class="job-summary__prison"
           v-if="prisonName || prisonCity">
        <div class="job-summary__prison-city">
          {{prisonCity}}
        </div>
      </div>
      <a class="job-summary__link"
         v-if="url"
         @click="handleViewJobClick"
      >
        view job
      </a>
    </div>
  </div>
</template>
<script>
  export default {
    props: {
      'distance': {default: 0}
      , 'distance-time': {default: ''}
      , 'position': {default: ''}
      , 'prison-city': {default: ''}
      , 'prison-name': {default: ''}
      , 'prison-page-link': {default: ''}
      , 'salary': {default: ''}
      , 'selected': {default: false}
      , 'title': {default: 'remove me'}
      , 'url': {default: '/job-post.html'}
    },

    methods: {
      handleViewJobClick: function() {
        ga('send', 'event', 'View Job', 'View job',this.prisonName ,5);
        window.location = this.url;
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
          distanceStr = ' ';
        }

        return distanceStr;
      },

      formattedRole: function() {
        let formattedRoleText = '';
        switch (this.position) {
          case 'prison-officer':
            formattedRoleText = 'Prison Officer';
            break;
        }
        return formattedRoleText;
      },

      formattedSalary: function() {
        return '£' + Intl.NumberFormat().format(this.salary)
      }
    }
  }
</script>
