import Vue from 'vue';
import JobSummary from '../vue/JobSummary.vue';
import Search from '../vue/Search.vue';

window.addEventListener('load', function() {

  if (document.querySelectorAll('.search').length > 0) {

    Vue.component('job-summary', JobSummary);
    Vue.component('search', Search);

    var vm = new Vue({
      el: '.search',
      methods: {
        pageLoaded: function () {
          this.$emit('pageLoaded');
        }
      }
    });

  }
});
