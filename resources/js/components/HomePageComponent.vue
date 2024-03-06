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
            <Address :section-name="'Sender Details'" 
                    v-model:address-data="addressDataSender" 
                    :errors="senderErrors" 
                    :show-errors="showErrors">
            </Address>
            <Address :section-name="'Receiver Details'" 
                    v-model:address-data="addressDataReceiver" 
                    :errors="receiverErrors" 
                    :should-display="false">
            </Address>
            <Package :section-name="'Package Details'" 
                    v-model:package-data="packageData"
                    :errors="packageErrors" ></Package>


            <div v-if="showErrors" class="warning-message-box"> {{ this.requestErrors === '' ? this.requestErrors : 'Error while handling the request. Please check if the filled data is of correct type.' }} </div>

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
import Address from './Helpers/Address.vue';
import Package from './Helpers/Package.vue';

export default {
    name: 'HomePageComponent',
    components: {
        InputComponent,
        Address,
        Package
    },
    data() {
        return {
            addressDataSender: {
                countryName: 'Bulgaria',
                countryCode: 'BG',
                countyName: '',
                postalCode: '',
                cityName: '',
                addressLine1: '',
                addressLine2: '',
                addressLine3: '',
                email: '',
                phone: '',
                companyName: '',
                fullName: '',
                taxId: '',
            },
            addressDataReceiver: {
                countryName: 'Bulgaria',
                countryCode: 'BG',
                countyName: '',
                postalCode: '',
                cityName: '',
                addressLine1: '',
                email: '',
                phone: '',
                companyName: '',
                fullName: '',
            },
            packageData: {
                weight: '',
                additionalInformation: '',
                itemName: '',
                price: '',
                packageDescription: '',
                labelDescription: '',
                additionalDeliveryMessage: '',

            },
            invoiceContent: null,
            initialContentVisible: true,
            senderErrors: [],
            receiverErrors: [],
            packageErrors: [],
            showErrors: false,
            requestErrors: '',
        };
    },
    methods: {
        createWaybill() {
            if (!this.validateForm()) {
                return;
            }
            
            this.packageData.weight = parseFloat(this.packageData.weight).toFixed(2);
            
            const requestData = {
                addressSender: this.addressDataSender,
                addressReceiver: this.addressDataReceiver,
                packageData: this.packageData
            };
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
                                this.requestErrors = 'Invoice could not be created. Please check the filled data.';
                                this.showErrors = true;
                            }
                        } else {
                            this.requestErrors = 'Documents could not be generated. Please check the filled data.';
                            this.showErrors = true;
                        }
                    } else if(response.status === 500) {
                        this.requestErrors = 'Documents could not be generated. Please check the filled data.';
                        this.showErrors = true;
                    } else {
                        this.requestErrors = 'Documents could not be generated. Please check the filled data.';
                        this.showErrors = true;
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    this.requestErrors = 'Error fetching data:'. error;
                    this.showErrors = true;
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
        },
        validateForm() {
            this.senderErrors = [];
            this.receiverErrors = [];
            this.packageErrors = [];

            let isValid = true;

            Object.keys(this.addressDataSender).forEach(field => {
                if (!this.addressDataSender[field]) {
                this.senderErrors.push(`Sender ${field} is required.`);
                isValid = false;
                }
            });

            Object.keys(this.addressDataReceiver).forEach(field => {
                if (!this.addressDataReceiver[field]) {
                this.receiverErrors.push(`Receiver ${field} is required.`);
                isValid = false;
                }
            });

            Object.keys(this.packageData).forEach(field => {
                if (field !== 'additionalInformation' && field !== 'additionalDeliveryMessage' && !this.packageData[field]) {
                    this.packageErrors.push(`Package ${field} is required.`);
                    isValid = false;
                }
            });

            return isValid;
        }
    }
};
</script>
  
  <style>
  @import './../../sass/app.scss';
  </style>
  
