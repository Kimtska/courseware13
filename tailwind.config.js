export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        violet: {
          950: '#1E0552',
          900: '#2D0A7A',
          800: '#3B0F8F',
          700: '#5B21B6',
          600: '#6D28D9',
          500: '#7C3AED',
          400: '#8B5CF6',
          300: '#A78BFA',
          200: '#C4B5FD',
          100: '#DDD6FE',
          50: '#EDE9FE',
        },
        light: {
          50: '#FAFAFA',
          100: '#F5F5F5',
          200: '#EEEEEE',
          300: '#E0E0E0',
          400: '#BDBDBD',
        },
        dark: {
          950: '#030307',
          900: '#0a0a0f',
          800: '#111118',
          700: '#1a1a24',
        },
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        display: ['Space Grotesk', 'sans-serif'],
      },
    },
  },
};
