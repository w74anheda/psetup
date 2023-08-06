<template>
    <div>
        <div class="my-5" v-if="currentSession">
            <BaseTheSeparator title="دستگاه فعلی من" textColor="text-success"
                separatorColor="bg-success" />
            <div class="relative shadow-md bg-light-blue p-3 w-72 rounded-2xl mt-7">
                <div class="absolute left-2 -top-4  text-white p-2 rounded-full"
                    :class="[currentSession.os.toLowerCase().includes('windows') ? 'bg-primary' : 'bg-success']">
                    <Icon name="ic:outline-desktop-windows" size="20"
                        v-if="currentSession.os.toLowerCase().includes('windows')" />
                    <Icon name="ic:outline-phone-android" size="20" v-else />
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
                <span class="flex gap-2 items-center mt-2">
                    <span class="relative flex h-3 w-3">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary/75"></span>
                        <span
                            class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                    </span>
                    <span
                        class="text-12 text-primary font-IRANSans_Medium">آنلاین</span>
                </span>
            </div>
        </div>
        <div v-if="sessions?.length">
            <BaseTheSeparator title="دستگاه های فعال" />
            <div class="flex w-full justify-center my-7">
                <span @click="revokeAllUserSessions"
                    class="text-error font-IRANSans_Medium cursor-pointer">حذف تمامی
                    دستگاه ها</span>
            </div>
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
                        <div class="flex items-center justify-between">
                            <span class="text-dark-gray">{{ getPersianDate(new
                                Date(item.created_at),
                                'y/M/dd') }}</span>
                            <Icon @click="revokeUserSession(item.id)"
                                name="ri:delete-bin-6-line" size="16"
                                class="text-error cursor-pointer ml-1" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { getAllSessions, revokeSession } from '~~/services/user/sessions';
import { Session } from '~~/models/user/sessions';
import { useNotify } from '~~/store/notify';
import { revokeAllSessions } from '~~/services/user/sessions';

definePageMeta({
    layout: "profile"
})
const sessions: Ref<Session[] | null> = ref([]);
const currentSession: Ref<Session | undefined> = ref(undefined);

onMounted(() => {
    getSessions();
})

const getSessions = async () => {
    const res = await getAllSessions();
    if (res.status === 200) {
        //@ts-ignore
        sessions.value = res.data.sessions.filter(f => f.current === false);
        currentSession.value = res.data.sessions.find(f => f.current);
    }
}

const revokeUserSession = async (id: string) => {
    const res = await revokeSession(id);
    if (res.status === 202) {
        await getSessions();
        useNotify().notify("دستگاه با موفقیت حذف شد.", "success");
        return;
    }
    useNotify().notify("مشکلی پیش آمده، دوباره امتحان کنید.", "error");
}

const revokeAllUserSessions = async () => {
    const res = await revokeAllSessions();
    if (res.status === 202) {
        await getSessions();
        useNotify().notify("تمامی دستگاه ها با موفقیت حذف شدند.", "success");
        return;
    }
    useNotify().notify("مشکلی پیش آمده، دوباره امتحان کنید.", "error");
}
</script>

<style scoped></style>