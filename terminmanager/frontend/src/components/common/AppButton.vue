<template>
  <button :class="buttonClass" @click="$emit('click')" :disabled="disabled">
    <slot />
  </button>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'primary'
  },
  disabled: {
    type: Boolean,
    default: false
  }
})

defineEmits(['click'])

const buttonClass = computed(() => {
  return {
    'btn-primary': props.variant === 'primary',
    'btn-slot': props.variant === 'slot'
  }
})
</script>

<style scoped>
.btn-slot {
  display: flex;
  padding: 8px 16px;
  justify-content: center;
  align-items: center;
  background-color: #002198;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  color: #FFFFFF;
  font-family: var(--font-primary);
  font-size: 14px;
  font-weight: 600;
}

.btn-slot:hover {
  opacity: 0.9;
}

button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
