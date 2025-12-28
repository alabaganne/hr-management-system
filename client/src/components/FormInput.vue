<template>
  <div class="grid">
    <label :class="{ required }" :for="name">{{ label }}</label>
    <input
      v-if="type !== 'select' && type !== 'textarea'"
      :id="name"
      :type="type"
      :name="name"
      :placeholder="placeholder"
      :value="modelValue"
      :disabled="disabled"
      @input="$emit('update:modelValue', $event.target.value)"
      class="form-control"
      :class="{ 'is-invalid': error }"
    />
    <textarea
      v-else-if="type === 'textarea'"
      :id="name"
      :name="name"
      :placeholder="placeholder"
      :value="modelValue"
      :disabled="disabled"
      :rows="rows"
      @input="$emit('update:modelValue', $event.target.value)"
      class="form-control"
      :class="{ 'is-invalid': error }"
    ></textarea>
    <select
      v-else
      :id="name"
      :name="name"
      :value="modelValue"
      :disabled="disabled"
      @change="$emit('update:modelValue', $event.target.value)"
      class="form-control custom-select"
      :class="{ 'is-invalid': error }"
    >
      <option value="">{{ placeholder || 'Select an option' }}</option>
      <option
        v-for="option in options"
        :key="typeof option === 'object' ? option.value : option"
        :value="typeof option === 'object' ? option.value : option"
      >
        {{ typeof option === 'object' ? option.label : option }}
      </option>
    </select>
    <p v-if="error" class="text-danger mt-1 mb-0 small">{{ error }}</p>
  </div>
</template>

<script>
export default {
  name: 'FormInput',
  props: {
    modelValue: {
      type: [String, Number],
      default: ''
    },
    type: {
      type: String,
      default: 'text'
    },
    name: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    placeholder: {
      type: String,
      default: ''
    },
    required: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    error: {
      type: String,
      default: null
    },
    options: {
      type: Array,
      default: () => []
    },
    rows: {
      type: Number,
      default: 3
    }
  },
  emits: ['update:modelValue']
}
</script>

<style scoped>
.required::after {
  content: ' *';
  color: #dc3545;
}
</style>
