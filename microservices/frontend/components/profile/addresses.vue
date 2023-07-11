<template>
    <div
        class="border p-2 border-primary shadow rounded-xl relative cursor-pointer">
        <div class="font-IRANSans_Medium my-1">اهواز - حصیرآباد خ 24 پ 32 جنب
            پمپ بنزین دغاغله</div>
        <div class="flex gap-2 items-center my-2 text-dark-gray">
            <Icon name="ic:outline-my-location" size="20" />
            خوزستان، اهواز
        </div>
        <div class="flex gap-2 items-center my-2 text-dark-gray">
            <Icon name="ic:outline-mail-outline" size="20" />
            6555251525
        </div>
        <div class="flex gap-2 items-center my-2 text-dark-gray">
            <Icon name="ic:outline-phone" size="20" />
            093932355584
        </div>
        <div class="flex gap-2 items-center my-2 text-dark-gray">
            <Icon name="ic:outline-person-outline" size="20" />
            مصطفی نظرپور
        </div>
        <div class="text-center lg:pt-6 text-16 text-primary">
            آدرس پیشفرض
        </div>
        <div class="text-left absolute left-2 bottom-0 my-2">
            <Icon @click="useDropdown().dropdownHandler(true)"
                name="ic:outline-more-horiz" size="25" />
            <BaseTheDropdown position="bottom-right">
                <span>ویرایش</span>
                <span>حذف</span>
            </BaseTheDropdown>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useDropdown } from '~~/store/base/dropdown'
</script>

<style scoped></style>