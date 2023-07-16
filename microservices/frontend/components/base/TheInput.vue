<template>
  <div class="relative flex flex-col w-full">
    <label class="form-label" v-if="label">{{ label }}</label>

    <textarea v-if="type === 'textarea'" rows="3" :name="name" :value="modelValue"
      :class="['form-control', { 'border-danger': errorMessage }]"
      :placeholder="placeholder" @input="handleInputChange" />

    <input v-else :type="type" :name="name" :value="modelValue"
      :class="['form-control', { 'border-danger': errorMessage }]"
      :placeholder="placeholder" @input="handleInputChange" />
    <slot />
    <span v-if="errorMessage" class="input-error-message">{{ errorMessage
    }}</span>
  </div>
</template>
  
<script setup>
const props = defineProps({
  label: {
    default: "نام",
    type: String,
  },
  placeholder: {
    default: "",
    type: String,
  },
  type: {
    default: "text",
    type: String,
  },
  modelValue: {
    default: "",
  },
  name: {
    type: String,
    required: true,
  },
});

const {
  errorMessage,
  handleChange,
  value: inputValue,
  setValue,
} = useField(props.name, undefined, {
  initialValue: props.modelValue,
});
const emit = defineEmits(["update:modelValue"]);
watch(
  () => props.modelValue,
  (val) => setValue(val)
);
const handleInputChange = (e) => {
  emit("update:modelValue", e.target.value);
  handleChange(e);
};
</script>