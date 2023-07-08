// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  css: ["@/assets/css/main.css"],
  modules: [
    "@pinia/nuxt",
    "@nuxtjs/tailwindcss",
    "@vee-validate/nuxt",
    "nuxt-icon",
    "@hypernym/nuxt-anime",
  ],
  app: {
    pageTransition: { name: "page", mode: "out-in" },
    layoutTransition: { name: "layout", mode: "out-in" },
  },
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },
  pinia: {
    autoImports: [
      "defineStore", // import { defineStore } from 'pinia'
    ],
  },
  vite: {
    server: {
      hmr: {
        protocol: "ws",
      },
    },
  },
});
