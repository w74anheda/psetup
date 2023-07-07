<template>
    <Transition name="fade">
        <div @click.self="useModal().modalHandler(true)" class="main-modal"
            v-if="!modal">
            <div class="modal">
                <div>ModalHEADER</div>
                <div>MOdalBody</div>
                <div>Modalfooter</div>
            </div>
        </div>
    </Transition>
</template>

<script setup lang="ts">
import { useModal } from "~~/store/base/modal"
const modal = computed(() => useModal().modal)
const { $anime } = useNuxtApp();

const onLeaveDrawer = () => {
    $anime({ targets: ".drawer", right: "-20rem", duration: 1000 });
    $anime({
        targets: ".main-drawer",
        opacity: [1, 0],
        easing: "linear",
        duration: 400,
    });
};
const onEnterDrawer = () => {
    $anime({
        targets: ".drawer",
        right: 0,
        easing: "easeInOutQuad",
        duration: 500,
    });
    $anime({
        targets: ".main-drawer",
        opacity: [0, 1],
        easing: "linear",
        duration: 300,
    });
};
</script>

<style scoped>
.main-modal {
    @apply flex justify-center items-center bg-darker-gray/30 h-full w-full fixed top-0 right-0 z-0;
}

.modal {
    @apply w-1/3 bg-white shadow-xl p-3;
}
</style>
