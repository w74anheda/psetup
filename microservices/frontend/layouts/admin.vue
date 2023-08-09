<template>
    <div class="bg-transparent">
        <div class="container">
            <div class="flex">
                THEME
                <Icon @click="changeTheme" name="ic:outline-brightness-medium"
                    size="20" />
            </div>

        </div>
    </div>
    <div class="flex my-5 items-start">
        <div class="text-light-gray w-96 md:flex hidden">
            <ul class="px-5">
                <li class="py-2" v-for="n in 10">
                    <Icon name="ic:outline-home" class="ml-1" size="20" />
                    پیشخوان
                </li>
            </ul>
        </div>
        <div class="w-full h-10">
            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
onMounted(() => {
    const themeData = localStorage.getItem('theme');
    if (themeData && themeData === "dark") {
        document.documentElement.classList.add('dark');
        return;
    }
})
const changeTheme = () => {
    if (!document.documentElement.classList.contains('dark')) {
        localStorage.setItem("theme", "dark");
        document.documentElement.classList.add('dark');
    } else {
        localStorage.setItem("theme", "light");
        document.documentElement.classList.remove('dark');
    }
}
</script>

<style>
body {
    @apply bg-gradient-to-l dark:from-[#243B55] dark:to-[#141E30] from-[#304352] to-[#d7d2cc];
}
</style>