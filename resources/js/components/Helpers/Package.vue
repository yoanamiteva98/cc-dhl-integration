<template>
    <div>
      <h2 v-if="sectionName" class="section-title">{{ sectionName }}</h2>
      <p>Enter the details of your package.<br>
        Note: All measurements are in kilograms and dimensions are in the metric system.
      </p>
      <hr v-if="sectionName">
      <div class="input-fields-wrapper">
        <div class="column-left">
          <InputComponent v-model="packageData.weight" :input-type="'number'" :input-name="'Weight (in kg)'" />
          <InputComponent v-model="packageData.length" :input-type="'number'" :input-name="'Length (in meters)'" />
          <InputComponent v-model="packageData.packageDescription" :input-name="'Package Description'" />
        </div>
        <div class="column-right">
          <InputComponent v-model="packageData.width" :input-type="'number'" :input-name="'Width (in meters)'" />
          <InputComponent v-model="packageData.height" :input-type="'number'" :input-name="'Height (in meters)'" />
          <InputComponent v-model="packageData.labelDescription" :input-name="'Label Description'" />
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import InputComponent from './InputComponent.vue';
  
  export default {
    name: 'Package',
    components: {
      InputComponent,
    },
    props: {
      sectionName: {
        type: String,
        required: false,
      },
      packageData: { // Define the prop name
        type: Object,
        required: true,
      },
    },
  };
  </script>
  