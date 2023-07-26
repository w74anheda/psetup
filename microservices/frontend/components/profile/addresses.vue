<template>
    <div class="border p-2 border-primary shadow rounded-xl relative cursor-pointer"
        v-for="item in addresses" :key="item.id">
        <div class="font-IRANSans_Medium my-1">{{ item.full_address }}, پلاک {{
            item.house_number }}, واحد {{ item.unit_number }}</div>
        <div class="flex gap-2 items-center my-2 text-dark-gray">
            <Icon name="ic:outline-my-location" size="20" />
            {{ item.city.state.name }}، {{ item.city.name }}
        </div>
        <div class="flex gap-2 items-center my-2 text-dark-gray">
            <Icon name="ic:outline-mail-outline" size="20" />
            {{ item.postalcode }}
        </div>
        <div class="flex gap-2 items-center my-2 text-dark-gray">
            <Icon name="ic:outline-phone" size="20" />
            {{ user?.phone }}
        </div>
        <div class="flex gap-2 items-center my-2 text-dark-gray">
            <Icon name="ic:outline-person-outline" size="20" />
            {{ user?.first_name }} {{ user?.last_name }}
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
import { useAddress } from "~~/store/addresses";
import { useAuth } from '~~/store/userAuth';

const addresses = computed(() => useAddress().userAddresses);
const user = computed(() => useAuth().currentUser)

</script>

<style scoped></style>