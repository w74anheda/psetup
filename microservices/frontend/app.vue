<template>
  <NuxtLayout>
    <NuxtLoadingIndicator :height="5"/>
    <NuxtPage />
  </NuxtLayout>

  <ClientOnly>
    <div class="fixed top-20 flex flex-col w-full z-10 items-center">
      <TransitionGroup name="alert">
        <BaseThePopover @closePopover="notfiy.removeNotification(notify)"
          :type="notify.type" :message="notify.message"
          v-for="notify in notfiy.notifications"
          v-if="notfiy.notifications.length > 0" />
      </TransitionGroup>
    </div>
  </ClientOnly>
</template>

<script setup lang="ts">
import { useNotify } from "~~/store/notify";

const notfiy = useNotify();
</script>
