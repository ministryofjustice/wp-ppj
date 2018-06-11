import 'url-search-params-polyfill';
import Vue from 'vue';
import JobSummary from '../vue/JobSummary.vue';
import FindAJob from '../vue/FindAJob.vue';

window.addEventListener('load', function() {

  if (document.querySelectorAll('.find-a-job-container').length > 0) {

    Vue.component('job-summary', JobSummary);
    Vue.component('find-a-job', FindAJob);

    var vm = new Vue({
      el: '.find-a-job-container',
      methods: {
        pageLoaded: function () {
          this.$emit('pageLoaded');
        }
      }
    });

  }
});
