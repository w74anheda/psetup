<template>
  <Form v-slot="{ meta }" :validation-schema="verifySchema"
    class="md:px-10 px-4 py-5 relative">
    کد 5 رقمی | {{ auth.loginResult?.code }}
    <div class="w-full mb-2">
      <BaseTheSeparator title="تایید موبایل" separatorColor="bg-primary"
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
      <div class="flex flex-col items-center text-center">
        <label class="form-label pt-2" v-if="countDownHandler">کد تایید به شماره
          {{ $route.query.phone }} ارسال شد.</label>
        <label class="form-label pt-2" v-else>کد تایید 5 رقمی</label>
        <BaseTheOTP :count="5" @otpInputValue="(value, index) => {
          checkOtp(value, index);
        }
          " />
      </div>
      <span v-if="!otpCheck" class="input-error-message">لطفا کد را وارد
        نمایید.</span>
      <span class="flex gap-1.5 items-center justify-center my-5">
        <BaseTheCountdown :date="date"
          @countdownFinished="(value) => (countDownHandler = value)"
          v-if="countDownHandler" />
        <span class="text-darker-gray" v-if="countDownHandler">تا دریافت مجدد
          کد...</span>
        <span @click="resendCode" class="border-bottom-link" v-else>کد رو دوباره
          برام بفرست.</span>
      </span>
    </div>
    <BaseTheButton v-if="auth.loginResult?.is_new" @click.prevent="submitVerify"
      type="submit" title="تایید" block :class="[
        'mt-3',
        meta.valid && otpCheck === 5 ? 'btn-primary' : 'btn-disabled',
      ]" />
    <BaseTheButton v-else @click.prevent="submitVerify" title="تایید" block
      :class="['mt-3', otpCheck === 5 ? 'btn-primary' : 'btn-disabled']" />
  </Form>
</template>

<script setup lang="ts">
import * as yup from "yup";
import { useNotify } from "~~/store/notify";
import { Gender } from "~~/models/auth/verify";
import { userVerify } from "~~/services/auth/userVerify";
import { useAuth } from "~~/store/userAuth";

definePageMeta({
  layout: "auth",
});
const router = useRouter();
const verifyData = reactive({
  gender: Gender.مرد,
  firstName: "",
  lastName: "",
  opt: <any>[],
});
const verifySchema = yup.object().shape({
  firstName: yup.string().required().min(3).label("نام"),
  lastName: yup.string().required().min(3).label("نام خانوادگی"),
});
const otpCheck = ref(-1);
const auth = useAuth();
const notify = useNotify();
const genderItems = ref([
  { id: 0, title: "آقا", value: Gender.مرد },
  { id: 1, title: "خانم", value: Gender.زن },
]);
const date = ref(auth.loginResult?.expire_at);
const countDownHandler = ref(true);
const currentRoute = router.currentRoute.value.query.phone;

onMounted(async () => {
  if (currentRoute !== auth.phoneNumber || !auth.phoneNumber) {
    notify.notify("لطفا شماره موبایل خود را وارد نمایید.", "info");
    router.push("/auth/login");
    return;
  }
  if (!auth.loginResult) {
    const res = await auth.getUserLoginData(currentRoute!.toString());
    if (res?.status === 200) {
      auth.loginResult = res.verification;
    }
  }
});

const checkOtp = (value: number, index: number) => {
  value ? (verifyData.opt[index] = value) : verifyData.opt.splice(index, 1);
  otpCheck.value = verifyData.opt.filter(() => {
    return true;
  }).length;
};
const submitVerify = async () => {
  if (otpCheck.value === 5) {
    const res = await userVerify({
      first_name: auth.loginResult?.is_new ? verifyData.firstName : undefined,
      last_name: auth.loginResult?.is_new ? verifyData.lastName : undefined,
      gender: auth.loginResult?.is_new ? verifyData.gender : undefined,
      code: verifyData.opt.join(""),
      hash: auth.loginResult!.hash,
    });
    if (
      res.status === 200 &&
      verifyData.opt.join("") === auth.loginResult?.code
    ) {
      notify.notify("خوش آمدید.", "success");
      localStorage.setItem(
        "auth",
        JSON.stringify({
          token: res.access_token,
          refresh_token: res.refresh_token,
        })
      );
      router.push("/");
      return;
    }
    notify.notify("کد وارد شده، صحیح نمی باشد.", "error");
  }
};
const resendCode = async () => {
  const res = await auth.getUserLoginData(currentRoute!.toString());
  if (res?.status === 200) {
    countDownHandler.value = true;
    date.value = res.verification.expire_at;
    return;
  }
  notify.notify("کد ارسال نشد.", "error");
};
</script>
