<template>
    <div class="relative flex flex-col w-full">
        <label class="form-label" v-if="label">{{ label }}</label>
        <input :name="name" :value="modelValue"
            :class="['form-control', { 'border-danger': errorMessage }]"
            :placeholder="placeholder" @focusout="showResults = false"
            @focus="focuseDada" @input="handleInputChange"
            @keydown.escape="closeResults" @keydown.down="highlightNext"
            @keydown.up="highlightPrevious" @keydown.enter="selectHighlighted" />

        <Transition name="fade">
            <ul class="bg-light-blue text-darker-gray p-2 rounded shadow h-48 overflow-auto absolute z-10 w-full top-20"
                v-if="showResults">
                <li class="py-1 cursor-pointer"
                    v-for="(item, index) in filteredItems" :key="index"
                    :class="{ highlighted: highlightedIndex === index }"
                    @mousedown="selectItem(item)">
                    {{ item }}
                </li>
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
const highlightedIndex = ref(-1);
const emit = defineEmits(["update:modelValue"]);

var filteredItems = reactive([]);

watch(
    () => props.modelValue,
    (val) => setValue(val)
);

const focuseDada = () => {
    filteredItems = props.items;
    showResults.value = true;
}

const handleInputChange = (e) => {
    filteredItems.length = 0;
    emit("update:modelValue", e.target.value);
    if (e.target.value !== "") {
        handleChange(e);
        filteredItems.push(
            ...props.items.filter((item) =>
                item.toLowerCase().includes(e.target.value.toLowerCase())
            )
        );
    }
    showResults.value = filteredItems.length > 0;
    highlightedIndex.value = -1;
};

const closeResults = () => {
    showResults.value = false;
};

const highlightNext = () => {
    if (highlightedIndex.value < filteredItems.length - 1) {
        highlightedIndex.value++;
    }
};

const highlightPrevious = () => {
    if (highlightedIndex.value > 0) {
        highlightedIndex.value--;
    }
};

const selectHighlighted = () => {
    if (
        highlightedIndex.value >= 0 &&
        highlightedIndex.value < filteredItems.length
    ) {
        selectItem(filteredItems[highlightedIndex.value]);
    }
};

const selectItem = (item) => {
    emit("update:modelValue", item);
    showResults.value = false;
};
</script>
