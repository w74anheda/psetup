<template>
    <button class="btn" :class="{ 'w-full': block }">
        {{ title }}
        <slot />
    </button>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        default: "دکمه",
    },
    block: {
        type: Boolean,
        default: false,
    },
});
</script>
