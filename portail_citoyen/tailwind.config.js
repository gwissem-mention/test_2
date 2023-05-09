/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.ts",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      backgroundImage: {
        'hero-pattern': "linear-gradient(to right, #000091 0%,#000091 calc(708/1440 * 100%), transparent calc(708/1440 * 100%),transparent 100%)",
     },
    },
  },
  plugins: [
    require('@tailwindcss/container-queries'),
  ],
}