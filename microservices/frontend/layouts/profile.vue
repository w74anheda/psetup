<template>
    <div class="container">
        <BodyTheHeader />
        <main class="grid grid-cols-3 gap-5 my-7 relative">
            <div
                class="md:col-span-1 col-span-full bg-white sticky top-0 border-secondary border rounded-xl pt-3">
                <div class="flex items-center relative justify-between pb-3 px-3">
                    <div class="flex items-center gap-3">
                        <BaseTheAvatar />
                        <div class="flex flex-col">
                            <span class="text-darker-gray">{{ user?.first_name }} {{
                                user?.last_name }}</span>
                            <span class="text-secondary">{{ user?.phone }}</span>
                        </div>
                    </div>
                    <nuxt-link to="/profile/personal-info">
                        <Icon name="ri:edit-2-line" size="20"
                            class="text-primary" />
                    </nuxt-link>
                </div>
                <ul class="pt-2">
                    <NuxtLink :to="`/profile/${item.link}`"
                        v-for="item in profileItems">
                        <li class="flex items-center relative gap-2 p-4 text-darker-gray border-t rounded border-light-gray hover:bg-secondary"
                            :class="{ 'font-IRANSans_Bold': selectedItem === item.link }">
                            <span v-if="selectedItem === item.link"
                                class="bg-primary w-1 h-10 rounded-lg absolute right-0"></span>
                            <Icon :name="item.icon" size="25" />
                            <span>{{ item.title }}</span>
                        </li>
                    </NuxtLink>
                </ul>
            </div>
            <div
                class="md:col-span-2 col-span-full relative bg-white border-secondary border rounded-xl px-3">
                <slot />
            </div>
            <ClientOnly>
                <Transition name="fade" mode="out-in">
                    <BaseTheLoading v-if="loading" />
                </Transition>
            </ClientOnly>
        </main>
    </div>
</template>

<script setup lang="ts">
import { useLoading } from "~~/store/base/loading";
import { useAuth } from "~~/store/userAuth";

const route = useRoute();
const profileItems = ref([
    { id: 0, title: "پیشخوان", icon: "ic:baseline-person-outline", link: "" },
    {
        id: 1,
        title: "آدرس ها",
        icon: "ic:outline-map",
        link: "addresses",
    },
    {
        id: 2,
        title: "ویرایش اطلاعات کاربری",
        icon: "ic:outline-mode-edit-outline",
        link: "personal-info",
    },
    {
        id: 3,
        title: "دستگاه ها",
        icon: "ic:round-devices",
        link: "sessions",
    },
    { id: 4, title: "خروج", icon: "ic:outline-exit-to-app", link: "?logout" },
]);
const selectedItem = computed(() => route.name?.toString().slice(8));
const user = computed(() => useAuth().currentUser);
const loading = computed(() => useLoading().isLoading);
</script>
