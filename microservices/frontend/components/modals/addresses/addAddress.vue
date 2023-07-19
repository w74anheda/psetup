<template>
    <Transition name="alert" mode="out-in">
        <Form v-if="tab === 0" :validation-schema="addressFormSchema"
            v-slot="{ meta }" class="md:col-span-1 col-span-full">
            <div class="md:flex block md:gap-2">
                <BaseTheAutocomplete label="استان"
                    :items="['Apple', 'Banana', 'Cherry', 'Grapes']" name="state"
                    v-model="addressFormData.state" />
                <BaseTheAutocomplete label="شهر"
                    :items="['Apple', 'Banana', 'Cherry', 'Grapes']" name="city"
                    v-model="addressFormData.city" />
            </div>
            <div class="flex gap-2">
                <BaseTheInput type="number" class="!w-16" label="پلاک"
                    name="houseNumber" v-model="addressFormData.houseNumber" />
                <BaseTheInput type="number" class="!w-16" label="واحد"
                    name="unitNumber" v-model="addressFormData.unitNumber" />
                <BaseTheInput type="number" class="md:flex-1 !w-[8.25rem]"
                    label="کد پستی" v-model="addressFormData.postalCode"
                    name="postalCode" />
            </div>
            <BaseTheInput type="textarea" label="آدرس پستی" name="postalAddress"
                v-model="addressFormData.postalAddress" />
            <div class="mt-3">
                <BaseTheButton @click="emit('mapModal', true), tab = 1"
                    title="مرحله بعد"
                    :class="[meta.valid ? 'btn-primary' : 'btn-disabled']" />
            </div>
        </Form>
        <BaseTheMap v-else @SetCoordinate="value => coordinate = value" />
    </Transition>
</template>

<script setup lang="ts">
import * as Yup from 'yup';

const tab = ref(0);
const emit = defineEmits(['mapModal']);

const coordinate = ref(null);
const addressFormData = reactive({
    state: '',
    city: '',
    houseNumber: '',
    unitNumber: '',
    postalCode: '',
    postalAddress: '',
    fullName: ''
})

const addressFormSchema = Yup.object().shape({
    state: Yup.string().required().label('استان'),
    city: Yup.string().required().label('شهر'),
    houseNumber: Yup.string().required().label('شماره پلاک'),
    unitNumber: Yup.string().required().label('شماره واحد'),
    postalCode: Yup.string().required().label('کد پستی'),
    postalAddress: Yup.string().required().label('آدرس پستی'),
})

onMounted(() => {
    emit('mapModal', false);
})
</script>
