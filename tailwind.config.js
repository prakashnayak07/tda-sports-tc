/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./module/**/view/**/*.phtml",
    "./public/**/*.html",
    "./public/js/**/*.js",
    "./src/**/*.php",
    "./module/Base/src/Base/View/Helper/**/*.php",
    "./module/Base/src/Base/View/Helper/**/*.phtml",
  ],
  theme: {
    extend: {
      colors: {
        // Custom colors matching your current theme
        'booking-primary': '#333333',
        'booking-secondary': '#cbcbcb',
        'booking-accent': '#666666',
        'booking-light': '#f1f1f1',
        'booking-dark': '#808080'
      },
      fontFamily: {
        'sans': ['Arial', 'sans-serif'],
        'mono': ['Consolas', 'monospace']
      },
      spacing: {
        '75': '75px'
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography')
  ],
}