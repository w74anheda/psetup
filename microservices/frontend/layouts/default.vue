<template>
    <div class="container">
        <div
            class="flex justify-between items-center lg:py-9 lg:px-14 p-5 bg-white shadow-sm rounded-2xl">
            <div class="lg:hidden block">
                <Icon name="ri:menu-fill" size="25" />
            </div>
            <div class="basis-12">
                <img src="/images/bmw-logo.png" />
            </div>
            <div class="basis-1/2 lg:block hidden">
                <BaseTheInput label="" name="search"
                    placeholder="دنبال چی میگردی؟" />
            </div>
            <div class="flex">
                <BaseTheButton title="سبد خرید"
                    class="text-primary bg-light-blue !px-5 !py-2 hover:bg-primary/70 hover:text-white relative right-2 lg:!flex !hidden">
                    <Icon name="ri:shopping-cart-line" size="20" />
                    <span
                        class="absolute -top-2 -right-2 text-light-blue bg-primary rounded-full px-2">2</span>
                </BaseTheButton>
                <BaseTheButton title="(2 مورد) سبد خرید"
                    class="text-light-blue bg-primary bottom-0 w-full h-12 shadow left-0 !rounded-none !px-5 !py-2 hover:bg-primary/70 hover:text-white fixed lg:!hidden !flex">
                    <Icon name="ri:shopping-cart-line" size="20" />
                </BaseTheButton>
                <ClientOnly>
                    <NuxtLink to="/auth/login" v-if="!useAuth().isLogin">
                        <BaseTheButton title="ورود | ثبت نام"
                            class="btn-primary !px-5 !py-2 relative z-1 lg:!flex !hidden" />
                        <BaseTheButton title=""
                            class="btn-primary !px-5 !py-2 relative z-1 lg:!hidden !flex">
                            <icon name="ri:logout-box-r-line" size="20" />
                        </BaseTheButton>
                    </NuxtLink>
                    <NuxtLink to="/auth/login" v-else>
                        <BaseTheButton :title="user ? `${user.first_name} ${user.last_name}` : 'کاربر عزیز'
                            "
                            class="btn-primary !px-5 !py-2 relative z-1 lg:!flex !hidden" />
                        <BaseTheButton title=""
                            class="btn-primary !px-5 !py-2 relative z-1 lg:!hidden !flex">
                            <icon name="ri:map-pin-user-line" size="20" />
                        </BaseTheButton>
                    </NuxtLink>
                </ClientOnly>
            </div>
        </div>
        <div
            class="lg:flex hidden justify-center gap-10 items-center py-5 w-11/12 mx-auto bg-secondary shadow-sm rounded-br-2xl rounded-bl-2xl">
            <div class="">01</div>
            <div class="">01</div>
            <div class="">01</div>
            <div class="">01</div>
        </div>
        <slot />
    </div>
</template>

<script setup lang="ts">
import { useAuth } from "~~/store/userAuth";
const user = computed(() => useAuth().currentUser);
</script>
