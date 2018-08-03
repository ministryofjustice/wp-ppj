import 'url-search-params-polyfill';
import Vue from 'vue';
import FindAJob from '../vue/FindAJob.vue';
import Spinner from '../vue/Spinner.vue';

window.addEventListener('load', function() {

  if (document.querySelectorAll('.find-a-job-container').length > 0) {

    Vue.component('find-a-job', FindAJob);

    var vm = new Vue({
      el: '.find-a-job-container',
      components: {
        'spinner': Spinner
      },
      methods: {
        pageLoaded: function () {
          this.$emit('pageLoaded');
        }
      }
    });

  }

});
