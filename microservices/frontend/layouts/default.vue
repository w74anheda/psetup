<template>
    <div class="container">
        <BodyTheHeader />
        <slot />
        <ClientOnly>
            <Teleport to="body">
                <BaseTheDrawer />
            </Teleport>
        </ClientOnly>
    </div>
</template>

<script setup lang="ts">

</script>
