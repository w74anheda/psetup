<template>
    <div class="loading">
        <Vue3Lottie :width="'50%'" animation-link="/animations/loading-dots.json" />
    </div>
</template>

<style scoped>
.loading {
    position: absolute;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(90, 167, 218, 0.1);
    border-radius: 16px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(3px);
    -webkit-backdrop-filter: blur(3px);
    border: 0;
}
</style>