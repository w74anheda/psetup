<template>
    <div dir="ltr" class="flex py-5 justify-between items-end text-center">
        <BaseTheSelect v-model="date.year" :icon="false" placeholder="سال"
            class="!w-20"
            :options="rangeBetween2Numbers(todayYear, todayYear - 100).map(number => padTo2Digits(number))" />
        <span class="font-IRANSans_Medium text-20">/</span>
        <BaseTheSelect v-model="date.month" :icon="false" placeholder="ماه"
            class="!w-20"
            :options="rangeBetween2Numbers(1, 12).map(number => padTo2Digits(number))" />
        <span class="font-IRANSans_Medium text-20">/</span>
        <BaseTheSelect v-model="date.day" :icon="false" placeholder="روز"
            class="!w-20"
            :options="rangeBetween2Numbers(1, 31).map(number => padTo2Digits(number))" />
    </div>
</template>

<script setup lang="ts">
defineProps({
    date: {
        type: Object,
        required: true,
    },
});
const todayYear = Number(
    new Date().toLocaleDateString("fa-IR-u-nu-latn", { year: "numeric" })
);
</script>
