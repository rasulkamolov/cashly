module.exports = {
  content: ["./*.php", "./includes/**/*.php", "./actions/**/*.php"],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        'jm-primary': '#2E3A8C',
        'jm-secondary': '#4A5FD9',
        'jm-navy': '#1A2254',
        'jm-light-gray': '#F5F7FA',
        'jm-dark-gray': '#4A4A4A',
        'jm-black': '#1A1A1A',
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
