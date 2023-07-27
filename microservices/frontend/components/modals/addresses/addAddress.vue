<template>
    <Transition name="alert" mode="out-in">
        <Form v-if="tab === 0" :validation-schema="addressFormSchema"
            v-slot="{ meta }" class="md:col-span-1 col-span-full">
            <div class="md:flex block md:gap-2">
                <BaseTheAutocomplete label="استان" :items="states" name="state"
                    v-model="addressFormData.state"
                    @setValue="(state) => getCityByState(state)" />
                <transition name="fade">
                    <BaseTheAutocomplete v-if="addressFormData.state" label="شهر"
                        :items="cities" name="city" v-model="addressFormData.city"
                        @setValue="(city) => cityId = city" />
                </transition>
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
    <BaseTheButton @click="addUserAddress" v-if="coordinate" title="ثبت آدرس"
        class="btn-primary mt-5" />
</template>

<script setup lang="ts">
import { useAddress } from '~~/store/addresses';
import { addAddress } from '~~/services/address'
import * as Yup from 'yup';
import { useModal } from "~~/store/base/modal";
import { useNotify } from '~~/store/notify';


const emit = defineEmits(['mapModal']);
const states = computed(() => useAddress().states);
const cities = ref();
const tab = ref(0);
const coordinate = ref(null);
const cityId = ref(null);

const addressFormData = reactive({
    state: '',
    city: '',
    houseNumber: '',
    unitNumber: '',
    postalCode: '',
    postalAddress: '',
})

const addressFormSchema = Yup.object().shape({
    state: Yup.string().required().oneOf(states.value.map(state => { return state.name })).label("استان"),
    city: Yup.string().required().oneOf(useAddress().cities.map(city => { return city.name })).label('شهر'),
    houseNumber: Yup.string().required().label('شماره پلاک'),
    unitNumber: Yup.string().required().label('شماره واحد'),
    postalCode: Yup.string().required().length(10).label('کد پستی'),
    postalAddress: Yup.string().required().label('آدرس پستی'),
})

onMounted(() => {
    emit('mapModal', false);
})

const getCityByState = async (state: number) => {
    cities.value = useAddress().cities.filter(city => city.state_id === state);
    addressFormData.city = '';
}
const addUserAddress = async () => {
    //@ts-ignore
    const res = await addAddress({
        city_id: cityId.value!,
        full_address: addressFormData.postalAddress,
        house_number: Number(addressFormData.houseNumber),
        unit_number: Number(addressFormData.unitNumber),
        postalcode: addressFormData.postalCode,
        latitude: coordinate.value![0],
        longitude: coordinate.value![1],
    });
    if (res.status === 201) {
        await useAddress().getUserAddresses();
        useNotify().notify("آدرس با موفقیت اضافه شد.", "success")
    } else {
        useNotify().notify("مشکلی وجود دارد، دوباره امتحان کنید.", "error")
    }
    useModal().modalHandler(false);

}
</script>
