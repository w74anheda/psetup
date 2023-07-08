<template>
    <div class="h-full grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 gap-1 py-3">
        <div class="bg-light-blue w-full p-2 cursor-pointer">
            <div
                class="flex flex-col gap-2 text-darker-gray justify-center items-center h-full border-dashed border-2 border-secondary">
                <Icon name="ic:outline-add-location" size="25" />
                افزودن آدرس جدید
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useModal } from "~~/store/base/modal";
import { useAuth } from "~~/store/userAuth";

const user = computed(() => useAuth().currentUser);
const modal = computed(() => useModal().modal);

definePageMeta({
    layout: "profile",
});
</script>

<style scoped></style>
