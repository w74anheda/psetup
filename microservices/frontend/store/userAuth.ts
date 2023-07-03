import { userLogin } from "~~/services/auth/userLogin";
import { Verification } from "~/models/auth/login";

export const useAuth = defineStore("auth", () => {
    const phoneNumber = ref<string | null>(null);
    const loginResult: Ref<Verification | null> = ref(null);

    const getUserLoginData = async (phone: string) => {
        const res = await userLogin(phone);
        if (res.status === 200)
            return res;
    };
    return { loginResult, phoneNumber, getUserLoginData };
});
