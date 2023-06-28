<template>
    <div class="flex justify-center items-center gap-2 text-xs" :class="textColor">
        <div class="w-20 h-0.5 px-2" :class="separatorColor"></div>
        <span class="text-center">{{ title }}</span>
        <div class="w-20 h-0.5 px-2" :class="separatorColor"></div>
    </div>
</template>

<script setup>
defineProps({
    textColor: {
        type: String,
        default: 'text-primary'
    },
    separatorColor: {
        type: String,
        default: 'bg-primary'
    },
    title: {
        type: String,
        default: 'جدا کننده'
    }
})
</script>