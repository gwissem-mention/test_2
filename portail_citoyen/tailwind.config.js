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
    // https://www.systeme-de-design.gouv.fr/elements-d-interface/fondamentaux-techniques/grille-et-points-de-rupture
    screens: {
      'xs': '320px',
      // => @media (min-width: 576px) { ... }

      'sm': '576px',
      // => @media (min-width: 576px) { ... }

      'md': '768px',
      // => @media (min-width: 768px) { ... }

      'lg': '992px',
      // => @media (min-width: 992px) { ... }

      'xl': '1440px',
      // => @media (min-width: 1440px) { ... }
    }
  },
  plugins: [
    require('@tailwindcss/container-queries'),
  ],
}