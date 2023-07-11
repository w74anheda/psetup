<template>
    <Transition name="alert">
        <div v-if="dropdown" @click="useDropdown().dropdownHandler(false)"
            v-click-outside="closeDropDown" class="dropdown" :class="[
                { 'left-0 md:bottom-7 -bottom-24': position === 'top-right' },
                { 'left-0 md:top-7 -top-24': position === 'bottom-right' },
                { 'right-0 md:bottom-7 -bottom-24': position === 'top-left' },
                { 'right-0 md:top-7 -top-24': position === 'bottom-left' },
            ]">
            <slot />
        </div>
    </Transition>
</template>

<script setup lang="ts">
import { useDropdown } from '~~/store/base/dropdown'

const dropdown = computed(() => useDropdown().dropdown);
withDefaults(
    defineProps<{
        position?: "top-left" | "top-right" | "bottom-right" | "bottom-left";
    }>(),
    {
        position: "bottom-left",
    }
);

const closeDropDown = () => {
    useDropdown().dropdownHandler(false)
}
</script>

<style scoped>
.dropdown {
    @apply bg-light-blue text-darker-gray w-48 shadow-lg p-5 absolute flex flex-col gap-3 rounded-lg text-right overflow-hidden
}
</style>
