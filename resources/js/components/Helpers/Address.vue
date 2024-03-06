<template>
    <div>
      <h2 v-if="sectionName" class="section-title">{{ sectionName }}</h2>
      <hr v-if="sectionName">
      <div class="input-fields-wrapper">
        <div class="column-left">
          <InputComponent :inputValue="address.email" 
                @update-value="newValue => updateAddress('email', newValue)" 
                :input-name="'Email'" 
                :input-type="'email'"
                :is-required="true" />
               <div v-if="errors.find(e => e.includes('email'))" class="error-text"> Email is required. </div>

          <InputComponent :inputValue="address.companyName" 
                @update-value="newValue => updateAddress('companyName', newValue)" 
                :input-name="'Company name'" :is-required="true"/>
                <div v-if="errors.find(e => e.includes('companyName'))" class="error-text"> Company name is required. </div>
          
          <InputComponent :inputValue="address.taxId" v-if="shouldDisplay" 
                @update-value="newValue => updateAddress('taxId', newValue)" 
                :input-name="'TAX ID'" :is-required="true"/>
                <div v-if="errors.find(e => e.includes('taxId'))" class="error-text"> Tax ID is required. </div>

          <InputComponent :inputValue="address.countryName" :input-name="'Country'" :is-disabled="true" />

          <InputComponent :inputValue="address.countyName" 
                @update-value="newValue => updateAddress('countyName', newValue)" 
                :input-name="'County name'" :is-required="true"/>
                <div v-if="errors.find(e => e.includes('countyName'))" class="error-text"> County name is required. </div>

          <InputComponent :inputValue="address.postalCode" 
                @update-value="newValue => updateAddress('postalCode', newValue)" :input-name="'Postal code'" :input-type="'postcode'" :is-required="true"/>
                <div v-if="errors.find(e => e.includes('postalCode'))" class="error-text"> County name is required. </div>

          <InputComponent :inputValue="address.addressLine3" v-if="shouldDisplay" 
                @update-value="newValue => updateAddress('addressLine3', newValue)" 
                :input-name="'Address Line 3'" :is-required="true"/>
                <div v-if="errors.find(e => e.includes('addressLine3'))" class="error-text"> Address line 3 is required. </div>
        </div>
        <div class="column-right">
          <InputComponent :inputValue="address.phone" 
                @update-value="newValue => updateAddress('phone', newValue)" 
                :input-name="'Phone'" :is-required="true" :input-type="'phone'"/>
                <div v-if="errors.find(e => e.includes('phone'))" class="error-text"> Phone is required. </div>

          <InputComponent :inputValue="address.fullName" 
                @update-value="newValue => updateAddress('fullName', newValue)" 
                :input-name="'Full name'" :is-required="true"/>
                <div v-if="errors.find(e => e.includes('fullName'))" class="error-text"> Full name is required. </div>

          <InputComponent :inputValue="address.countryCode" 
                :input-name="'Country code'" :is-disabled="true" :is-required="true"/>

          <InputComponent :inputValue="address.cityName" 
                @update-value="newValue => updateAddress('cityName', newValue)" 
                :input-name="'City'" :is-required="true" />
                <div v-if="errors.find(e => e.includes('cityName'))" class="error-text"> City name is required. </div>

          <InputComponent :inputValue="address.addressLine1" 
                @update-value="newValue => updateAddress('addressLine1', newValue)" 
                :input-name="'Address Line 1'" :is-required="true"/>
                <div v-if="errors.find(e => e.includes('addressLine1'))" class="error-text"> Address line 1 is required. </div>
          
            <InputComponent :inputValue="address.addressLine2" v-if="shouldDisplay" 
                @update-value="newValue => updateAddress('addressLine2', newValue)" 
                :input-name="'Address Line 2'" :is-required="true"/>
                <div v-if="errors.find(e => e.includes('addressLine2'))" class="error-text"> Address line 2 is required. </div>
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
      shouldDisplay: {
        type: Boolean,
        required: false, 
        default: true,
      },
      errors: {
        type: Array,
        required: true,
        default: () => [],
      },
    },
    data() {
      return {
        address: { ...this.addressData },
      };
    },
    methods: {
      updateAddress(key, value) {
        this.address[key] = value;
        this.$emit('update:addressData', { ...this.address });
      },
    },
  };
  </script>
  