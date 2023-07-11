export const useDropdown = defineStore('dropdown', () => {
    const dropdown = ref(false);

    const dropdownHandler = (payload: boolean) => {
        dropdown.value = payload;
    }
    return { dropdown, dropdownHandler }
})