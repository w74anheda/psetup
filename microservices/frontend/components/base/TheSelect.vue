<template>
    <div class="form-control cursor-pointer relative"
        v-click-outside="hiddenOptions" @click="showOptions = !showOptions">
        <span v-if="modelValue">{{ modelValue }}</span>
        <span v-else class="text-dark-gray">{{ placeholder }}</span>
        <Icon v-if="icon" name="ri:arrow-drop-down-line" size="30"
            class="absolute left-1 top-0.5 text-primary" />
        <Transition name="select">
            <div v-show="showOptions"
                class="absolute left-0 top-0 z-20 w-full h-44 overflow-auto">
                <ul
                    class="bg-light-blue text-darker-gray p-2 rounded shadow relative">
                    <li @click.stop="selectOption(item)"
                        v-for="(item, index) in options" :key="index" class="py-1">
                        {{ item }}
                    </li>
                </ul>
            </div>
        </Transition>
    </div>
</template>

<script setup>
const props = defineProps({
    options: {
        type: Array,
        required: true
    },
    modelValue: {
        type: String,
        required: true
    },
    placeholder: {
        type: String,
        default: 'یک گزینه انتخاب کنید.'
    },
    icon: {
        type: Boolean,
        default: true
    }

});
const showOptions = ref(false);
const emit = defineEmits(["update:modelValue"]);

const selectOption = (item) => {
    showOptions.value = false;
    emit('update:modelValue', item);
};
const hiddenOptions = () => {
    showOptions.value = false;
};
</script>

<style scoped>
.select-enter-active,
.select-leave-active {
    transition: all 0.5s ease;
}

.select-enter-from,
.select-leave-to {
    opacity: 0;
    transform: translateY(-30px);
}
</style>
