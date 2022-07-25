const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/rappasoft/laravel-livewire-tables/resources/views/components/**/*.blade.php",
        "./vendor/rappasoft/laravel-livewire-tables/resources/views/includes/**/*.blade.php",
        "./vendor/rappasoft/laravel-livewire-tables/resources/views/stubs/**/*.blade.php",
        "./vendor/rappasoft/laravel-livewire-tables/resources/views/specific/tailwind/**/*.blade.php",
        "./vendor/wire-elements/modal/resources/views/**/*.blade.php",
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
