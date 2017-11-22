<template>
    <div class="organization-summary" v-cloak>
        <div class="organization-summary__name"><strong>{{name}}</strong></div>
        <div class="organization-summary__address-line-1">{{addressLine1}}</div>
        <div class="organization-summary__address-line-2">{{addressLine2}}</div>
        <div class="organization-summary__city">{{city}}, {{postcode}}</div>
        <div class="organization-summary__category">{{category}}</div>
        <a :href="linkUrl" class="organization-summary__link-url">View details</a>
    </div>
</template>
<script>
    export default {
        props: ['name', 'address-line-1', 'address-line-2', 'city', 'postcode', 'category', 'link-url'],

        data() {
            return {
            }
        },
        methods: {
        },
        mounted() {
          console.log('a l 1', this.addressLine1);
        }
    }
</script>