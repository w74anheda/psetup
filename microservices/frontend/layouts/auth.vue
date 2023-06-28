<template>
    <div class="w-full h-screen flex items-center justify-center">
        <IconsAuthBg />
        <div class="w-[300px] lg:w-[450px] sm:w-1/2 rounded-xl shadow-lg bg-white z-10">
            <Transition name="fade" mode="out-in">
                <NuxtLink to="/auth/login"
                    class="flex justify-end items-center my-2 text-primary"
                    v-if="$route.name?.toString().split('-')[1] === 'verify'">
                    <span class="text-12">ویرایش شماره موبایل</span>
                    <Icon name="ri:arrow-drop-left-line" size="40" />
                </NuxtLink>
            </Transition>

            <div class="flex font-bold justify-center mt-6">
                <img class="h-20 w-20 mb-3" src="/images/bmw-logo.png" />
            </div>
            <h1 class="text-2xl text-center text-darker-gray mb-1 font-semibold">
                سلام دوست عزیز!
            </h1>
            <p class="text-center text-xs text-dark-gray">به سایت ما خوش آمدی، بوس
            </p>
            <slot />
        </div>
    </div>
</template>
