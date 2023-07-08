<template>
    <Form>
        <div dir="ltr" class="flex justify-between items-end text-center">
            <BaseTheInput label="سال" name="year" v-model="date.year"
                class="!w-20" />
            <span class="font-IRANSans_Medium text-20">/</span>
            <BaseTheInput label="ماه" name="month" v-model="date.month"
                class="!w-20" />
            <span class="font-IRANSans_Medium text-20">/</span>
            <BaseTheInput label="روز" name="day" v-model="date.day" class="!w-20" />
        </div>
        <BaseTheButton v-if="user?.personal_info.birth_day" title="ویرایش"
            class="btn-primary mr-auto mt-5" />
        <BaseTheButton v-else title="تایید" class="btn-primary mr-auto mt-3" />
    </Form>
</template>

<script setup lang="ts">
import { useAuth } from '~~/store/userAuth';
import { getPersianDate } from '~~/utils/persiandateformat'
const user = computed(() => useAuth().currentUser);

const date = reactive({
    year: '',
    month: '',
    day: ''
})
onMounted(() => {
    const userBirthDay = user.value?.personal_info.birth_day;
    if (userBirthDay) {
        date.year = getPersianDate(userBirthDay, 'y/M/dd')!.split('/')[2];
        date.month = getPersianDate(userBirthDay, 'y/M/dd')!.split('/')[1];
        date.day = getPersianDate(userBirthDay, 'y/M/dd')!.split('/')[0];
    }
})
</script>

<style scoped></style>