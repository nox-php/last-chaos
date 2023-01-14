const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js'
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                gray: colors.neutral,
                danger: colors.rose,
                primary: colors.amber,
                success: colors.green,
                warning: colors.amber
            }
        }
    },
    plugins: []
};
