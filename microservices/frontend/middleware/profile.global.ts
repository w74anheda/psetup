export default defineNuxtRouteMiddleware((to, from) => {
    if (to.path.toLocaleLowerCase().startsWith("/profile")) {
        var token = localStorage.getItem("auth");
        if (!token) {
            return navigateTo("/auth/login?returnTo=" + to.path);
        }
    }
});
