<template>
    <div>
      <h2 v-if="sectionName" class="section-title">{{ sectionName }}</h2>
      <hr v-if="sectionName">
      <div class="input-fields-wrapper">
        <div class="column-left">
          <InputComponent v-model="addressData.country" :input-name="'Country'" :is-disabled="true" />
          <InputComponent v-model="addressData.county" :input-name="'County name'" />
          <InputComponent v-model="addressData.postalCode" :input-name="'Postal code'" />
          <InputComponent v-model="addressData.addressLine2" :input-name="'Address Line 2'" />
        </div>
        <div class="column-right">
          <InputComponent :input-name="'Country code'"  v-model="addressData.countryCode"  :is-disabled="true" />
          <InputComponent v-model="addressData.city" :input-name="'City'" />
          <InputComponent v-model="addressData.addressLine1" :input-name="'Address Line 1'" />
          <InputComponent v-model="addressData.addressLine3" :input-name="'Address Line 3'" />
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import InputComponent from './InputComponent.vue';
  
  export default {
    name: 'Address',
    components: {
      InputComponent,
    },
    props: {
      sectionName: {
        type: String,
        required: false,
      },
      addressData: {
        type: Object,
        required: true,
      },
    },
    methods: {
      // This method is no longer needed
      // updateAddressData(newValue) {
      //   this.$emit('update:address-data', newValue);
      // },
    },
  };
  </script>
  