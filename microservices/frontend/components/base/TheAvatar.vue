<template>
    <div class="avatar !bg-light-blue shadow-lg">
        <!-- Avatar image -->
        <img class="avatar__image" src="/images/default-user.png" />
    </div>
</template>

<script setup lang="ts">

</script>

<style scoped>
.avatar {
    border-radius: 50%;
    align-items: center;
    display: flex;
    justify-content: center;
    height: 3rem;
    width: 3rem;
}

.avatar__image {
    height: 90%;
    width: 90%;
}
</style>