<template>
    <div class="flex flex-col items-center">
        <label class="form-label pt-2">کد {{ count }} رقمی یکبار مصرف</label>
        <div dir="ltr" class="relative flex justify-between w-full mt-2">
            <input @input="maxLengthNumber" @keyup="(e) => inputHandler(e, n)"
                v-for="n in count" type="number" maxlength="1"
                class="form-control text-center lg:w-14 sm:w-12 w-10"
                :placeholder="placeholder" />
        </div>
    </div>
</template>

<script setup>
defineProps({
    placeholder: {
        default: "x",
        type: String,
    },
    count: {
        default: 5,
        type: Number,
    },
});

const emit = defineEmits(["otpInputValue"]);

const inputHandler = (e, index) => {
    emit("otpInputValue", e.target.value, index);
    if (e.target.value.length >= e.target.maxLength) {
        var nextInput = e.target.nextElementSibling;
        if (nextInput !== null && nextInput.tagName === "INPUT") {
            nextInput.focus();
        }
    }
    if (e.keyCode === 8) {
        var prevInput = e.target.previousElementSibling;
        if (prevInput && prevInput.tagName === "INPUT") {
            prevInput.focus();
        }
    }
};
const maxLengthNumber = (e) => {
    if (e.target.value.length > e.target.maxLength)
        e.target.value = e.target.value.slice(0, e.target.maxLength);
};
</script>
