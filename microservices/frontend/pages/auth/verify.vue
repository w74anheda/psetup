<template>
  <Form class="md:px-10 px-4 py-5">
    <BaseTheSeparator title="احراز هویت" separatorColor="bg-primary"
      textColor="text-primary" />
    <div class="w-full mb-2">
      <div class="flex items-center">
        <BaseTheInput placeholder="رجب طیب اردوغان" v-model="fullName"
          name="fullName" label="نام و نام خانوادگی" />
      </div>
      <Transition name="fade" mode="out-in">
        <div class="flex flex-col items-center" v-if="countdownHandler">
          <label class="form-label pt-2">کد 5 رقمی یکبار مصرف</label>
          <BaseTheOTP :key="key"
            @otpInputValue="(value, index) => otpDigitSubmit(value, index)" />
        </div>
      </Transition>
      <Transition name="fade" mode="out-in">
        <BaseTheCountdown v-if="countdownHandler"
          @countdownFinished="(value) => (countdownHandler = value)"
          :date="`${currentDate} ${getNextMinutes(0.35)}`" />
        <span @click="resendCode" class="border-bottom-link" v-else>کد رو دوباره
          برام بفرست</span>
      </Transition>
    </div>
  </Form>
</template>

<script setup lang="ts">
// import * as yup from "yup";
import { useNotify } from "~~/store/notify";
import { getNextMinutes } from "~~/utils/countdown.client";

definePageMeta({
  layout: "auth",
});
const key = ref(0);
const notify = useNotify();
const fullName = ref("");
const opt: Ref = ref([]);
const currentDate = ref(
  new Date().toLocaleDateString().slice(0, 10).toString()
);
const countdownHandler = ref(true);

const otpDigitSubmit = (value: number, index: number) => {
  let currentIndex = index - 1;
  value ? (opt.value[currentIndex] = value) : opt.value.splice(currentIndex, 1);
  if (opt.value.length === 5) {
    notify.notify(`کد ${opt.value.join("")} وارد شده صحیح نمی باشد.`, "error");
    opt.value = [];
    key.value++;
  }
};
const resendCode = () => {
  countdownHandler.value = true;
  notify.notify("کد 5 رقمی، به شماره موبایل 093123123 ارسال شد.", "info");
  opt.value = [];
  key.value++;
};
</script>
