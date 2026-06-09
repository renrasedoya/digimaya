/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/View/Components/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    DEFAULT: '#165DFF',
                    50:  '#EBF1FF',
                    100: '#D6E3FF',
                    500: '#165DFF',
                    600: '#0E47CC',
                    700: '#0A3699',
                },
            },
            fontFamily: {
                sans: ['Inter', 'Figtree', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [],
};
