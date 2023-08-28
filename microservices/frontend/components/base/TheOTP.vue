<template>
    <div dir="ltr" class="otp-inputs">
        <input ref="input" @input="maxLengthNumber"
            @keyup="(e) => inputHandler(e, index)" v-for="(n, index) in count"
            type="number" maxlength="1"
            class="form-control text-center lg:w-16 w-12"
            :placeholder="placeholder" />
    </div>
</template>

<script setup>

const props = defineProps({
    placeholder: {
        default: "x",
        type: String,
    },
    count: {
        default: 5,
        type: Number,
    },
});
const input = ref(null);
const emit = defineEmits(["otpInputValue"]);

const inputHandler = (e, index) => {
    emit("otpInputValue", e.target.value, index);
    if (e.target.value.length >= e.target.maxLength) {
        if (input.value[index + 1] && input.value[index + 1].tagName === "INPUT") {
            input.value[index + 1].focus();
        }
    }
    if (e.keyCode === 8) {
        if (input.value[index - 1] && input.value[index - 1].tagName === "INPUT") {
            input.value[index - 1].focus();
            input.value[index - 1].select();
        }
    }
};
const maxLengthNumber = (e) => {
    if (e.target.value.length > e.target.maxLength)
        e.target.value = e.target.value.slice(0, e.target.maxLength);
};
</script>

<style scoped>
.otp-inputs{
    @apply relative flex justify-between w-full mt-2;
}

::-moz-selection {
    /* Code for Firefox */
    @apply bg-transparent;
}

::selection {
    @apply bg-transparent;
}
</style>