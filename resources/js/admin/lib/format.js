import { usePage } from '@inertiajs/vue3';

const symbol = () => {
    try {
        return usePage().props.currency || '৳';
    } catch {
        return '৳';
    }
};

const nf = new Intl.NumberFormat('en-US', { maximumFractionDigits: 2, minimumFractionDigits: 0 });
const nf0 = new Intl.NumberFormat('en-US', { maximumFractionDigits: 0 });
const compactNf = new Intl.NumberFormat('en-US', { notation: 'compact', maximumFractionDigits: 1 });

/** ৳12,345 (decimals shown only when present). `signed` prefixes + for positives. */
export function money(value, { decimals = 'auto', signed = false } = {}) {
    const n = Number(value) || 0;
    const abs = Math.abs(n);
    const body = decimals === 0 ? nf0.format(abs) : nf.format(abs);
    const sign = n < 0 ? '−' : signed && n > 0 ? '+' : '';
    return `${sign}${symbol()}${body}`;
}

/** ৳1.2M — for chart axes and tight spaces. */
export function moneyCompact(value) {
    const n = Number(value) || 0;
    return `${n < 0 ? '−' : ''}${symbol()}${compactNf.format(Math.abs(n))}`;
}

export function number(value) {
    return nf.format(Number(value) || 0);
}

export function percent(value, digits = 1) {
    if (value === null || value === undefined || Number.isNaN(Number(value))) return '—';
    return `${Number(value).toFixed(digits)}%`;
}

/** Parse "YYYY-MM-DD" as a local date (no timezone shift). */
export function parseDate(value) {
    if (!value) return null;
    if (value instanceof Date) return value;
    const m = String(value).match(/^(\d{4})-(\d{2})-(\d{2})/);
    if (m) return new Date(Number(m[1]), Number(m[2]) - 1, Number(m[3]));
    const d = new Date(value);
    return Number.isNaN(d.getTime()) ? null : d;
}

const dateFmt = new Intl.DateTimeFormat('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
const shortFmt = new Intl.DateTimeFormat('en-GB', { day: 'numeric', month: 'short' });
const monthFmt = new Intl.DateTimeFormat('en-GB', { month: 'long', year: 'numeric' });

export function date(value) {
    const d = parseDate(value);
    return d ? dateFmt.format(d) : '—';
}

export function shortDate(value) {
    const d = parseDate(value);
    return d ? shortFmt.format(d) : '—';
}

export function monthLabel(value) {
    const d = parseDate(value);
    return d ? monthFmt.format(d) : '';
}

export function today() {
    const d = new Date();
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
}

/** Whole days from today to the date (negative = past). */
export function daysUntil(value) {
    const d = parseDate(value);
    if (!d) return null;
    const t = parseDate(today());
    return Math.round((d - t) / 86400000);
}

export function relativeDue(value) {
    const n = daysUntil(value);
    if (n === null) return '';
    if (n === 0) return 'Due today';
    if (n === 1) return 'Due tomorrow';
    if (n > 1) return `Due in ${n} days`;
    if (n === -1) return '1 day overdue';
    return `${-n} days overdue`;
}

export function initials(name = '') {
    return name
        .split(/\s+/)
        .filter(Boolean)
        .slice(0, 2)
        .map((p) => p[0].toUpperCase())
        .join('');
}
