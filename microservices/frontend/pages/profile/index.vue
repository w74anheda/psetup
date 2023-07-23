<template>
    <div v-if="!user?.personal_info.is_completed">
        حساب شما تکمیل نیست. برای تکمیل حساب کاربری خود
        <span @click="useModal().modalHandler(true)"
            class="text-primary cursor-pointer font-IRANSans_Medium border-b border-dashed">اینجا</span>
        کلیک کنید.

        <Transition name="fade">
            <BaseTheModal title="تکمیل حساب کاربری" v-if="modal">
                <Form @submit="completeProfile"
                    :validation-schema="completeProfileSchema" v-slot="{ meta }">
                    <BaseTheSeparator title="تاریخ تولد" />
                    <ModalsProfileCompleteBirthday :date="date" />
                    <BaseTheSeparator title="کد ملی" />
                    <BaseTheInput v-model="nationalId" type="number" class="mt-3"
                        label="" placeholder="کد ملی 10 رقمی" name="nationalId" />
                    <BaseTheButton type="submit" title="تایید" :class="[
                        'mr-auto mt-5',
                        meta.valid && date.year && date.month && date.day
                            ? 'btn-primary'
                            : 'btn-disabled',
                    ]" />
                </Form>
            </BaseTheModal>
        </Transition>
    </div>
    <div v-else>حساب شما تکملیل است.</div>
</template>

<script setup lang="ts">
import { useAuth } from "~~/store/userAuth";
import { useModal } from "~~/store/base/modal";
import * as yup from "yup";
import { completeUserProfile } from "~~/services/user/user";
import { useNotify } from "~~/store/notify";

const modal = computed(() => useModal().modal);
const user = computed(() => useAuth().currentUser);
const date = reactive({
    year: "",
    month: "",
    day: "",
});
const nationalId = ref("");
const completeProfileSchema = yup.object().shape({
    //@ts-ignore
    nationalId: yup.string().required().length(10).label("کد ملی").nationalId(),
});

const completeProfile = async () => {
    const dateFormat = `${date.year}-${date.month}-${date.day}`;
    const res = await completeUserProfile(dateFormat, nationalId.value);
    if (res.status === 202) {
        await useAuth().setCurrentUser();
        useNotify().notify("حساب کاربری شما با موفقیت کامل شد.", "success");
        useModal().modalHandler(false);
    } else {
        useNotify().notify("حساب شما کامل نشد، دوباره امتحان کنید.", "error");
    }
};

definePageMeta({
    layout: "profile",
});
</script>
