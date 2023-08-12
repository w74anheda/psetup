<template>
    <div class="flex items-start w-full">
        <div
            class="text-dark-1 py-5 dark:text-secondary shadow-2xl h-screen md:w-64 w-20">
            <ul class="px-5">
                <li class="flex md:flex-row flex-col items-center md:text-14 text-12 py-2 font-IRANSans_Medium" v-for="n in 10">
                    <Icon name="ic:outline-home" class="ml-1" size="20" />
                    <span>پیشخوان</span>
                </li>
            </ul>
        </div>
        <div class="w-full p-5 h-10 text-dark-1 dark:text-secondary">
            <div class="flex justify-between items-center bg-transparent">
                <div class="text-20 font-IRANSans_Medium">
                    سایت
                </div>
                <div class="flex items-center gap-8">
                    <v-text-field dir="rtl" class="w-72 relative" density="compact"
                        variant="outlined" placeholder="دنبال چی میگردی؟" single-line
                        hide-details>
                        <Icon name="ic:outline-search" size="20"
                            class="absolute left-2 top-3" />
                    </v-text-field>
                    <v-divider vertical></v-divider>
                    <Icon name="ic:outline-notifications" size="20" />
                    <Icon @click="changeTheme" name="ic:outline-brightness-medium"
                        size="20" />
                    <Icon name="ic:outline-power-settings-new" size="20" />
                </div>
            </div>
            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
onMounted(() => {
    const themeData = localStorage.getItem("theme");
    if (themeData && themeData === "dark") {
        document.documentElement.classList.add("dark");
        return;
    }
});
const changeTheme = () => {
    if (!document.documentElement.classList.contains("dark")) {
        localStorage.setItem("theme", "dark");
        document.documentElement.classList.add("dark");
    } else {
        localStorage.setItem("theme", "light");
        document.documentElement.classList.remove("dark");
    }
};
</script>

<style>
body {
    @apply bg-gradient-to-l dark:from-dark-2 dark:to-dark-3 from-secondary to-light-gray;
}
</style>
