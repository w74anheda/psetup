import { City, IAddress, State } from "~~/models/address";
import { getAddresses, getCities, getStates } from "~~/services/address";

export const useAddress = defineStore('address', () => {
    const userAddresses: Ref<IAddress[]> = ref([]);
    const states: Ref<State[]> = ref([]);
    const cities: Ref<City[]> = ref([]);

    const getUserAddresses = async () => {
        const res = await getAddresses();
        if (res.status === 200) {
            //@ts-ignore
            userAddresses.value = res.data;
        }
    }
    const getState = async () => {
        const res = await getStates();
        if (res.status === 200) {
            //@ts-ignore
            states.value = res.data;
        }

    }
    const getCity = async () => {
        const res = await getCities();
        if (res.status === 200) {
            //@ts-ignore
            cities.value = res.data;
        }
    }
    return { userAddresses, states, cities, getUserAddresses, getState, getCity }
})