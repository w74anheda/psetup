import { IVerifyDTO } from "models/auth/verify";
import { ILoginDTO } from "~/models/auth/login"

export const useAuth = defineStore('auth', () => {
    const loginResult: Ref<ILoginDTO | null> = ref(null);
    const verifyResult: Ref<IVerifyDTO | null> = ref(null);

    return { loginResult }
})