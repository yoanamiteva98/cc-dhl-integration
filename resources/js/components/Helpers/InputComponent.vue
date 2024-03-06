<template>
    <div>
      <h3 v-if="inputName" class="input-name">{{ inputName }} <span v-if="isRequired" class="required-indicator">*</span></h3>
      <input :type="inputType" :class="{ 'text-input': true, 'disabled': isDisabled }" 
             :placeholder="inputPlaceholder" :value="inputValue" 
             @input="updateInputValue($event.target.value)" :required="isRequired"/>
             <div v-if="showErrorInput" class="error-text">{{ errorMessage }}</div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'InputComponent',
    props: {
      inputName: {
        type: String,
        required: false
      },
      inputType: {
        type: String,
        required: false,
        default: 'text'
      },
      inputPlaceholder: {
        type: String,
        required: false,
      },
      inputValue: {
        required: false,
      },
      isRequired: {
        type: Boolean,
        default: false
      },
      isDisabled: {
        type: Boolean,
        default: false
      },
    },
    data() {
        return {
            showErrorInput: false,
            errorMessage: ''
        }
    },
    methods: {
      updateInputValue(value) {
        if (this.inputType === 'postcode') {
            if (!/^\d{4}$/.test(value) || value.startsWith('0')) {
                this.showErrorInput = true;
                this.errorMessage = 'Postcode must be 4 digits and not start with 0';
            }  else {
                this.showErrorInput = false;
                this.errorMessage = '';
            }
        } else if (this.inputType === 'phone') {
                if (!/^\d{10}$/.test(value) || !value.startsWith('0')) {
                this.showErrorInput = true;
                this.errorMessage = 'Phone must be 10 digits and start with 0';
            }
            else {
                this.showErrorInput = false;
                this.errorMessage = '';
            }
        } else if (this.inputType === 'email') {
            if (!/\S+@\S+\.\S+/.test(value)) {
            this.showErrorInput = true;
            this.errorMessage = 'Please enter a valid email address';
            } else {
            this.showErrorInput = false;
            this.errorMessage = '';
            }
        }

        this.$emit('update-value', value);
      
    }
  }
};
  </script>
  
  