<template>
    <div
        class="h-full grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 gap-1 py-3">
        <div class="bg-light-blue w-full p-2 cursor-pointer rounded-xl"
            @click="useModal().modalHandler(true)">
            <div
                class="flex flex-col gap-2 text-darker-gray justify-center items-center h-full border-dashed border-2 border-secondary rounded-xl">
                <Icon name="ic:outline-add-location" size="25" />
                افزودن آدرس جدید
            </div>
        </div>
        <ProfileAddresses />
        <Transition name="fade">
            <BaseTheModal large title="افزودن آدرس جدید" v-if="modal">
                <ModalsAddressesAddAddress />
                <!-- <div class="flex justify-between items-center mt-3">
                    <span class="text-12">لطفا محل خود را بر روی نقشه انتخاب
                        کنید.</span>
                    <BaseTheButton title="تایید و ادامه" class="btn-primary" />
                </div> -->
            </BaseTheModal>
        </Transition>
    </div>
</template>

<script setup lang="ts">
import { IAddress } from "~~/models/address";
import { useModal } from "~~/store/base/modal";
import { useAddress } from "~~/store/addresses";

const modal = computed(() => useModal().modal);
const addresses = ref<IAddress>();

onMounted(async () => {
    // const res = await useAddress().getUserAddresses();
})

definePageMeta({
    layout: "profile",
});
</script>