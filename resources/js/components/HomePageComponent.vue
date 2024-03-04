<!-- <template>
    <div class="container">
        <div class="head-details">
            <h1>DHL Integration</h1>
            <p>Generate a waybill by filling the details below</p>
        </div>
        
        <Address :section-name="'Sender Details'"  v-model="addressDataSender"> </Address>
        <Address :section-name="'Receiver Details'"  v-model="addressDataReceiver"> </Address>
        <Package :section-name="'Package Details'" v-model="packageData"> </Package>

        <div class="align-center">
            <button @click="createWaybill()" class="btn-primary">Generate Waybill</button>
        </div>
    </div>
</template>


<script>
import Address from './Helpers/Address.vue'
import Package from './Helpers/Package.vue'

export default {
    name: 'HomePageComponent',
    components: {
        Address,
        Package
    },
    data() {
    return {
        addressDataSender: {
            country: 'Bulgaria',
            countryCode: 'BG',
            county: '',
            postalCode: '',
            city: '',
            addressLine1: '',
            addressLine2: '',
            addressLine3: '',
        },
        addressDataReceiver: {
            country: 'Bulgaria',
            county: '',
            postalCode: '',
    },
      packageData: {}
    };
  },
    methods: {
        createWaybill() {
            const requestData = {
            };

            axios.post('/create-waybill', requestData)
            .then(response => {
                console.log(this.addressDataSender);
                console.log(response.data); 
            })
            .catch(error => {
                console.error(error); 
            });
        }
    }
}
</script>

<style>
@import './../../sass/app.scss';
</style> -->
<template>
    <div class="container">
    <div v-if="initialContentVisible">
      <div class="head-details">
        <h1>DHL Integration</h1>
        <p>Generate a waybill by filling the details below</p>
      </div>
      <div>
        <h2 class="section-title">Sender Details</h2>
        <hr>
        <div class="input-fields-wrapper">
            <div class="column-left">
            <InputComponent v-model="addressDataSender.country" :input-name="'Country'" :is-disabled="true" />
            <InputComponent v-model="addressDataSender.county" :input-name="'County name'" />
            <InputComponent v-model="addressDataSender.postalCode" :input-name="'Postal code'" />
            <InputComponent v-model="addressDataSender.addressLine2" :input-name="'Address Line 2'" />
            </div>
            <div class="column-right">
            <InputComponent :input-name="'Country code'"  v-model="addressDataSender.countryCode"  :is-disabled="true" />
            <InputComponent v-model="addressDataSender.city" :input-name="'City'" />
            <InputComponent v-model="addressDataSender.addressLine1" :input-name="'Address Line 1'" />
            <InputComponent v-model="addressDataSender.addressLine3" :input-name="'Address Line 3'" />
            </div>
        </div>
    </div>
      
    <div>
        <h2 class="section-title">Receiver Details</h2>
        <hr>
        <div class="input-fields-wrapper">
            <div class="column-left">
            <InputComponent v-model="addressDataReceiver.country" :input-name="'Country'" :is-disabled="true" />
            <InputComponent v-model="addressDataReceiver.county" :input-name="'County name'" />
            <InputComponent v-model="addressDataReceiver.postalCode" :input-name="'Postal code'" />
            <InputComponent v-model="addressDataReceiver.addressLine2" :input-name="'Address Line 2'" />
            </div>
            <div class="column-right">
            <InputComponent :input-name="'Country code'"  v-model="addressDataReceiver.countryCode"  :is-disabled="true" />
            <InputComponent v-model="addressDataReceiver.city" :input-name="'City'" />
            <InputComponent v-model="addressDataReceiver.addressLine1" :input-name="'Address Line 1'" />
            <InputComponent v-model="addressDataReceiver.addressLine3" :input-name="'Address Line 3'" />
            </div>
        </div>
    </div>
    <div>
        <h2 class="section-title">Package Details</h2>
        <p>Enter the details of your package.<br>
            Note: All measurements are in kilograms and dimensions are in the metric system.
        </p>
        <hr>
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
  
      <div class="align-center">
        <button @click="createWaybill()" class="btn-primary">Generate Waybill</button>
      </div>
    </div>
    <div v-if="!initialContentVisible">
        <div v-if="invoiceContent" class="align-center">
            <h2 class="section-title">Great! Download your waybill</h2>
            <button @click="downloadInvoice" class="btn-primary">Download Waybill</button>
        </div>
    </div>
</div>  
  </template>
  
  <script>
  import InputComponent from './Helpers/InputComponent.vue';
  import axios from 'axios';
  
  export default {
    name: 'HomePageComponent',
    components: {
      InputComponent
    },
    data() {
      return {
        addressDataSender: {
          country: 'Bulgaria',
          countryCode: 'BG',
          county: '',
          postalCode: '',
          city: '',
          addressLine1: '',
          addressLine2: '',
          addressLine3: ''
        },
        addressDataReceiver: {
          country: 'Bulgaria',
          countryCode: 'BG',
          county: '',
          postalCode: '',
          city: '',
          addressLine1: '',
          addressLine2: '',
          addressLine3: ''
        },
        packageData: {
          weight: '',
          height: '',
          width: '',
          length: '',
          packageDescription: '',
          labelDescription: ''
        },
        invoiceContent: null,
        initialContentVisible: true,
      };
    },
  methods: {
    createWaybill() {
      const requestData = { };
      axios.post('/create-waybill', requestData)
        .then(response => {
          if (response.status === 200 && response.data) {
            const documents = response.data.documents;
            if (documents && documents.length > 0) {
              const invoiceDocument = documents.find(doc => doc.typeCode === "invoice");
              if (invoiceDocument) {
                this.initialContentVisible = false;
                this.invoiceContent = invoiceDocument.content;
              } else {
                console.error('No invoice document found');
              }
            } else {
              console.error('No documents found in the response');
            }
          } else {
            console.error('Invalid response or missing data');
          }
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    },

    downloadInvoice() {
        if (this.invoiceContent) {
            const bytes = new Uint8Array(atob(this.invoiceContent).split('').map(char => char.charCodeAt(0)));
            const blob = new Blob([bytes], { type: 'application/pdf' });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'invoice.pdf';
            document.body.appendChild(link);
            link.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(link);
        } else {
            console.error('Invoice content is empty');
        }
        }
  }
};
</script>
  
  <style>
  @import './../../sass/app.scss';
  </style>
  
