export const useLoading = defineStore('loading', () => {
    const isLoading = ref(false);

    const handleLoading = (payload: boolean) => {
        isLoading.value = payload;
    }
    return { isLoading, handleLoading }
})