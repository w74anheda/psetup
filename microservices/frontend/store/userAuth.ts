import { IVerifyDTO } from "~~/models/auth/verify";
import { userLogin } from "~~/services/auth/userLogin";
import { Verification } from "~/models/auth/login";

export const useAuth = defineStore("auth", () => {
    const loginResult: Ref<Verification | null> = ref(null);
    const verifyResult: Ref<IVerifyDTO | null> = ref(null);
    const router = useRouter();


    const userLoginData = async (phone: string) => {
        const res = await userLogin(phone);
        if (res.status === 200)
            return res;
    };

    const refreshUserLoginData = async () => {
        const currentPhone = (router.currentRoute.value.query.phone)
        if (currentPhone) {
            const res = await userLoginData(currentPhone.toString());
            if (res?.status === 200) {
                loginResult.value = res.verification;
                return res;
            }
        }
        router.back();
    }
    return { loginResult, userLoginData, refreshUserLoginData };
});
