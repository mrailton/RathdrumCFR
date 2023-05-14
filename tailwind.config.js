/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      './resources/**/*.blade.php',
      './resources/**/*.js',
      './vendor/usernotnull/tall-toasts/config/**/*.php',
      './vendor/usernotnull/tall-toasts/resources/views/**/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [
      require('@tailwindcss/forms')
  ],
}
