<template>
    <div>
        <label class="form-label">{{ label }}</label>
        <div class="radio-button-container">
            <div class="radio-button" v-for="item in items" :key="item.id">
                <input @change="$emit('update:modelValue', item.value)"
                    :type="type === 'radio' ? 'radio' : 'checkbox'"
                    class="radio-button__input"
                    :id="type === 'radio' ? `radio-${item.id}` : undefined"
                    :name="type === 'radio' ? `radio-group` : undefined"
                    :checked="item.value === modelValue">
                <label class="radio-button__label"
                    :for="type === 'radio' ? `radio-${item.id}` : undefined">
                    <span class="radio-button__custom"></span>
                    {{ item.title }}
                </label>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
withDefaults(
    defineProps<{
        type: 'checkbox' | 'radio';
        items: Array<any>;
        label?: string;
        modelValue: number | string | null;
    }>(), {
    label: 'لیبل',
})
</script>

<style scoped>
.radio-button-container {
    display: flex;
    align-items: center;
    gap: 20px;
    margin: 0.5rem 0;
}

.radio-button {
    display: inline-block;
    position: relative;
    cursor: pointer;

}

.radio-button__input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.radio-button__label {
    @apply text-darker-gray;
    display: inline-block;
    padding-left: 30px;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}

.radio-button__custom {
    position: absolute;
    top: 3px;
    left: 0;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    border: 2px solid;
    transition: all 0.3s ease;
}

.radio-button__input:checked+.radio-button__label .radio-button__custom {
    background-color: #4c8bf5;
    border-color: transparent;
    transform: scale(0.8);
    box-shadow: 0 0 20px #4c8bf580;
}

.radio-button__input:checked+.radio-button__label {
    color: #4c8bf5;
}

.radio-button__label:hover .radio-button__custom {
    transform: scale(1.2);
    border-color: #4c8bf5;
    box-shadow: 0 0 20px #4c8bf580;
}
</style>