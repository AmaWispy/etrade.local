const colors = require('tailwindcss/colors')

module.exports = {
    content: ['./resources/**/*.blade.php', './vendor/filament/**/*.blade.php'],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                danger: colors.rose,
                primary: colors.blue,
                secondary: colors.gray,
                success: colors.green,
                warning: colors.yellow,
                florarColor: 'rgba(255, 65, 118, 1)'
            },
        },
        screens: {
            sm: '320px',  
            370: '370px',  
            md: '425px',  
            lg: '768px', 
            xl: '1024px', 
            '2xl': '1440px', 
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}