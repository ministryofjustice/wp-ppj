<template>
  <div class="screen-overlay"
       :class="{'screen-overlay--active': active}"
  >
    <div class="screen-overlay__message-container">
      <spinner></spinner>
      <div class="screen-overlay__message">{{ message }}</div>
    </div>
  </div>
</template>
<script>
  import Spinner from './Spinner.vue';

  export default {
    components: {
      'spinner': Spinner
    },
    props: {
      'active'  : { 'default' : false },
      'message' : { 'message' : '' },
    }
  }
</script>
