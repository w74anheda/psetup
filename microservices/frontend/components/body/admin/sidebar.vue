<template>
    <div class="sidebar">
        <div class="md:hidden px-3 block pb-8 items-center gap-2">
            <v-avatar size="42px"
                class="border-2 rounded-full border-primary shadow-lg">
                <v-img alt="Avatar"
                    src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460"></v-img>
            </v-avatar>
        </div>
        <ul>
            <li class="sidebar-items"
                @click="selectedSideItem = item.id, $router.push('/admin/' + item.link)"
                :class="{ 'dark:!bg-dark-3 !bg-light-blue': selectedSideItem === item.id }"
                v-for="item in sidebarItems" :key="item.id">
                <Icon :name="item.icon" class="mx-4" size="20" />
                <span class="md:hidden block" v-if="selectedSideItem === item.id">{{
                    item.name }}</span>
                <span class="md:block hidden">{{ item.name }}</span>
            </li>
        </ul>
    </div>
</template>

<script setup lang="ts">
const sidebarItems = ref([
    { id: 0, name: "پیشخوان", icon: "ic:outline-home", link: "" },
    { id: 1, name: "نقش ها", icon: "ic:outline-supervisor-account", link: "roles" },
    { id: 2, name: "دسترسی ها", icon: "ic:outline-lock-open", link: "permissions" },
])
const selectedSideItem = ref(0);
</script>

<style scoped>
.sidebar {
    @apply text-dark-1 py-5 dark:text-secondary shadow-2xl h-screen md:w-64 w-20;
}

.sidebar-items {
    @apply flex transition-all ease-linear duration-200 md:flex-row flex-col cursor-pointer items-center md:text-14 text-12 py-2 font-IRANSans_Medium;
}
</style>