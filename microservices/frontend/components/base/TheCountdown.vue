<template>
    <div class="countdown-timer" v-if="date">
        <!-- <span data-days>{{ twoDigitsFormat(date.days) }}</span>:
        <span data-hours>{{ twoDigitsFormat(date.hours) }}</span>: -->
        <span data-minutes>{{ twoDigitsFormat(date.minutes) }}</span>:
        <span data-seconds>{{ twoDigitsFormat(date.seconds) }}</span>
    </div>
</template>

<script setup lang="ts">
import { calculateCountdown, Countdown, twoDigitsFormat } from "~/utils/countdown";

const date = ref<Countdown | null>();
const props = withDefaults(
    defineProps<{
        date?: string;
    }>(),
    {
        date: "6/28/2024",
    }
);

const emit = defineEmits(['countdownFinished'])
const refreshTime = setInterval(() => {
    date.value = calculateCountdown(new Date(props.date));
    if (date.value.minutes === 0 && date.value.seconds === 0) {
        date.value = null;
        emit("countdownFinished", false);
        clearInterval(refreshTime);
    }
}, 1000);

onMounted(() => {
    date.value = calculateCountdown(new Date(props.date));
})

onUnmounted(() => {
    clearInterval(refreshTime);
})
</script>

<style scoped>
.countdown-timer {
    @apply font-IRANSans_Medium text-16 text-darker-gray;
    direction: ltr;
}
</style>