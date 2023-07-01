import { IVerifyDTO } from "~~/models/auth/verify";
import { userLogin } from "~~/services/auth/userLogin";
import { Verification } from "~/models/auth/login"

export const useAuth = defineStore('auth', () => {
    const loginResult: Ref<Verification | null> = ref(null);
    const verifyResult: Ref<IVerifyDTO | null> = ref(null);

    return { loginResult }
})