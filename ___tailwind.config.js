/** @type{ import('tailwindcss').Config } */
const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors')

module.exports = {
    content: [
        "./vendor/rappasoft/laravel-livewire-tables/resources/views/tailwind/**/*.blade.php",
        "./vendor/wire-elements/modal/resources/views/**/*.blade.php",
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // Simple
            /*fontFamily: {
                display: ['Inter', 'system-ui', 'sans-serif'],
                body: ['Inter', 'system-ui', 'sans-serif'],
            },*/
            // Playfull
            /*fontFamily: {
                display: ['Pally', 'Comic Sans MS', 'sans-serif'],
                body: ['Pally', 'Comic Sans MS', 'sans-serif'],
            },*/
            // Elegant
            fontFamily: {
                display: ['Source Serif Pro', 'Georgia', 'serif'],
                body: ['Synonym', 'system-ui', 'sans-serif'],
            },
            // Brutalist
            /*fontFamily: {
                display: ['IBM Plex Mono', 'Menlo', 'monospace'],
                body: ['IBM Plex Mono', 'Menlo', 'monospace'],
            },*/
            // Default
            /*fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },*/

        },
        // colors: {
        //     transparent: 'transparent',
        //     current: 'currentColor',
        //     white: colors.white,
        //     gray: colors.slate,
        //     sky: colors.sky,
        //     blue: colors.blue,
        //     lime: colors.lime,
        //     green: colors.green,
        //     red: colors.red,
        //     indigo: colors.violet,
        //     violet: colors.violet,
        //     yellow: colors.yellow,
        //     orange: colors.orange,
        //     amber: colors.amber,
        // },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
