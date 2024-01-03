const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  content: [
    './resources/**/*.blade.php',
    './storage/framework/views/*.php',
    './resources/**/*.ts',
    './resources/**/*.vue'
  ],
  theme: {
    extend: {
      colors: {
        primary: "#0ED3CF"
      },
      fontFamily: {
        roboto: [ "Roboto", "sans-serif", ...defaultTheme.fontFamily.sans ]
      }
    }
  },
  plugins: [
    require( '@tailwindcss/forms' ),
    require( '@tailwindcss/typography' ),
    require( '@tailwindcss/aspect-ratio' )

  ]
}
