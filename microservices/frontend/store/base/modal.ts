export const useModal = defineStore("modal", () => {
    const modal = ref(false);

    const modalHandler = (payload: boolean) => {
        modal.value = payload;
    }
    return { modal, modalHandler }
})