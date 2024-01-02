module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.ts',
    './resources/**/*.vue'
  ],
  theme: {
    extend: {
      colors: {
        primary: "#0ED3CF"
      },
      fontFamily: {
        roboto: [ "Roboto", "sans-serif" ]
      }
    }
  },
  plugins: [
    require( '@tailwindcss/forms' ),
    require( '@tailwindcss/aspect-ratio' )
  ]
}
