/** Tailwind config for the admin panel only (the public site keeps tailwind.config.js). */
export default {
    darkMode: 'class',
    content: [
        './resources/views/admin.blade.php',
        './resources/js/admin/**/*.{vue,js}',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Inter Variable"', 'Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'Segoe UI', 'sans-serif'],
            },
            colors: {
                brand: {
                    50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc', 400: '#818cf8',
                    500: '#6366f1', 600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81', 950: '#1e1b4b',
                },
            },
            boxShadow: {
                card: '0 1px 2px 0 rgb(0 0 0 / 0.04), 0 1px 3px 0 rgb(0 0 0 / 0.03)',
                pop: '0 10px 38px -10px rgb(22 23 24 / 0.35), 0 10px 20px -15px rgb(22 23 24 / 0.2)',
            },
            keyframes: {
                'fade-in': { from: { opacity: 0 }, to: { opacity: 1 } },
                'pop-in': { from: { opacity: 0, transform: 'translateY(6px) scale(.98)' }, to: { opacity: 1, transform: 'none' } },
                'slide-in': { from: { opacity: 0, transform: 'translateX(16px)' }, to: { opacity: 1, transform: 'none' } },
            },
            animation: {
                'fade-in': 'fade-in .15s ease-out',
                'pop-in': 'pop-in .18s cubic-bezier(.16,1,.3,1)',
                'slide-in': 'slide-in .2s cubic-bezier(.16,1,.3,1)',
            },
        },
    },
    plugins: [],
};
