<template>
  <div class="checkbox">
    <input
      :id="id"
      type="checkbox"
      :checked="modelValue"
      @change="$emit('update:modelValue', $event.target.checked)"
      class="checkbox-input"
    />
    <label :for="id" class="checkbox-label">
      {{ label }}
      <span v-if="duration" class="checkbox-duration">({{ duration }})</span>
    </label>
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
    required: true
  },
  duration: {
    type: String,
    default: ''
  },
  modelValue: {
    type: Boolean,
    default: false
  }
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
.checkbox {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: var(--spacing-sm);
}

.checkbox-input {
  width: 24px;
  height: 24px;
  border: 2px solid var(--color-text-title);
  border-radius: 4px;
  background: #FFFFFF;
  cursor: pointer;
  flex-shrink: 0;
  appearance: none;
  position: relative;
}

.checkbox-input:checked {
  background-color: #002198;
  border-color: #002198;
}

.checkbox-input:checked::after {
  content: '✓';
  position: absolute;
  color: white;
  font-size: 16px;
  font-weight: bold;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.checkbox-label {
  color: #000;
  font-family: var(--font-primary);
  font-size: 18px;
  font-weight: 400;
  cursor: pointer;
}

.checkbox-duration {
  color: var(--color-text-muted);
  font-size: 14px;
  margin-left: 8px;
}
</style>
