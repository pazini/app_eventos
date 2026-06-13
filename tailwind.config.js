const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    mode: 'jit',
    presets: [
        require('./vendor/wireui/wireui/tailwind.config.js')
    ],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './app/**/*.php',
        './resources/views/**/*.blade.php',
        './src/**/*.{html,js}',
        './vendor/wireui/wireui/resources/**/*.blade.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/View/**/*.php',
        './node_modules/tw-elements/dist/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
            //     roboto     : ['Roboto'],
            //     nunito     : ['Nunito'],
            //     sans       : ['Montserrat','system-ui','ui-sans-serif','Nunito', ...defaultTheme.fontFamily.sans],
            //     serif      : ['ui-serif', 'Georgia'],
            //     mono       : ['ui-monospace', 'SFMono-Regular'],
            //     display    : ['Oswald'],
            //     body       : ['Open Sans'],
                sans: ['inter','inter-tight', ...defaultTheme.fontFamily.sans],
            },
        },
        fontSize: {
            '2xs': '0.625rem',
            '3xs': '0.5rem',
            '4xs': '0.4rem',
            'xxs': '.50rem',
            'xs': '.75rem',
            'sm': '.875rem',
            'tiny': '.875rem',
            'base': '1rem',
            'lg': '1.125rem',
            'xl': '1.25rem',
            '2xl': '1.5rem',
            '3xl': '1.875rem',
            '4xl': '2.25rem',
            '5xl': '3rem',
            '6xl': '4rem',
            '7xl': '5rem',
            '8xl': '6rem',
            '9xl': '7rem',
          },
    },
    safelist: [
        // {
        //     pattern: /from-(gray|red|orange|amber|yellow|green|blue|purple)-(100|200|300|400|500|600|700|800|900)/,
        // },
        // {
        //     pattern: /to-(gray|red|orange|amber|yellow|green|blue|purple)-(100|200|300|400|500|600|700|800|900)/,
        // },
        // {
        //     pattern: /bg-(gray|red|orange|amber|yellow|green|blue|purple)-(100|200|300|400|500|600|700|800|900)/,
        // },
        {
            pattern: /font-(light|extralight|normal|medium|semibold|bold|extrabold)/,
        },
    //   {
    //     pattern: /text-(gray|red|green|blue)-(100|200|300|400|500|600|700)/,
    //   },
    ],
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('tw-elements/dist/plugin'),
    ]

};
