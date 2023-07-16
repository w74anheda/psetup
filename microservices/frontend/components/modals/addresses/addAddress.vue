<template>
    <div>
        <div class="grid grid-cols-2 gap-2">
            <Form class="md:col-span-1 col-span-full">
                <div class="md:flex block md:gap-2">
                    <BaseTheAutocomplete label="استان"
                        :items="['Apple', 'Banana', 'Cherry', 'Grapes']"
                        name="ahwaz" v-model="state" />
                    <BaseTheAutocomplete label="شهر"
                        :items="['Apple', 'Banana', 'Cherry', 'Grapes']"
                        name="ahwaz" v-model="city" />
                </div>
                <div class="flex gap-2">
                    <BaseTheInput class="!w-16" label="پلاک" name="houseNumber" />
                    <BaseTheInput class="!w-16" label="واحد" name="unitNumber" />
                    <BaseTheInput class="md:flex-1 !w-[8.25rem]" label="کد پستی" name="postalCode" />
                </div>
                <BaseTheInput type="textarea" label="آدرس پستی"
                    name="postalAddress" />
                <BaseTheCheckbox @click="receiver = !receiver" class="pt-2"
                    v-model="receiver"
                    :items="[{ id: 0, title: 'گیرنده خودم هستم.', value: receiver }]"
                    label="" type="checkbox" />
                <Transition name="fade">
                    <BaseTheInput v-if="!receiver" label="گیرنده" name="receiver" />
                </Transition>
            </Form>
            <BaseTheMap class="md:col-span-1 col-span-full" />
        </div>
        <div class="flex my-2">
            <BaseTheButton title="افزودن آدرس" class="btn-primary " />
        </div>
    </div>
</template>

<script setup lang="ts">
const receiver = ref(true);
const city = ref("");
const state = ref("");
</script>

<style scoped></style>
