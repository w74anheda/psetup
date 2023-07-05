<template>
    <div class="main-header">
        <div class="md:hidden block" @click="useDrawer().handleDrawer(true)">
            <Icon name="ri:menu-fill" size="25" />
        </div>
        <div class="basis-12">
            <img src="/images/bmw-logo.png" />
        </div>
        <div class="basis-1/2 md:block hidden">
            <BaseTheInput label="" name="search" placeholder="دنبال چی میگردی؟" />
        </div>
        <div class="flex">
            <BaseTheButton title="سبد خرید" class="main-header-basket">
                <Icon name="ri:shopping-cart-line" size="20" />
                <span class="badge">2</span>
            </BaseTheButton>
            <BaseTheButton title="(2 مورد) سبد خرید"
                class="main-header-basket-responsive">
                <Icon name="ri:shopping-cart-line" size="20" />
            </BaseTheButton>
            <ClientOnly>
                <NuxtLink to="/auth/login" v-if="!useAuth().isLogin">
                    <BaseTheButton title="ورود | ثبت نام"
                        class="main-header-login-signup md:!flex !hidden" />
                    <BaseTheButton title=""
                        class="main-header-login-signup md:!hidden !flex">
                        <icon name="ri:logout-box-r-line" size="20" />
                    </BaseTheButton>
                </NuxtLink>
                <NuxtLink to="/profile" v-else>
                    <BaseTheButton :title="user ? `${user.first_name} ${user.last_name}` : 'کاربر عزیز'
                        " class="main-header-profile md:!flex !hidden" />
                    <BaseTheButton title=""
                        class="main-header-profile md:!hidden !flex">
                        <icon name="ri:map-pin-user-line" size="20" />
                    </BaseTheButton>
                </NuxtLink>
            </ClientOnly>
        </div>
    </div>
    <div class="sub-header">
        <div class="">01</div>
        <div class="">01</div>
        <div class="">01</div>
        <div class="">01</div>
    </div>
</template>

<script setup lang="ts">
import { useDrawer } from "~~/store/base/drawer";
import { useAuth } from "~~/store/userAuth";

const user = computed(() => useAuth().currentUser);
</script>

<style scoped></style>