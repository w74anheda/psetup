<template>
    <div class="grid grid-cols-2">
        <div v-for="item in items" :key="item.id" class="edit-section">
            <div class="flex flex-col">
                <label class="form-label">{{ item.label }}</label>
                <span class="font-IRANSans_Medium">{{ item.value }}</span>
            </div>
            <Icon v-if="item.value" name="ri:edit-2-line" class="text-primary"
                size="25" />
            <Icon v-else="item.value" name="ri:add-line" size="25" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { useAuth } from '~~/store/userAuth';
import { getPersianDate } from '~~/utils/persiandateformat'

const user = computed(() => useAuth().currentUser);
const items = computed(() => [{ id: 0, label: 'نام', value: user.value?.first_name ?? '' },
{ id: 1, label: 'نام خانوادگی', value: user.value?.last_name ?? '' },
{ id: 2, label: 'شماره موبایل', value: user.value?.phone ?? '' },
{ id: 3, label: 'ایمیل', value: user.value?.email ?? '' },
{ id: 4, label: 'کد ملی', value: user.value?.personal_info.national_id ?? '' },
{ id: 5, label: 'تاریخ تولد', value: getPersianDate(user.value?.personal_info.birth_day, "y/M/dd") ?? '' }])

definePageMeta({
    layout: "profile",
});
</script>

<style scoped>
.edit-section {
    @apply md:col-span-1 col-span-full flex items-center justify-between py-6 px-2 border-secondary border-b last:border-b-0 md:[&:nth-child(5)]:border-b-0 md:border-b md:border-l even:md:border-l-0
}
</style>