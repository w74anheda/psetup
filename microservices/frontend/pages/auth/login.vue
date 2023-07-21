<template>
    <Form @submit="submitLogin" :validation-schema="loginFormSchema"
        v-slot="{ meta }">
        <div class="md:px-10 px-4 py-5">
            <BaseTheSeparator title="ورود با موبایل" separatorColor="bg-primary"
                textColor="text-primary" />
            <div class="w-full mb-2">
                <div class="flex items-center">
                    <BaseTheInput type="number" placeholder="09xxxxxxxxx"
                        v-model="phone" name="phone" label="شماره موبایل" />
                </div>
            </div>
            <BaseTheButton block
                :class="['mt-2', meta.valid ? 'btn-primary' : 'btn-disabled']"
                title="ورود" />
        </div>
    </Form>
</template>

<script setup lang="ts">
import * as yup from "yup";
import { useNotify } from "~~/store/notify";
import { useAuth } from "~~/store/userAuth";

definePageMeta({
    layout: "auth",
});

onMounted(() => {
    auth.phoneNumber = null;
});

const auth = useAuth();
const notify = useNotify();
const phone = ref("");
const router = useRouter();

const loginFormSchema = yup.object().shape({
    //@ts-ignore
    phone: yup.string().phone(),
});

const submitLogin = async () => {
    const res = await auth.getUserLoginData(phone.value);
    var returnTo = router.currentRoute.value.query.returnTo?.toString();

    if (res?.status) {
        auth.phoneNumber = phone.value;
        auth.loginResult = res.data.verification;
        router.push({ path: "/auth/verify", query: { phone: auth.phoneNumber, returnTo } });
        return;
    }
    notify.notify("لطفا دوباره تلاش کنید.", "error");
};
</script>
