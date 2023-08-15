<template>
    <div class="flex items-start w-full">
        <BodyAdminSidebar />
        <div class="w-full p-5 text-dark-1 dark:text-secondary">
            <BodyAdminHeader />
            <div class="my-10 p-3 bg-white/50 dark:bg-dark-2 rounded-lg">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
onMounted(() => {
    const themeData = localStorage.getItem("theme");
    if (themeData && themeData === "dark") {
        document.documentElement.classList.add("dark");
    }
});
</script>

<style>
body {
    @apply bg-gradient-to-l dark:from-dark-2 dark:to-dark-3 from-secondary to-light-gray;
}
</style>
