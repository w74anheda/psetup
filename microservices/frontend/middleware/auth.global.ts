import { useAuth } from "~~/store/userAuth";
export default defineNuxtRouteMiddleware((to, from) => {
    if (to.path.toLocaleLowerCase().startsWith("/auth")) {
        var isLogin = useAuth().verifyResult;
        if (isLogin) {
            return navigateTo("/");
        }
    }
});
