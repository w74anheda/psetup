export const useDrawer = defineStore("drawer", () => {
    const drawer = ref(false);

    const handleDrawer = (payload: boolean) => {
        drawer.value = payload;
    }
    return { drawer, handleDrawer }
})