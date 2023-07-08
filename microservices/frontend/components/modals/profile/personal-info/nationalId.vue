<template>
    <Form>
        <BaseTheInput label="کد ملی" name="nationalId" />
        <BaseTheButton v-if="user?.personal_info.is_completed" title="ویرایش"
            class="btn-primary mr-auto mt-3" />
        <BaseTheButton v-else title="تایید" class="btn-primary mr-auto mt-3" />
    </Form>
</template>

<script setup lang="ts">
import { useAuth } from '~~/store/userAuth';
const user = computed(() => useAuth().currentUser);
</script>

<style scoped></style>