import { useAuth } from "~~/store/userAuth";

export default defineNuxtPlugin((nuxtApp) => {
    const authData = useAuth();
    authData.setCurrentUser();
});