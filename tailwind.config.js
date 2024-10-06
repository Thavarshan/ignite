import animate from 'tailwindcss-animate';
import prime from 'tailwindcss-primeui';

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: ['class'],
    safelist: ['dark'],
    prefix: '',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{ts,tsx,vue}',
    ],

    theme: {
        container: {
            center: true,
            padding: '2rem',
            screens: {
                '2xl': '1400px',
            },
        },
        extend: {},
    },
    plugins: [animate, prime],
};
