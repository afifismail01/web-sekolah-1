/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./node_modules/flowbite/**/*.js",
    ],
    safelist: ["bg-limeDark"],
    theme: {
        extend: {
            colors: {
                limeDark: "#5BCE1C",
                limeLight: "#C6D747",
                sidebarHover: "#5BCE1C",
            },
        },
    },
    plugins: [require("@tailwindcss/forms"), require("flowbite/plugin")],
    daisyui: {
        themes: ["light", "dark"],
        prefix: "ds-",
    },
};
