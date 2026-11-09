/** Category colour names (stored in the DB) mapped to swatches. 500 = solid, 600 = text. */
export const PALETTE = {
    slate: ['#64748b', '#475569'],
    zinc: ['#71717a', '#52525b'],
    stone: ['#78716c', '#57534e'],
    red: ['#ef4444', '#dc2626'],
    orange: ['#f97316', '#ea580c'],
    amber: ['#f59e0b', '#d97706'],
    yellow: ['#eab308', '#ca8a04'],
    lime: ['#84cc16', '#65a30d'],
    green: ['#22c55e', '#16a34a'],
    emerald: ['#10b981', '#059669'],
    teal: ['#14b8a6', '#0d9488'],
    cyan: ['#06b6d4', '#0891b2'],
    sky: ['#0ea5e9', '#0284c7'],
    blue: ['#3b82f6', '#2563eb'],
    indigo: ['#6366f1', '#4f46e5'],
    violet: ['#8b5cf6', '#7c3aed'],
    purple: ['#a855f7', '#9333ea'],
    fuchsia: ['#d946ef', '#c026d3'],
    pink: ['#ec4899', '#db2777'],
    rose: ['#f43f5e', '#e11d48'],
};

export const COLOR_NAMES = Object.keys(PALETTE);

export function swatch(name) {
    return (PALETTE[name] || PALETTE.zinc)[0];
}

/** Soft background + strong foreground for an icon chip. */
export function chipStyle(name) {
    const [solid, strong] = PALETTE[name] || PALETTE.zinc;
    return { backgroundColor: `${solid}1f`, color: strong };
}

/**
 * Validated categorical slots (dataviz reference palette), used for charts with
 * several series. Fixed order — never cycled; extra series fold into "Other".
 */
export const SERIES_LIGHT = ['#2a78d6', '#eb6834', '#1baf7a', '#eda100', '#e87ba4', '#008300', '#6250d6', '#e34948'];
export const SERIES_DARK = ['#3987e5', '#d95926', '#199e70', '#c98500', '#d55181', '#008300', '#9085e9', '#e66767'];

export function cssVar(name) {
    return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
}
