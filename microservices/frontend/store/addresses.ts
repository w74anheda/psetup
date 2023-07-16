import { IAddress } from "models/address";
import { getAddresses } from "~~/services/address";

export const useAddress = defineStore('address', () => {
    const userAddresses = ref<IAddress>();

    const getUserAddresses = async () => {
        const res = await getAddresses();
        if (res.status === 200) {
            userAddresses.value = res.data;
        }
    }

    return { userAddresses, getUserAddresses }
})