/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./components/**/*.{js,vue,ts}",
    "./layouts/**/*.vue",
    "./pages/**/*.vue",
    "./plugins/**/*.{js,ts}",
    "./nuxt.config.{js,ts}",
    "./app.vue",
  ],
  theme: {
    fontFamily: {
      IRANSans: ['IRANSans']
    },
    colors: {
      transparent: "transparent",
      primary: "#3b82f6",
      secondary: "#d0d4dc",
      background: "#f4f5f7",
      white: "#ffffff",
      error: "#e35151",
      success: "#2cda94",
      warning: "#fcb823",
      "light-blue": "#ebf2fe",
      "light-gray": "#eceeef",
      "dark-gray": "#98a7b4",
      "darker-gray": "#64748b",
    },
    container: {
      center: true,
      padding: {
        DEFAULT: "1rem",
        sm: "2rem",
        lg: "4rem",
        xl: "5rem",
        "2xl": "6rem",
      },
    },
    extend: {
      fontSize: {
        12: "12px",
        14: "14px",
        16: "16px",
        18: "18px",
        20: "20px",
        24: "24px",
      },
    },
  },
  plugins: [],
};
