<template>
    <div>
        حساب شما تکمیل نیست. برای تکمیل حساب کاربری خود
        <span @click="useModal().modalHandler(true)"
            class="text-primary cursor-pointer font-IRANSans_Medium border-b border-dashed">اینجا</span>
        کلیک کنید.

        <Transition name="fade">
            <BaseTheModal title="تکمیل حساب کاربری" v-if="modal">
                <ModalsProfilePersonalInfoNationalId />
                <ModalsProfilePersonalInfoBirthDay />
            </BaseTheModal>
        </Transition>
    </div>
</template>

<script setup lang="ts">
import { useModal } from "~~/store/base/modal";

definePageMeta({
    layout: "profile",
});

const modal = computed(() => useModal().modal);
</script>
