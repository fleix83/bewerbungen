<template>
  <div class="input-group">
    <label v-if="label" :for="id" class="input-label">
      {{ label }}
      <span v-if="required" class="required">*</span>
    </label>
    <input
      v-if="type !== 'textarea'"
      :id="id"
      :type="type"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :placeholder="placeholder"
      :required="required"
      class="input-field"
    />
    <textarea
      v-else
      :id="id"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :placeholder="placeholder"
      :required="required"
      :rows="rows"
      class="input-field"
    />
  </div>
</template>

<script setup>
defineProps({
  id: {
    type: String,
    required: true
  },
  label: {
    type: String,
    default: ''
  },
  type: {
    type: String,
    default: 'text'
  },
  modelValue: {
    type: [String, Number],
    default: ''
  },
  placeholder: {
    type: String,
    default: ''
  },
  required: {
    type: Boolean,
    default: false
  },
  rows: {
    type: Number,
    default: 4
  }
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
.input-group {
  margin-bottom: var(--spacing-md);
}

.input-label {
  display: block;
  margin-bottom: 8px;
  color: #000;
  font-family: var(--font-primary);
  font-size: 16px;
  font-weight: 600;
}

.required {
  color: #d32f2f;
}

.input-field {
  width: 100%;
  padding: 12px 16px;
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  border-radius: 4px;
  font-family: var(--font-primary);
  font-size: 16px;
  color: #000;
}

.input-field:focus {
  outline: none;
  border-color: var(--color-text-title);
}

textarea.input-field {
  resize: vertical;
  min-height: 100px;
}
</style>
