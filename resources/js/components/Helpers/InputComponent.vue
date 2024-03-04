<template>
    <div>
        <h3 v-if="inputName" class="input-name">{{ inputName }}</h3>
        <input :type="inputType" :class="{ 'disabled': isDisabled }" class="text-input" :placeholder="inputPlaceholder" v-model="inputValue" />
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
        isDisabled: {
            type: Boolean,
            required: false,
            default: false
        },
        // You can remove the value prop
    },
    data() {
        return {
            inputValue: '' // Use local data to store input value
        };
    },
    watch: {
        // Watch for changes in inputValue and emit input event
        inputValue(newValue) {
            this.$emit('input', newValue);
        }
    }
}
</script>
