<template>
    <div>
        <div v-if="user?.personal_info.is_completed">
        اطلاعات شما کامل شده است.
    </div>
    <div v-else>
       لطفا اطلاعات خود را کامل کنید.
    </div>
    </div>
</template>

<script setup lang="ts">
import { useAuth } from '~~/store/userAuth';
const user = computed(() => useAuth().currentUser);

definePageMeta({
    layout: "profile",
});
</script>
