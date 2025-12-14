/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./**/*.php",
        "./**/*.html",
        "./js/**/*.js",
        "./admin/**/*.php",
    ],
    theme: {
        colors: {
            // Sand Gold Minimal Palette (Updated)
            'primary': '#D4AF37',       // Metallic Gold (Accent/Buttons)
            'secondary': '#FDFBF7',     // Off-White/Cream (Background)
            'dark': '#2C1810',          // Dark Coffee (Header/Footer)
            'light': '#FDFBF7',         // Cream (Background alias)
            'text': '#1A1A1A',          // Almost Black (Text)

            // Legacy mapping for compatibility
            'gold': '#D4AF37',
            'gold-dark': '#B5952F',     // Slightly darker version for hover
            'gold-light': '#FDFBF7',
        },
        fontFamily: {
            'sans': ['DM Sans', 'sans-serif'],      // Default for body
            'heading': ['Plus Jakarta Sans', 'sans-serif'], // For headings
            'button': ['Inter', 'sans-serif'],      // For buttons
            'serif': ['Georgia', 'serif'],          // Keep as fallback
        },
        spacing: {
            'section': '4rem',
            'section-lg': '10rem',
            'extra': '3rem',
        },
        backgroundImage: {
            'promo': "url('../img/e.jpg')",
        },
    },
    plugins: [],
}
