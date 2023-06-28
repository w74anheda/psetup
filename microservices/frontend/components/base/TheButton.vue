<template>
    <button class="btn" :class="{ 'w-full': block }">
        <Icon v-if="icon && prepend" :name="icon" />
        {{ title }}
        <Icon v-if="icon && !prepend" :name="icon" />
    </button>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        default: "دکمه",
    },
    icon: {
        type: String,
        default: "",
    },
    prepend: {
        type: Boolean,
        default: false,
    },
    block: {
        type: Boolean,
        default: false,
    },
});
</script>
