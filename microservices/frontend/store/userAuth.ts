import { userLogin } from "~~/services/auth/userLogin";
import { Verification } from "~~/models/auth/login";
import { IVerifyDTO } from "~~/models/auth/verify";
import { IUser } from "~~/models/user/user";
import { getCurrentUserData } from "~~/services/user/user";

export const useAuth = defineStore("auth", () => {
    const phoneNumber = ref<string | null>(null);
    const loginResult: Ref<Verification | null> = ref(null);
    const verifyResult: Ref<IVerifyDTO | null> = ref(null);
    const currentUser: Ref<IUser | null> = ref(null);

    const isLogin = computed(() => verifyResult.value !== null);

    const getUserLoginData = async (phone: string) => {
        const res = await userLogin(phone);
        if (res.status === 200) return res;
    };

    const setCurrentUser = async () => {
        const localStorageAuthData = localStorage.getItem("auth");
        if (!localStorageAuthData) {
            return;
        }
        const loginData = JSON.parse(localStorageAuthData);
        verifyResult.value = loginData;
        const res = await getCurrentUserData();
        if (res.status === 200) {
            currentUser.value = res;
            return;
        }
        verifyResult.value = null;
        localStorage.removeItem("auth");

    };
    return { loginResult, verifyResult, currentUser, isLogin, phoneNumber, getUserLoginData, setCurrentUser };
});
