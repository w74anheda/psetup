<template>
  <Form ref="form" v-slot="{ validate, meta }" :validation-schema="verifySchema"
    class="md:px-10 px-4 py-5 relative">
    کد 6 رقمی | {{ auth.loginResult?.code }}
    <div class="w-full mb-2">
      <BaseTheSeparator title="احراز هویت" separatorColor="bg-primary"
        textColor="text-primary" />
      <div v-if="auth.loginResult?.is_new">
        <div class="flex items-center">
          <BaseTheInput placeholder="رجب" v-model="verifyData.firstName"
            name="firstName" label="نام" />
        </div>
        <div class="flex items-center mt-2">
          <BaseTheInput placeholder="طیب اردوغان" v-model="verifyData.lastName"
            name="lastName" label="نام خانوادگی" />
        </div>
        <BaseTheCheckbox v-model="verifyData.gender" type="radio" label="جنسیت"
          :items="genderItems" class="mt-2" />
      </div>

      <Transition name="fade" mode="out-in">
        <div v-if="meta.valid">
          <Transition name="fade" mode="out-in">
            <BaseTheOTP :count="6" :key="key" v-if="countdownHandler"
              @otpInputValue="(value, index) => {
                otpDigitSubmit(value, index), validate();
              }
                " />
          </Transition>
          <Transition name="fade" mode="out-in">
            <BaseTheCountdown v-if="countdownHandler"
              @countdownFinished="(value) => (countdownHandler = value)"
              :date="date" />
            <span v-else @click="resendCode" class="border-bottom-link">کد رو
              دوباره
              برام بفرست</span>
          </Transition>
        </div>
      </Transition>
    </div>
  </Form>
</template>

<script setup lang="ts">
import * as yup from "yup";
import { useNotify } from "~~/store/notify";
import { getNextMinutes } from "~~/utils/countdown";
import { Gender } from "~~/models/auth/verify";
import { userVerify } from "~~/services/auth/userVerify";
import { useAuth } from "~~/store/userAuth";

definePageMeta({
  layout: "auth",
});

const verifyData = reactive({
  gender: Gender.مرد,
  firstName: "",
  lastName: "",
  opt: <any>[],
});
const verifySchema = yup.object().shape({
  firstName: yup.string().required().label("نام"),
  lastName: yup.string().required().label("نام خانوادگی"),
});

const form = ref<any>(null);
const auth = useAuth();
const notify = useNotify();
const key = ref(0);
const genderItems = ref([
  { id: 0, title: "آقا", value: Gender.مرد },
  { id: 1, title: "خانم", value: Gender.زن },
]);
const date = ref(
  new Date().toLocaleDateString().slice(0, 10).toString() +
  " " +
  getNextMinutes(2)
);
const countdownHandler = ref(true);

onMounted(async () => {
  if (!auth.loginResult) {
    await auth.refreshUserLoginData();
  }
});

const otpDigitSubmit = async (value: number, index: number) => {
  let currentIndex = index - 1;
  value
    ? (verifyData.opt[currentIndex] = value)
    : verifyData.opt.splice(currentIndex, 1);
  let optLength = verifyData.opt.filter(() => {
    return true;
  }).length;

  if (optLength === 6 && form.value.getMeta().valid) {
    if (verifyData.opt.join("") === auth.loginResult?.code) {
      const res = await userVerify({
        first_name: verifyData.firstName,
        last_name: verifyData.lastName,
        gender: verifyData.gender,
        code: verifyData.opt.join(""),
        hash: auth.loginResult!.hash,
      });
      console.log(res);
    } else {
      notify.notify(
        `کد ${verifyData.opt.join("")} وارد شده صحیح نمی باشد.`,
        "error"
      );
      verifyData.opt = [];
      key.value++;
    }
  }
};
const resendCode = async () => {
  const res = await auth.refreshUserLoginData();
  if (res?.status === 200) {
    countdownHandler.value = true;
    notify.notify("کد 5 رقمی، به شماره موبایل 093123123 ارسال شد.", "info");
    verifyData.opt = [];
    date.value =
      new Date().toLocaleDateString().slice(0, 10).toString() +
      " " +
      getNextMinutes(2);
    key.value++;
    return;
  }
  notify.notify(res?.message, "error");
};
</script>
