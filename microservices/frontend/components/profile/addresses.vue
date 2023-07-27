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
        <div class="flex gap-1 items-center text-12 text-primary">
            <Icon name="ri:checkbox-circle-line" size="18"/> 
            آدرس پیشفرض
        </div>
        <div class="text-left absolute left-2 -bottom-1 my-2">
            <Icon
                @click="useDropdown().dropdownHandler(true), selectedAddress = item.id"
                name="ic:outline-more-horiz" size="25" />
            <Transition name="alert">
                <BaseTheDropdown v-if="dropdown && selectedAddress === item.id"
                    position="bottom-right">
                    <span>ویرایش</span>
                    <span @click="deleteUserAddress(item.id)">حذف</span>
                </BaseTheDropdown>
            </Transition>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useDropdown } from '~~/store/base/dropdown'
import { useAddress } from "~~/store/addresses";
import { useAuth } from '~~/store/userAuth';
import { deleteAddress } from '~~/services/address';
import { useNotify } from '~~/store/notify';

const dropdown = computed(() => useDropdown().dropdown);

const addresses = computed(() => useAddress().userAddresses);
const user = computed(() => useAuth().currentUser)
const selectedAddress = ref(-1);

const deleteUserAddress = async (id: number) => {
    const res = await deleteAddress(id);
    if (res.status === 202) {
        await useAddress().getUserAddresses();
        useNotify().notify('آدرس مورد نظر با موفقیت حذف شد.', 'success')
    } else {
        useNotify().notify('آدرس حذف نشد، دوباره امتحان کنید.', 'error')
    }
}
</script>

<style scoped></style>