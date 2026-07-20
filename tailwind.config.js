import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                // Warm oak / walnut wood scale
                wood: {
                    50: '#faf6f0',
                    100: '#f3e9db',
                    200: '#e6d2b8',
                    300: '#d4b58c',
                    400: '#bf9160',
                    500: '#a9743f',
                    600: '#8f5a2f',
                    700: '#734527',
                    800: '#5c3823',
                    900: '#472c1d',
                    950: '#2a1a12',
                },
                // Brass / harvest-gold accent that pairs with wood
                brass: {
                    300: '#e7c789',
                    400: '#dcb063',
                    500: '#c8963d',
                    600: '#a97a2c',
                },
            },
        },
    },

    plugins: [forms],
};
