<template>
  <Form class="md:px-10 px-4 py-5">
    <BaseTheSeparator title="احراز هویت" separatorColor="bg-primary"
      textColor="text-primary" />
    <div class="w-full mb-2">
      <div class="flex items-center">
        <BaseTheInput placeholder="رجب" v-model="firstName" name="firstName"
          label="نام" />
      </div>
      <div class="flex items-center mt-2">
        <BaseTheInput placeholder="طیب اردوغان" v-model="lastName" name="lastName"
          label="نام خانوادگی" />
      </div>
      <BaseTheCheckbox v-model="gender" type="radio" label="جنسیت"
        :items="genderItems" class="mt-2" />
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
          :date="date" />
        <span v-else @click="resendCode" class="border-bottom-link">کد رو دوباره
          برام بفرست</span>
      </Transition>
    </div>
  </Form>
</template>

<script setup lang="ts">
// import * as yup from "yup";
import { useNotify } from "~~/store/notify";
import { getNextMinutes } from "~~/utils/countdown.client";
import { Gender } from "~~/models/auth/verify";

definePageMeta({
  layout: "auth",
});
const notify = useNotify();
const key = ref(0);
const genderItems = ref([
  { id: Gender.مرد, title: "آقا", value: Gender.مرد },
  { id: Gender.زن, title: "خانم", value: Gender.زن }
]);
const gender = ref(null);
const firstName = ref("");
1;
const lastName = ref("");
const opt: Ref = ref([]);
const date = ref(
  new Date().toLocaleDateString().slice(0, 10).toString() +
  " " +
  getNextMinutes(2)
);
const countdownHandler = ref(true);

const otpDigitSubmit = (value: number, index: number) => {
  let currentIndex = index - 1;
  value ? (opt.value[currentIndex] = value) : opt.value.splice(currentIndex, 1);
  if (opt.value.filter(() => { return true }).length === 5) {
    notify.notify(`کد ${opt.value.join("")} وارد شده صحیح نمی باشد.`, "error");
    opt.value = [];
    key.value++;
  }
};
const resendCode = () => {
  countdownHandler.value = true;
  notify.notify("کد 5 رقمی، به شماره موبایل 093123123 ارسال شد.", "info");
  opt.value = [];
  date.value =
    new Date().toLocaleDateString().slice(0, 10).toString() +
    " " +
    getNextMinutes(2);
  key.value++;
};
</script>
