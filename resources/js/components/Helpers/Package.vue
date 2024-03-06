<template>
    <div>
      <h2 v-if="sectionName" class="section-title">{{ sectionName }}</h2>
      <hr v-if="sectionName">
      <div class="input-fields-wrapper">
        <div class="column-left">
          <InputComponent :inputValue="packageData.itemName" 
                @update-value="updatePackage('itemName', $event)" 
               :input-name="'Item name (displayed in the waybill)'" :is-required="true"/>
                <div v-if="errors.find(e => e.includes('itemName'))" class="error-text"> Item name is required. </div>

          <InputComponent :inputValue="packageData.weight" 
                @update-value="updatePackage('weight', $event)" 
                :input-type="'number'" :input-name="'Weight (in kg)'" :is-required="true"/>
                <div v-if="errors.find(e => e.includes('weight'))" class="error-text"> Weight is required. </div>

          <InputComponent :inputValue="packageData.labelDescription" 
                @update-value="updatePackage('labelDescription', $event)" 
                :input-name="'Label Description'" :is-required="true"/>
                <div v-if="errors.find(e => e.includes('labelDescription'))" class="error-text"> Label Description is required. </div>

          <InputComponent :inputValue="packageData.additionalDeliveryMessage" 
                @update-value="updatePackage('additionalDeliveryMessage', $event)" 
               :input-name="'Additional note (e.g. fragile)'"/>
        </div>
        <div class="column-right">
          <InputComponent :inputValue="packageData.price" 
                @update-value="updatePackage('price', $event)" 
                :input-type="'number'" :input-name="'Price'" :is-required="true"/>
                <div v-if="errors.find(e => e.includes('price'))" class="error-text"> Price is required. </div>

          <InputComponent :inputValue="packageData.packageDescription" 
                @update-value="updatePackage('packageDescription', $event)" 
                :input-name="'Package Description'" :is-required="true" />
                <div v-if="errors.find(e => e.includes('packageDescription'))" class="error-text"> Package Description is required. </div>

          <InputComponent :inputValue="packageData.additionalInformation" 
                @update-value="updatePackage('additionalInformation', $event)" 
               :input-name="'Additional product description'"/>

          
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
      packageData: {
        type: Object,
        required: true,
      },
      errors: {
        type: Array,
        required: true,
        default: () => [],
      },
    },
    methods: {
      updatePackage(key, value) {
        this.$emit('update:packageData', { ...this.packageData, [key]: value });
      },
    },
  };
  </script>
  