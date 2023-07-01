<template>
    <div @click="$emit('closePopover')" class="notification" :class="[
        type === 'error'
            ? 'error'
            : type === 'success'
                ? 'success'
                : type === 'warning'
                    ? 'warning'
                    : 'info',
    ]">
        <Vue3Lottie class="!m-1" :animation-link="`/animations/${type === 'error'
            ? 'error'
            : type === 'success'
                ? 'success'
                : type === 'warning'
                    ? 'warning'
                    : 'info'
            }.json`" :height="50" :width="50"
            :loop="type === 'success' || type === 'warning' ? false : true" />
        {{ message }}
    </div>
</template>

<script setup lang="ts">

defineProps<{
    type?: "error" | "warning" | "success" | "info";
    message: string;
}>();
</script>

<style scoped>
.notification {
    @apply flex items-center my-1 text-right rounded-xl lg:w-1/3 w-3/4 shadow;
}
.error {
    @apply bg-error/30 text-error;
}

.success {
    @apply bg-success/30 text-success;
}

.warning {
    @apply bg-warning/30 text-warning;
}

.info {
    @apply bg-primary/30 text-primary;
}
</style>
