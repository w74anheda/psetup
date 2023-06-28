<template>
    <Form @submit="userLogin" :validation-schema="loginFormSchema"
        v-slot="{ meta }">
        <div class="md:px-10 px-4 py-5">
            <BaseTheSeparator title="ورود با موبایل" separatorColor="bg-primary"
                textColor="text-primary" />
            <div class="w-full mb-2">
                <div class="flex items-center">
                    <BaseTheInput type="tel" placeholder="09xxxxxxxxx"
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

definePageMeta({
    layout: "auth",
});

const router = useRouter();
const phone = ref("");

const loginFormSchema = yup.object().shape({
    phone: yup.string().required().min(11).max(11),
});

const userLogin = () => {
    console.log(phone.value);
    router.push("/auth/verify");
};
</script>
