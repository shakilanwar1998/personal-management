/** Tailwind config for the public portfolio only (see resources/portfolio/portfolio.css). */
const tok = (name) => `rgb(var(--${name}) / <alpha-value>)`;

export default {
    darkMode: ['selector', '[data-theme="dark"]'],
    content: [
        './resources/portfolio/**/*.{vue,js}',
        './resources/views/portfolio.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                bg: tok('bg'),
                surface: tok('surface'),
                raised: tok('raised'),
                line: tok('line'),
                ink: tok('ink'),
                muted: tok('muted'),
                faint: tok('faint'),
                accent: { DEFAULT: tok('accent'), ink: tok('accent-ink'), text: tok('accent-text') },
                ai: { DEFAULT: tok('ai'), text: tok('ai-text'), fill: tok('ai-fill') },
                human: { DEFAULT: tok('human'), text: tok('human-text') },
                ok: { DEFAULT: tok('ok'), text: tok('ok-text') },
                warn: tok('warn'),
            },
            fontFamily: {
                sans: ['Geist', 'ui-sans-serif', 'system-ui', 'Segoe UI', 'Helvetica', 'Arial', 'sans-serif'],
                mono: ['"Geist Mono"', 'ui-monospace', 'SFMono-Regular', 'Menlo', 'Consolas', 'monospace'],
            },
            keyframes: {
                flow: { to: { strokeDashoffset: '-20' } },
                ping2: {
                    '0%': { transform: 'scale(1)', opacity: '.6' },
                    '100%': { transform: 'scale(2.2)', opacity: '0' },
                },
                rise: {
                    from: { opacity: '0', transform: 'translateY(10px)' },
                    to: { opacity: '1', transform: 'translateY(0)' },
                },
                fill: { from: { transform: 'scaleX(0)' }, to: { transform: 'scaleX(1)' } },
                spin: { to: { transform: 'rotate(360deg)' } },
                blink: { '50%': { opacity: '0' } },
            },
            animation: {
                flow: 'flow 1s linear infinite',
                ping2: 'ping2 2s cubic-bezier(0,0,.2,1) infinite',
                rise: 'rise .4s cubic-bezier(.2,.7,.3,1) both',
                ring: 'spin 8s linear infinite',
                blink: 'blink 1s step-end infinite',
            },
        },
    },
    plugins: [],
};
