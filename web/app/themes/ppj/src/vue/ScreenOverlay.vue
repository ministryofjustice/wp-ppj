<template>
  <div class="screen-overlay"
       :class="{'screen-overlay--active': active}"
  >
    <div class="screen-overlay__content">
     <slot></slot>
    </div>
  </div>
</template>
<script>
  export default {
    props: {
      'active'  : { 'default' : false },
    }
  }
</script>
