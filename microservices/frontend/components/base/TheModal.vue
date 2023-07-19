<template>
    <div @click.self="useModal().modalHandler(false)" class="main-modal">
        <div class="modal w-[300px] transition-all duration-1000 ease-in-out"
            :class="[large ? 'md:w-[800px]' : 'md:w-[420px]']">
            <div class="flex items-center justify-between pb-5">
                <h1 class="font-IRANSans_Medium text-16">{{ title }}</h1>
                <Icon name="ri:close-line" size="30"
                    @click="useModal().modalHandler(false)"
                    class="float-left text-darker-gray cursor-pointer" />
            </div>
            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
import { useModal } from "~~/store/base/modal"

withDefaults(defineProps<{
    title?: string,
    large?: boolean
}>(), {
    title: 'عنوان',
    large: false
})
</script>

<style scoped>
.main-modal {
    @apply flex justify-center items-center bg-darker-gray/30 h-full w-full fixed top-0 right-0 z-0;
}

.modal {
    @apply bg-white shadow-xl p-3 rounded-xl;
}
</style>
