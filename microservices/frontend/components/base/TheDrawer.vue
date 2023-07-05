<template>
    <Transition :duration="100" @enter="onEnterDrawer" @leave="onLeaveDrawer">
        <div @click.self="useDrawer().handleDrawer(false)" v-if="drawer"
            class="main-drawer">
            <div class="drawer">
                <div class="border-b-secondary border-b pb-5">
                    <Icon name="ri:close-line" size="30"
                        @click="useDrawer().handleDrawer(false)"
                        class="float-left text-darker-gray cursor-pointer" />
                    <BaseTheInput name="search" label=""
                        placeholder="دنبال چی میگردی؟" />
                </div>
                <ul class="overflow-auto h-[90%] py-3">
                    <li class="my-3 flex items-center gap-3 text-16 font-IRANSans_Medium text-darker-gray"
                        v-for="n in 5">
                        <Icon name="ri:apple-line" size="25" /> {{ n }}
                    </li>
                </ul>
            </div>
        </div>
    </Transition>
</template>

<script setup lang="ts">
import { useDrawer } from "~~/store/base/drawer";
const drawer = computed(() => useDrawer().drawer);

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
.main-drawer {
    @apply bg-darker-gray/30 h-full w-full fixed top-0 right-0 z-0 md:hidden block;
}

.drawer {
    @apply fixed top-0 -right-80 h-full w-3/5 bg-white md:hidden block z-10 shadow-xl p-3;
}
</style>
