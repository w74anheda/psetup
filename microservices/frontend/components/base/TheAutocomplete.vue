<template>
    <div class="relative flex flex-col w-full">
        <label class="form-label" v-if="label">{{ label }}</label>
        <input :name="name" :value="modelValue" autocomplete="off"
            :class="['form-control', { 'border-danger': errorMessage }]"
            :placeholder="placeholder" @focusout="showResults = false"
            @focus="focusData" @input="handleInputChange"
            @keydown.escape="closeResults" />
        <Transition name="fade">
            <ul class="bg-light-blue text-darker-gray p-2 rounded shadow max-h-48 overflow-auto absolute z-10 w-full top-20"
                v-if="showResults">
                <li v-if="filteredItems.length > 0" class="py-1 cursor-pointer"
                    v-for="item in filteredItems" :key="item.id"
                    @mousedown="selectItem(item.name, item.id)">
                    {{ item.name }}
                </li>
                <li class="text-error text-12 text-center font-IRANSans_Medium"
                    v-else>موردی یافت نشد.</li>
            </ul>
        </Transition>

        <slot />
        <span v-if="errorMessage" class="input-error-message">{{ errorMessage
        }}</span>
    </div>
</template>

<script setup>
const props = defineProps({
    label: {
        default: "نام",
        type: String,
    },
    items: {
        type: Array,
        required: true,
    },
    placeholder: {
        default: "",
        type: String,
    },
    modelValue: {
        default: "",
    },
    name: {
        type: String,
        required: true,
    },
});
const {
    errorMessage,
    handleChange,
    value: inputValue,
    setValue,
} = useField(props.name, undefined, {
    initialValue: props.modelValue,
});
const showResults = ref(false);
const emit = defineEmits(["update:modelValue", "setValue"]);
const filteredItems = ref([]);

watch(
    () => props.modelValue,
    (val) => setValue(val)
);

const focusData = () => {
    filteredItems.value = props.items;
    showResults.value = true;
}

const handleInputChange = (e) => {
    filteredItems.value = [];
    emit("update:modelValue", e.target.value);
    handleChange(e);
    filteredItems.value.push(
        ...props.items.filter(item => item.name.toLowerCase().includes(e.target.value.toLowerCase()))
    )
};

const closeResults = () => {
    showResults.value = false;
};

const selectItem = (name, id) => {
    emit("update:modelValue", name);
    emit("setValue", id);
    showResults.value = false;
};
</script>
