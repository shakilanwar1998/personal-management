import { ref } from 'vue';

const isDark = ref(document.documentElement.classList.contains('dark'));
const listeners = new Set();

export function useTheme() {
    const toggle = () => {
        isDark.value = !isDark.value;
        document.documentElement.classList.toggle('dark', isDark.value);
        try {
            localStorage.setItem('theme', isDark.value ? 'dark' : 'light');
        } catch {}
        listeners.forEach((fn) => fn(isDark.value));
    };
    return { isDark, toggle };
}

export function onThemeChange(fn) {
    listeners.add(fn);
    return () => listeners.delete(fn);
}
