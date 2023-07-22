<template>
    <div v-if="!user?.personal_info.is_completed">
        حساب شما تکمیل نیست. برای تکمیل حساب کاربری خود
        <span @click="useModal().modalHandler(true)"
            class="text-primary cursor-pointer font-IRANSans_Medium border-b border-dashed">اینجا</span>
        کلیک کنید.

        <Transition name="fade">
            <BaseTheModal title="تکمیل حساب کاربری" v-if="modal">
                <Form :validation-schema="completeProfileSchema">
                    <BaseTheSeparator title="تاریخ تولد" />
                    <ModalsProfileCompleteBirthday :date="date" />
                    <BaseTheSeparator title="کد ملی" />
                    <BaseTheInput v-model="nationalId" type="number" class="mt-3"
                        label="" placeholder="کد ملی 10 رقمی" name="nationalId" />
                    <BaseTheButton title="تایید" class="btn-primary mr-auto mt-5" />
                </Form>
            </BaseTheModal>
        </Transition>
    </div>
    <div v-else>حساب شما تکملیل است.</div>
</template>

<script setup lang="ts">
import { useAuth } from "~~/store/userAuth";
import { useModal } from "~~/store/base/modal";
import * as yup from 'yup';
import { rangeBetween2Numbers } from "~~/utils/main"

const user = computed(() => useAuth().currentUser);
const date = reactive({
    year: '',
    month: '',
    day: ''
})
const nationalId = ref('');
const completeProfileSchema = yup.object().shape({
    nationalId: yup.string().required().length(10).label('کد ملی'),
})

definePageMeta({
    layout: "profile",
});

const modal = computed(() => useModal().modal);
</script>
