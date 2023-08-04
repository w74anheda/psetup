<template>
    <div>
        <div class="my-5">
            <BaseTheSeparator title="دستگاه فعلی من" textColor="text-success" separatorColor="bg-success"/>
            <div v-if="currentSession"
                class="relative shadow-md bg-light-blue p-3 w-72 rounded-2xl">
                <div
                    class="absolute left-2 -top-4 bg-primary text-white p-2 rounded-full">
                    <Icon name="ic:outline-desktop-windows" size="20" />
                </div>
                <div class="border-secondary flex flex-col gap-1">
                    <span class="text-16 font-IRANSans_Bold text-darker-gray">{{
                        currentSession?.os }}
                        / {{ currentSession?.browser }}</span>
                    <span class="text-dark-gray">{{ currentSession?.ip_address
                    }}</span>
                    <span class="text-dark-gray">{{ getPersianDate(new
                        Date(currentSession?.created_at!),
                        'y/M/dd') }}</span>
                </div>
            </div>
        </div>
        <BaseTheSeparator title="دستگاه های فعال" />
        <div class="grid xl:grid-cols-3 lg:grid-cols-2 gap-3 my-5">
            <div class="relative shadow-md bg-light-blue my-2 p-3 rounded-2xl"
                v-for="item in sessions" :key="item.id">
                <div class="absolute left-2 -top-4  text-white p-2 rounded-full"
                    :class="[item.os.toLowerCase().includes('windows') ? 'bg-primary' : 'bg-success']">
                    <Icon name="ic:outline-desktop-windows" size="20"
                        v-if="item.os.toLowerCase().includes('windows')" />
                    <Icon name="ic:outline-phone-android" size="20" v-else />
                </div>
                <div class="border-secondary flex flex-col gap-1">
                    <span class="text-16 font-IRANSans_Bold text-darker-gray">{{
                        item.os }}
                        / {{ item.browser }}</span>
                    <span class="text-dark-gray">{{ item.ip_address }}</span>
                    <span class="text-dark-gray">{{ getPersianDate(new
                        Date(item.created_at),
                        'y/M/dd') }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { getAllSessions } from '~~/services/user/sessions';
import { Session } from '~~/models/user/sessions';

definePageMeta({
    layout: "profile"
})
const sessions: Ref<Session[] | null> = ref([]);
const currentSession: Ref<Session | undefined> = ref(undefined);

onMounted(async () => {
    const res = await getAllSessions();
    if (res.status === 200) {
        //@ts-ignore
        sessions.value = res.data.sessions;
        currentSession.value = res.data.sessions.find(f => f.current);
    }
})
</script>

<style scoped></style>