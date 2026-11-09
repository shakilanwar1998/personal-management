// Brand logos (Simple Icons, CC0) for skills, tags and project covers.
// Only the icons imported here end up in the bundle.
import {
    siN8n, siMake, siZapier, siAirtable, siGooglesheets, siEbay, siShopify, siStripe, siWhatsapp, siIntercom, siSlack,
    siOpenai, siAnthropic, siClaude, siGooglegemini, siLaravel, siPhp, siNodedotjs, siFastify, siNestjs, siExpress,
    siPython, siFastapi, siOpenapiinitiative, siVuedotjs, siInertia, siReact, siTypescript, siElectron, siTailwindcss,
    siMysql, siPostgresql, siMongodb, siRedis, siDocker, siCaddy, siDigitalocean, siGitlab, siFfmpeg, siElevenlabs,
    siCursor, siGraphql, siFilament, siGithub, siWikidata, siOpenstreetmap, siWikimediacommons, siGooglechrome,
    siUpwork, siFiverr, siGooglemaps, siJavascript, siGmail, siNextdotjs,
} from 'simple-icons';

const SI = {
    n8n: siN8n, make: siMake, zapier: siZapier, airtable: siAirtable, sheets: siGooglesheets, ebay: siEbay,
    shopify: siShopify, stripe: siStripe, whatsapp: siWhatsapp, intercom: siIntercom, slack: siSlack,
    openai: siOpenai, anthropic: siAnthropic, claude: siClaude, gemini: siGooglegemini, laravel: siLaravel, php: siPhp,
    node: siNodedotjs, fastify: siFastify, nestjs: siNestjs, express: siExpress, python: siPython, fastapi: siFastapi,
    openapi: siOpenapiinitiative, vue: siVuedotjs, inertia: siInertia, react: siReact, typescript: siTypescript,
    electron: siElectron, tailwind: siTailwindcss, mysql: siMysql, postgres: siPostgresql, mongodb: siMongodb,
    redis: siRedis, docker: siDocker, caddy: siCaddy, digitalocean: siDigitalocean, gitlab: siGitlab, ffmpeg: siFfmpeg,
    elevenlabs: siElevenlabs, cursor: siCursor, graphql: siGraphql, filament: siFilament, github: siGithub,
    wikidata: siWikidata, osm: siOpenstreetmap, wikimedia: siWikimediacommons, chrome: siGooglechrome,
    upwork: siUpwork, fiverr: siFiverr, googlemaps: siGooglemaps, javascript: siJavascript, gmail: siGmail, next: siNextdotjs,
};

// Concepts with no brand get a line icon from components/Icon.vue instead.
const GLYPH = {
    vision: 'eye', retrieval: 'search', privacy: 'shield', hitl: 'users', cost: 'coins', agents: 'sparkle',
    prompt: 'code', voice: 'mic', webhooks: 'link', pipelines: 'flow', rules: 'filter', rest: 'code',
    queues: 'layers', aws: 'cloud', playwright: 'play', sql: 'database', excel: 'grid', code: 'code', chart: 'chart',
};

// Tag / skill wording → logo key.
const ALIASES = {
    'OpenAI': 'openai', 'Anthropic Claude': 'anthropic', 'Claude Code': 'claude', 'Gemini': 'gemini',
    'n8n': 'n8n', 'Make': 'make', 'Zapier': 'zapier', 'Airtable': 'airtable', 'Google Sheets': 'sheets',
    'eBay API': 'ebay', 'Shopify': 'shopify', 'GraphQL': 'graphql', 'Stripe': 'stripe', 'WhatsApp API': 'whatsapp',
    'Intercom Fin': 'intercom', 'Laravel': 'laravel', 'PHP': 'php', 'Filament': 'filament', 'Node.js': 'node',
    'Fastify': 'fastify', 'Python': 'python', 'FastAPI': 'fastapi', 'Vue': 'vue', 'React': 'react',
    'TypeScript': 'typescript', 'Electron': 'electron', 'MySQL': 'mysql', 'PostgreSQL': 'postgres', 'Docker': 'docker',
    'ffmpeg': 'ffmpeg', 'GitLab CI': 'gitlab', 'Voice AI': 'elevenlabs', 'Playwright': 'playwright',
    'Vision': 'vision', 'Computer vision': 'vision', 'Retrieval': 'retrieval', 'Privacy': 'privacy',
    'Human-in-the-loop': 'hitl', 'AI agents': 'agents', 'Structured outputs': 'openai', 'Prompt engineering': 'prompt',
    'Webhooks': 'webhooks', 'Data pipelines': 'pipelines', 'Business rules': 'rules', 'Make.com': 'make',
    'Redis': 'redis', 'Google Maps API': 'googlemaps', 'JavaScript': 'javascript', 'Upwork': 'upwork', 'Fiverr': 'fiverr',
    'SQL Server': 'sql', 'Data visualization': 'chart', 'Next.js': 'next', 'MongoDB': 'mongodb',
};

export function logoKey(name) {
    return ALIASES[name] || (SI[name] || GLYPH[name] ? name : null);
}

/** { kind: 'brand', path, hex, title } | { kind: 'glyph', icon, title } | null */
export function logo(nameOrKey) {
    const key = logoKey(nameOrKey) || nameOrKey;
    if (SI[key]) return { kind: 'brand', path: SI[key].path, hex: `#${SI[key].hex}`, title: SI[key].title, key };
    if (GLYPH[key]) return { kind: 'glyph', icon: GLYPH[key], title: nameOrKey, key };
    return null;
}

/** Very dark or very light brand colours (e.g. OpenAI black) follow the text colour instead. */
export function useInk(hex) {
    const n = parseInt(hex.slice(1), 16);
    const [r, g, b] = [(n >> 16) & 255, (n >> 8) & 255, n & 255];
    const l = (0.2126 * r + 0.7152 * g + 0.0722 * b) / 255;
    return l < 0.18 || l > 0.9;
}
