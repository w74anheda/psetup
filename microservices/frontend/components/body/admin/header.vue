<template>
    <div class="flex justify-between items-center">
        <div class="md:flex hidden items-center gap-2 p-2">
            <v-avatar size="42px"
                class="border-2 rounded-full border-primary shadow-lg">
                <v-img alt="Avatar"
                    src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460"></v-img>
            </v-avatar>
            نام ادمین
        </div>
        <Icon name="ic:outline-search" class="md:!hidden !block" size="20" />
        <div class="flex relative items-center gap-8">
            <v-text-field dir="rtl" class="w-72 relative md:!block !hidden"
                density="compact" variant="outlined" placeholder="دنبال چی میگردی؟"
                single-line hide-details>
                <Icon name="ic:outline-search" size="20"
                    class="absolute left-2 top-3" />
            </v-text-field>
            <v-divider class="md:!block !hidden" vertical></v-divider>
            <Icon name="ic:outline-notifications" size="20" />
            <Icon @click="changeTheme" name="ic:outline-brightness-medium"
                size="20" />
            <Icon name="ic:outline-power-settings-new" size="20" />
        </div>
    </div>
</template>

<script setup lang="ts">
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

<style scoped></style>