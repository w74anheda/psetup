<template>
    <div
        class="h-full grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 gap-1 py-3 items-center">
        <div class="bg-light-blue w-full h-56 p-2 cursor-pointer rounded-xl"
            @click="useModal().modalHandler(true), (mode = 'add')">
            <div
                class="flex flex-col gap-2 p-5 text-darker-gray justify-center items-center h-full border-dashed border-2 border-secondary rounded-xl">
                <Icon name="ic:outline-add-location" size="25" />
                افزودن آدرس جدید
            </div>
        </div>
        <ProfileAddresses @editMode="(val) => (mode = val)" />
        <Transition name="fade">
            <BaseTheModal :large="largeMapModal"
                :title="mode === 'add' ? 'افزودن آدرس' : 'ویرایش آدرس'"
                v-if="modal">
                <ModalsAddressesAddAddress
                    :address="mode === 'add' ? undefined : mode.data"
                    @mapModal="(value) => (largeMapModal = value)" />
            </BaseTheModal>
        </Transition>
    </div>
</template>

<script setup lang="ts">
import { useModal } from "~~/store/base/modal";
import { useAddress } from "~~/store/addresses";

const modal = computed(() => useModal().modal);
const largeMapModal = ref(false);
const mode = ref<any>(null);

onMounted(async () => {
    if (!useAddress().userAddresses.length) {
        await useAddress().getUserAddresses();
        useAddress().getState();
        useAddress().getCity();
    }
}),
    definePageMeta({
        layout: "profile",
    });
</script>
