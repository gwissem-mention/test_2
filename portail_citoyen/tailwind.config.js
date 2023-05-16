/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.ts",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      backgroundImage: {
        'hero-pattern': "linear-gradient(to right, var(--background-action-high-blue-france) 0%,var(--background-action-high-blue-france) calc(708/1440 * 100%), transparent calc(708/1440 * 100%),transparent 100%)",
     },
    },
  },
  plugins: [
    require('@tailwindcss/container-queries'),
  ],
}