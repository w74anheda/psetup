<template>
    <div class="container">
        <BodyTheHeader />
        <main class="grid grid-cols-3 gap-7 my-7">
            <div
                class="md:col-span-1 col-span-full bg-white sticky top-0 border-secondary border rounded-xl pt-3">
                <div class="flex items-center justify-between pb-3 px-3">
                    <div class="flex items-center gap-3">
                        <BaseTheAvatar />
                        <div class="flex flex-col">
                            <span class="text-darker-gray">{{ user?.first_name }} {{
                                user?.last_name }}</span>
                            <span class="text-secondary">{{ user?.phone }}</span>
                        </div>
                    </div>
                    <Icon name="ri:edit-2-line" size="20" class="text-primary" />
                </div>
                <ul class="pt-2">
                    <NuxtLink :to="`/profile/${item.link}`"
                        v-for="item in profileItems">
                        <li @click="selectedItem = item.id"
                            class="flex items-center relative gap-2 p-4 text-darker-gray border-t rounded border-light-gray hover:bg-secondary"
                            :class="{ 'font-IRANSans_Bold': selectedItem === item.id }">
                            <span v-if="selectedItem === item.id"
                                class="bg-primary w-1 h-10 rounded-lg absolute right-0"></span>
                            <Icon :name="item.icon" size="25" />
                            <span>{{ item.title }}</span>
                        </li>
                    </NuxtLink>
                </ul>
            </div>
            <div
                class="md:col-span-2 col-span-full bg-white sticky top-0 h-1/3 border-secondary border rounded-xl p-3">
                <ClientOnly  v-if="loading">
                    <span>Loadiiiiiiiiing</span>
                </ClientOnly>
                <slot v-else />
            </div>
        </main>
        <ClientOnly>
            <Teleport to="body">
                <BaseTheDrawer />
            </Teleport>
        </ClientOnly>
    </div>
</template>

<script setup lang="ts">
import { useLoading } from '~~/store/base/loading';
import { useAuth } from '~~/store/userAuth';

const profileItems = ref([
    { id: 0, title: 'پروفایل', icon: 'ic:baseline-person-outline', link: '' },
    { id: 1, title: 'ویرایش اطلاعات کاربری', icon: 'ic:outline-mode-edit-outline', link: 'personal-info' },
    { id: 2, title: 'خروج', icon: 'ic:outline-exit-to-app', link: '' }
]);
const selectedItem = ref(0);
const user = computed(() => useAuth().currentUser);
const loading = computed(() => useLoading().isLoading)
</script>
