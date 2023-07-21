<template>
    <div
        class="h-full grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 gap-1 py-3">
        <div class="bg-light-blue w-full p-2 cursor-pointer rounded-xl"
            @click="useModal().modalHandler(true), selectedModal = 0">
            <div
                class="flex flex-col gap-2 p-5 text-darker-gray justify-center items-center h-full border-dashed border-2 border-secondary rounded-xl">
                <Icon name="ic:outline-add-location" size="25" />
                افزودن آدرس جدید
            </div>
            {{ addresses }}
        </div>
        <ProfileAddresses />
        <Transition name="fade">
            <BaseTheModal :large="largeMapModal" title="افزودن آدرس جدید"
                v-if="modal && selectedModal === 0">
                <ModalsAddressesAddAddress
                    @mapModal="value => largeMapModal = value" />
            </BaseTheModal>
        </Transition>
    </div>
</template>

<script setup lang="ts">
import { useModal } from "~~/store/base/modal";
import { useAddress } from "~~/store/addresses";

const largeMapModal = ref(false);
const selectedModal = ref(-1);
const modal = computed(() => useModal().modal);
const addresses = computed(() => useAddress().userAddresses);

definePageMeta({
    layout: "profile",
});
</script>