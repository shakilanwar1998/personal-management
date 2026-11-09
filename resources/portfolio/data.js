// All portfolio content lives here, so the components stay presentation-only.
//
// PROJECTS: every entry in `posts` is also a card on the Projects page.
//   url:   optional link the card opens (a live site, an app store page, a public repo…).
//          If empty, the card opens the post's primary link (e.g. a case study) or its details.
//   image: optional real thumbnail, e.g. '/images/projects/ghurun.jpg' (put the file in public/images/projects/).
//          Without it, a cover is generated from `cover` + the project's tags and stats.
//   name:  the project's own name, shown on every card, cover and post.
//   cover: { tone: violet|orange|teal|blue|rose|amber|slate, pattern: dots|grid|rings|lines, label?, logos?[] }
// Client names are anonymised on purpose. Numbers come from the project repos,
// case studies or commit history; anything illustrative is labelled as such.

export const profile = {
    name: 'Shakil Anwar',
    handle: 'shakilanwar1998',
    site: 'https://shakilanwar.com', // public address, used for links on the CV
    initials: 'SA',
    avatar: '/images/shakil.jpg', // photo in public/images/; set to null to fall back to initials
    headline: 'Software Engineer · Business Automation',
    bio: 'I turn manual business operations into workflows that run themselves.',
    tagline: 'AI where it helps, a human where it matters.',
    subline: 'Sr. Software Engineer at Dropmatix · Top Rated freelancer on Upwork, also on Fiverr',
    location: 'Dinajpur, Bangladesh',
    timezone: 'GMT+6',
    since: 2019,
    available: true,
    availability: 'Open to automation & AI projects',
    email: 'shakilanwar1998@gmail.com',
    phone: '+880 1738 362 296',
    whatsapp: '8801738362296',
    links: [
        { label: 'GitHub', href: 'https://github.com/shakilanwar1998', icon: 'github' },
        { label: 'LinkedIn', href: 'https://linkedin.com/in/shakilanwar1998', icon: 'linkedin' },
        { label: 'Facebook', href: 'https://facebook.com/shakilanwar1998', icon: 'facebook' },
        { label: 'Upwork', href: 'https://www.upwork.com/freelancers/~01a0defc0f412196fa', icon: 'upwork', brand: true },
        { label: 'Fiverr', href: 'https://www.fiverr.com/shakilanwar/', icon: 'fiverr', brand: true },
    ],
    countries: ['Spain', 'United Kingdom', 'United States', 'France', 'Germany', 'Austria', 'Netherlands', 'Bangladesh'],
    homeCountry: 'Bangladesh',
    clientCountries: ['Spain', 'United Kingdom', 'United States', 'Germany', 'France', 'Austria', 'Netherlands'],
    principle: 'The machine does the boring, provable work. A human owns the judgement calls. Every field carries a record of where it came from.',
};

// The Overview tab: who I am in a few lines, what I do, and the work to look at first.
export const overview = {
    heading: 'I build software that takes manual work off a team’s plate.',
    intro: 'Senior software engineer with seven years in e-commerce. I build marketplace integrations, back-office automation and AI features that a business can trust, mostly for teams in Europe, and I lead the tech at Okkhor, an online bookstore in Bangladesh.',
    pillars: [
        { icon: 'sparkle', title: 'AI automation', text: 'n8n and custom pipelines where AI drafts and a person approves: vision, agents and structured outputs, with costs capped.' },
        { icon: 'flow', title: 'Marketplaces & e-commerce', text: 'eBay, Shopify, Amazon, OTTO and Airtable kept in step: listings, stock, orders and feeds.' },
        { icon: 'layers', title: 'Product engineering', text: 'Laravel, Node.js, Python and Vue apps from first commit to production, and leading the team that runs them.' },
    ],
    featured: ['okkhor', 'quick-annotation', 'vintbay', 'saas-suite'],
};

// Freelance marketplace profiles. Numbers are copied from the public profiles; update them by hand.
// Upwork checked 2026-10-29. Fiverr blocks automated reads: the 4.9 rating is from search results, so
// check it (and add the review count) against the live gig.
export const marketplaces = [
    {
        id: 'upwork',
        label: 'Upwork',
        href: 'https://www.upwork.com/freelancers/~01a0defc0f412196fa',
        title: 'Expert Automation Developer | API Integration | eBay | n8n | Airtable',
        rating: '5.0',
        reviews: 10,
        badges: ['Top Rated', '100% Job Success', 'ID verified'],
        facts: [
            { value: '19', label: 'jobs' },
        ],
    },
    {
        id: 'fiverr',
        label: 'Fiverr',
        href: 'https://www.fiverr.com/shakilanwar/',
        title: 'API integration & e-commerce automation',
        rating: '4.9',
        reviews: null, // add the count shown on the gig
        badges: [],
        facts: [],
    },
];

// Skill badges in the profile header. `future: true` = the AI-era skills, shown first and highlighted.
// `tags` are post tags, so a click filters the feed to the projects that use the skill.
export const skillBadges = [
    { label: 'AI agents', logo: 'AI agents', tags: ['AI agents'], future: true },
    { label: 'LLM integration', logo: 'OpenAI', tags: ['OpenAI', 'Anthropic Claude', 'Gemini', 'Structured outputs'], future: true },
    { label: 'n8n', logo: 'n8n', tags: ['n8n'], future: true },
    { label: 'Human-in-the-loop', logo: 'Human-in-the-loop', tags: ['Human-in-the-loop'], future: true },
    { label: 'Vision & voice AI', logo: 'Vision', tags: ['Vision', 'Computer vision', 'Voice AI'], future: true },
    { label: 'Claude Code', logo: 'Claude Code', tags: ['Claude Code'], future: true },
    { label: 'Laravel', logo: 'Laravel', tags: ['Laravel', 'Filament'] },
    { label: 'Python', logo: 'Python', tags: ['Python', 'FastAPI'] },
    { label: 'Node.js', logo: 'Node.js', tags: ['Node.js', 'Fastify'] },
    { label: 'Vue & React', logo: 'Vue', tags: ['Vue', 'React'] },
    { label: 'eBay API', logo: 'eBay API', tags: ['eBay API'] },
    { label: 'Shopify', logo: 'Shopify', tags: ['Shopify'] },
    { label: 'Make & Zapier', logo: 'Make', tags: ['Make', 'Zapier'] },
    { label: 'Docker', logo: 'Docker', tags: ['Docker'] },
];

// Story highlights: the round "stories" under the profile header. One case-study reel each.
// Reels are made in ~/Development/reels (Remotion + ElevenLabs voice, captions burned in).
// Files live in public/videos/stories/: <id>.mp4 (vertical 9:16, H.264) and <id>.jpg (poster) and <id>-thumb.jpg (square, for the circle).
// A slide can also be text-only: { title, body, posts } instead of { video, poster, posts }.
const reel = (id, label, post = id) => ({
    id, label, image: `/videos/stories/${id}-thumb.jpg`,
    slides: [{ video: `/videos/stories/${id}.mp4`, poster: `/videos/stories/${id}.jpg`, posts: [post] }],
});

export const highlights = [
    reel('quick-annotation', 'Catalog AI'),
    reel('vintbay', 'VintBay'),
    reel('deal-finder', 'Deal Finder'),
    reel('okkhor', 'Okkhor'),
    reel('saas-suite', 'SaaS Suite'),
    reel('sadb', 'SaDB'),
    reel('ebay-de', 'eBay.de'),
    reel('ebay-shopify-seo', 'ShopSync'),
    reel('ai-support-agent', 'AI support'),
    reel('pii-masking', 'Private AI'),
];

// Feed posts, newest first. `when` is what a visitor sees; `sort` orders them.
export const posts = [
    {
        id: 'quick-annotation',
        name: 'Quick Annotation',
        pinned: true,
        sort: '2026-06',
        when: 'Apr – Jun 2026',
        kind: 'Client project',
        client: 'European used-equipment reseller',
        title: 'Equipment photos in. Approved catalog listings out.',
        body: [
            'I built Quick Annotation: an n8n pipeline where GPT-4o vision reads equipment nameplates, a search agent finds the real manual among the shop listings, and a rules layer checks the AI’s price against filtered market offers.',
            'Nothing reaches the catalog until a reviewer approves it, one field at a time, in a Laravel review queue that talks to n8n through webhooks. Calling the LLM was the easy part. Making its output safe to write into a production catalog was the real work.',
        ],
        attachments: [
            {
                type: 'pipeline',
                steps: [
                    { label: 'Catalog API', sub: 'batch pull', kind: 'trigger' },
                    { label: 'GPT-4o vision', sub: 'reads nameplates', kind: 'ai' },
                    { label: 'Search agent', sub: 'finds the manual', kind: 'ai' },
                    { label: 'Price rules', sub: 'IQR on market offers', kind: 'rule' },
                    { label: 'Reviewer', sub: 'approves per field', kind: 'human' },
                    { label: 'Catalog + XLSX', sub: 'write-back & eBay import file', kind: 'out' },
                ],
            },
        ],
        stats: [
            { value: '85', label: 'nodes in the workflow' },
            { value: '4', label: 'AI stages' },
            { value: '6', label: 'fields a human signs off' },
            { value: '20', label: 'real items in the pilot' },
        ],
        tags: ['n8n', 'OpenAI', 'Vision', 'Human-in-the-loop', 'Laravel', 'Google Sheets', 'Webhooks'],
        links: [{ label: 'Read the case study', href: '/case-studies/quick-annotation', primary: true }],
        url: null,
        cover: { tone: 'violet', pattern: 'grid', scene: 'QuickAnnotation', brand: { text: 'QA', color: '#8b7cff', color2: '#3b2f99' }, tagline: 'AI catalog enrichment for a used-equipment reseller' },
    },
    {
        id: 'okkhor',
        name: 'Okkhor',
        sort: '2026-10',
        when: 'Dec 2022 – Present',
        kind: 'At Okkhor',
        client: 'CTO · okkhor.com',
        title: 'Bangladesh’s online bookstore, where I lead the tech.',
        body: [
            'I’m CTO of Okkhor, an online bookstore for academic, Islamic and general books in Bangla and English. I work across the whole platform: the Next.js storefront, the Node.js API on MongoDB and Redis, the admin panel and a portal where publishers manage their books.',
            'In 2026 I led a design refresh of the storefront (navigation, book cards, banners, cart and checkout) and rebuilt the admin as Admin v2. On the backend I added Redis caching and lighter product and cart queries, fixed Bengali search, made order prices follow product price changes, and shipped campaign requests so publishers can ask for promotions. The work is ongoing.',
        ],
        attachments: [],
        // Catalog numbers from Okkhor's public API (api.okkhor.com), 30 Oct 2026.
        stats: [
            { value: '4,000+', label: 'books in the catalog' },
            { value: '1,800+', label: 'authors' },
            { value: '120', label: 'publishers' },
            { value: 'Since 2022', label: 'leading the tech' },
        ],
        tags: ['Next.js', 'React', 'Node.js', 'MongoDB', 'Redis', 'Docker'],
        links: [{ label: 'Visit okkhor.com', href: 'https://okkhor.com', primary: true, external: true }],
        url: 'https://okkhor.com',
        cover: { tone: 'orange', pattern: 'dots', scene: 'Okkhor', brand: { image: '/images/brands/okkhor-icon.png' }, tagline: 'Bangladesh’s online bookstore · I’m the CTO' },
    },
    {
        id: 'vintbay',
        name: 'VintBay',
        sort: '2026-10',
        when: 'Aug – Oct 2026',
        kind: 'Client project',
        client: 'UK second-hand fashion reseller',
        title: 'Paste a Vinted link. Get an eBay listing that passes eBay’s checks.',
        body: [
            'The platform pulls in the item, cuts the photos out onto white, maps the details to eBay’s own item specifics, publishes, and keeps eBay in step with the source price and stock.',
            'Rules fill only what they can prove. AI suggests the rest through strict JSON-schema output, the server rejects anything not on eBay’s allowed list, and a person accepts or rejects each row. Every AI call is costed up front and capped by a monthly budget. Plan to production took about four weeks.',
        ],
        attachments: [
            {
                type: 'fields',
                caption: 'Every field carries its source',
                illustrative: true,
                rows: [
                    { field: 'Department', value: 'Women', source: 'Vinted' },
                    { field: 'Size', value: 'UK 12', source: 'Rule' },
                    { field: 'Condition', value: 'Pre-owned · Excellent', source: 'Rule' },
                    { field: 'Material', value: 'Waxed cotton', source: 'AI' },
                    { field: 'Style', value: 'Field jacket', source: 'Manual' },
                ],
            },
            {
                type: 'bars',
                caption: 'Photo check · local run on 14 Vinted listings, 98 photos (Sep 2026, earlier model)',
                rows: [
                    { label: 'Passed', value: 72, tone: 'ok' },
                    { label: 'Flagged for a person', value: 9, tone: 'warn' },
                    { label: 'Refused (under eBay’s 500px)', value: 17, tone: 'muted' },
                ],
            },
        ],
        stats: [
            { value: '~4 wk', label: 'plan to production' },
            { value: '728', label: 'automated tests' },
            { value: '16', label: 'pipeline states' },
            { value: '103', label: 'API endpoints' },
        ],
        tags: ['Laravel', 'Vue', 'Python', 'FastAPI', 'OpenAI', 'Structured outputs', 'Computer vision', 'eBay API', 'Human-in-the-loop', 'Docker'],
        url: null,
        cover: { tone: 'teal', pattern: 'dots', logos: ['eBay API', 'OpenAI', 'Laravel', 'Python'], scene: 'VintBay', brand: { mark: 'vintbay' }, tagline: 'Vinted → eBay cross-listing platform' },
    },
    {
        id: 'deal-finder',
        name: 'Deal Finder',
        sort: '2026-10',
        when: 'Sep – Oct 2026',
        kind: 'Client project',
        client: 'UK second-hand fashion reseller',
        title: 'A Buy Score that shows its working.',
        body: [
            'Every item the business considers buying gets a score from 1 to 10, priced against live eBay listings and the business’s own costs, with a confidence label on every score. The sold-price path is built and switches on once eBay grants API access.',
            'When eBay has too few matches, an LLM writes a better search, sets look-alikes aside with a reason for each, and estimates a sale price. A person can untick any comparable and the score updates. A report then checks predicted profit against what actually sold.',
        ],
        attachments: [
            {
                type: 'score',
                illustrative: true,
                item: 'Waxed field jacket · size M',
                score: 7.8,
                parts: [
                    { label: 'Expected margin', value: 0.82 },
                    { label: 'Sell-through speed', value: 0.71 },
                    { label: 'Comparable quality', value: 0.76 },
                ],
                rejected: ['Kids’ size, not comparable', 'Lining only, not a jacket'],
            },
        ],
        stats: [],
        tags: ['OpenAI', 'Structured outputs', 'eBay API', 'Laravel', 'Python'],
        url: null,
        cover: { tone: 'amber', pattern: 'lines', label: 'A Buy Score from 1 to 10', logos: ['eBay API', 'OpenAI', 'Laravel', 'Python'], scene: 'DealFinder', brand: { text: 'DF', color: '#ffba52', color2: '#b45309' }, tagline: 'Buying assistant for a second-hand reseller' },
    },
    {
        id: 'ai-support-agent',
        name: 'Fin Knowledge Connector',
        sort: '2026-09',
        when: 'Sep 2026',
        kind: 'At Dropmatix',
        client: 'Multi-tenant SaaS business suite',
        title: 'Grounding an AI support agent in a product’s own video library.',
        body: [
            'I connected the product’s Intercom Fin AI agent to its video-course library. It only ever returns lessons the customer can actually open; locked lessons are never handed to the bot.',
            'Under the hood: German BM25F full-text ranking, re-ranking by the screen the customer is on, entitlement checks shared with the video player, and fail-closed machine-to-machine auth. Grounding an LLM properly is mostly careful plumbing.',
        ],
        attachments: [
            {
                type: 'chat',
                illustrative: true,
                messages: [
                    { from: 'user', text: 'How do I pause payment reminders for one customer?' },
                    { from: 'bot', text: 'There’s a 3-minute lesson on exactly that:', card: { title: 'Pausing reminders', meta: 'Lesson · included in your plan' } },
                    { from: 'user', text: 'And automating follow-up emails?' },
                    { from: 'bot', text: 'That’s covered in the Sequences course, which is part of the next plan up.' },
                ],
            },
        ],
        stats: [
            { value: '2.5×', label: 'title weight in BM25F' },
            { value: '<5 min', label: 'token lifetime for the bot' },
            { value: '60/min', label: 'rate limit per user' },
        ],
        tags: ['AI agents', 'Retrieval', 'Intercom Fin', 'Laravel'],
        url: null,
        cover: { tone: 'blue', pattern: 'rings', scene: 'FinConnector', brand: { text: 'FK', color: '#5aa9ff', color2: '#1d4ed8' }, tagline: 'AI support agent, grounded in video courses' },
    },
    {
        id: 'pii-masking',
        name: 'Private Import Mapper',
        sort: '2026-10',
        when: 'Sep – Oct 2026',
        kind: 'At Dropmatix',
        client: 'Multi-tenant SaaS business suite',
        title: 'AI help shouldn’t cost your customers their privacy.',
        body: [
            'The CRM importer uses Claude to suggest which CSV column maps to which field. I changed it so the model only ever sees the shape of the data, never the real values.',
            'In a synthetic-data check it also stopped guessing on meaningless headers, where the unchanged prompt had produced wrong mappings. The user still confirms every mapping.',
        ],
        attachments: [
            {
                type: 'mask',
                caption: 'What leaves the server',
                illustrative: true,
                rows: [
                    { from: 'Jane Cooper', to: 'Aaaa Aaaaaa' },
                    { from: 'jane@cooper-design.co', to: 'aaaa@aaaaaa-aaaaaa.aa' },
                    { from: '+49 151 2345 6789', to: '+99 999 9999 9999' },
                    { from: '2026-03-14', to: '9999-99-99' },
                ],
            },
        ],
        stats: [],
        tags: ['Anthropic Claude', 'Privacy', 'Prompt engineering', 'Laravel'],
        url: null,
        cover: { tone: 'slate', pattern: 'lines', label: 'Real values never reach the model', scene: 'ImportMapper', brand: { text: 'PM', color: '#94a3b8', color2: '#334155' }, tagline: 'Privacy-safe AI column mapping for CSV imports' },
    },
    {
        id: 'saas-suite',
        name: 'SaaS Business Suite',
        sort: '2026-10',
        when: 'Aug – Oct 2026',
        kind: 'At Dropmatix',
        client: 'Multi-tenant SaaS business suite',
        title: 'Three months as a core developer on a SaaS business suite.',
        body: [
            'CRM, invoicing, a unified mailbox, video courses and a public API, split off a legacy platform and hardened for launch.',
            'My parts: a channel-agnostic order API that Shopify, Make or Zapier can push into, with retried outbound webhooks; WhatsApp as a real inbox channel; Stripe and Digistore24 payments, with course access granted automatically on each Digistore24 sale; AI-written titles and descriptions that stay labelled as AI until a person edits them; Slack alerts for error bursts and stalled queues; and Gitleaks + Opengrep in CI. Claude Code was my pair programmer throughout.',
        ],
        attachments: [
            {
                type: 'terminal',
                title: '#alerts',
                illustrative: true,
                lines: [
                    { t: '09:14', tone: 'warn', text: 'Error burst · same exception every 3s on queue "default"' },
                    { t: '09:14', tone: 'muted', text: 'payload hidden · see Horizon for details' },
                    { t: '09:20', tone: 'warn', text: 'Scheduler heartbeat missed (2 cycles)' },
                    { t: '09:31', tone: 'ok', text: 'Recovered · queue drained, heartbeat back' },
                ],
            },
        ],
        stats: [
            { value: '643', label: 'commits in 3 months' },
            { value: '61', label: 'public API operations' },
            { value: '9', label: 'API domains' },
        ],
        tags: ['Laravel', 'Vue', 'Webhooks', 'Make', 'Zapier', 'Stripe', 'WhatsApp API', 'GitLab CI', 'Claude Code'],
        url: null,
        cover: { tone: 'blue', pattern: 'grid', scene: 'SaasSuite', brand: { text: 'SB', color: '#5aa9ff', color2: '#3730a3' }, tagline: 'Multi-tenant CRM, invoicing and inbox suite' },
    },
    {
        id: 'ghurun',
        name: 'Ghurun',
        sort: '2026-10',
        when: 'Oct 2026',
        kind: 'Own product',
        client: null,
        title: 'Tick off the places you’ve actually seen in Bangladesh.',
        body: [
            'A bilingual (Bangla/English) travel map where each district fills in as you visit its places, plus an optional district food-and-crafts marketplace.',
            'The data is built by pipelines. One pulls photos from Wikimedia Commons and keeps a photo only if it names both the place and where it is. Another geocodes through OpenStreetMap and Wikidata and keeps a point only if it lands inside the right upazila. A person picks wherever the automation isn’t sure. Even the launch reel came from code, with AI-generated Bangla narration.',
        ],
        attachments: [
            {
                type: 'pipeline',
                steps: [
                    { label: 'Place list', sub: '64 districts', kind: 'trigger' },
                    { label: 'Wikimedia', sub: 'photo search', kind: 'rule' },
                    { label: 'Name + place', sub: 'match check', kind: 'rule' },
                    { label: 'OSM → Wikidata', sub: 'geocode', kind: 'rule' },
                    { label: 'Polygon check', sub: 'inside upazila?', kind: 'rule' },
                    { label: 'Curator', sub: 'when unsure', kind: 'human' },
                ],
            },
        ],
        stats: [
            { value: '64', label: 'districts' },
            { value: '1,935', label: 'places mapped' },
            { value: '332', label: 'must-see picks' },
            { value: '60s', label: 'launch reel, rendered from code' },
        ],
        tags: ['React', 'TypeScript', 'Fastify', 'PostgreSQL', 'Data pipelines', 'Playwright', 'Voice AI'],
        links: [{ label: 'Visit ghurun.com', href: 'https://ghurun.com', primary: true, external: true }],
        url: 'https://ghurun.com',
        cover: { tone: 'teal', pattern: 'dots', scene: 'Ghurun', brand: { mark: 'ghurun' }, tagline: 'Bangla/English travel map of Bangladesh' },
    },
    {
        id: 'sadb',
        name: 'SaDB',
        sort: '2026-05',
        when: 'May 2026',
        kind: 'Open source',
        client: null,
        title: 'A database client whose AI reads your schema first.',
        body: [
            'A desktop client for PostgreSQL, MySQL and MongoDB. Its AI agent reads the live schema before it writes a query, and is told never to invent tables or columns.',
            'Bring your own model (Claude, GPT or Gemini), keys are encrypted locally, and every generated query lands in the editor for you to review before it runs.',
        ],
        attachments: [
            {
                type: 'terminal',
                title: 'SaDB · AI',
                lines: [
                    { t: 'you', tone: 'ink', text: 'customers who ordered twice this month' },
                    { t: 'ai', tone: 'ai', text: 'SELECT c.id, c.name, COUNT(o.id) AS orders' },
                    { t: '', tone: 'ai', text: 'FROM customers c JOIN orders o ON o.customer_id = c.id' },
                    { t: '', tone: 'ai', text: "WHERE o.created_at >= date_trunc('month', now())" },
                    { t: '', tone: 'ai', text: 'GROUP BY c.id, c.name HAVING COUNT(o.id) = 2;' },
                    { t: '', tone: 'muted', text: '↳ inserted into editor · review, then run' },
                ],
            },
        ],
        stats: [
            { value: '3', label: 'database engines' },
            { value: '3', label: 'AI providers' },
            { value: 'MIT', label: 'licence' },
        ],
        tags: ['AI agents', 'Anthropic Claude', 'OpenAI', 'Gemini', 'Electron', 'React', 'TypeScript'],
        links: [{ label: 'View on GitHub', href: 'https://github.com/shakilanwar1998/sadb', primary: true, external: true }],
        url: 'https://github.com/shakilanwar1998/sadb',
        cover: { tone: 'violet', pattern: 'rings', logos: ['Electron', 'Anthropic Claude', 'OpenAI', 'Gemini'], scene: 'Sadb', brand: { mark: 'sadb' }, tagline: 'Desktop database client with an AI agent' },
    },
    {
        id: 'express',
        name: 'Express',
        sort: '2026-10',
        when: 'May – Oct 2026',
        kind: 'Client project',
        client: 'Express Group · members’ investment society',
        title: 'A members’ investment society, run by its own constitution.',
        body: [
            'Express Group pools its members’ monthly savings, invests them and shares the profit. It used to run on a spreadsheet and a printed constitution. I turned both into one web app: members, capital deposits, investments and their returns, expenses, committees, meetings and the society’s funds.',
            'The constitution’s rules now run in code. Late payments and missed meetings create fines automatically, and running it twice never charges twice. Profit is held until a manager closes the year, the Fi Sabilillah and risk-fund cuts move between funds in the ledger, and closing a year can be reversed. Eleven official reports, from member statements to the yearly accounts, come out as Bengali PDFs.',
        ],
        attachments: [],
        stats: [
            { value: '11', label: 'official reports as PDFs' },
            { value: '3', label: 'fine types, created automatically' },
            { value: 'BN/EN', label: 'bilingual app' },
            { value: '33', label: 'automated tests' },
        ],
        tags: ['Laravel', 'Vue', 'Business rules', 'Docker'],
        url: null, // the app is private
        cover: { tone: 'teal', pattern: 'grid', scene: 'ExpressLedger', brand: { text: 'EX', color: '#3ddc97', color2: '#047857' }, tagline: 'Savings, investments and profit for a members’ investment society' },
    },
    {
        id: 'shopify-store',
        name: 'Fragrance Store Kit',
        sort: '2026-09',
        when: 'Sep 2026',
        kind: 'Prototype',
        client: 'Fragrance retailer (prospect)',
        title: 'A Shopify store set up by script, not by clicking.',
        body: [
            'A storefront built around how people actually buy perfume: by house, by note, and by which bottle size is worth it. The part I enjoyed most is underneath: Python tooling that sets up the whole store through the Admin GraphQL API, idempotent and throttle-aware.',
            'Lesson learned: products(first: 250) is a page, not your catalogue. Cursor pagination fixed 67 products that had silently stayed unpublished.',
        ],
        attachments: [],
        stats: [
            { value: '317', label: 'products imported' },
            { value: '0→96%', label: 'scent-note coverage' },
            { value: '0', label: 'Theme Check errors' },
        ],
        tags: ['Shopify', 'GraphQL', 'Python'],
        url: null,
        cover: { tone: 'teal', pattern: 'lines', scene: 'FragranceStore', brand: { text: 'FS', color: '#c9a36a', color2: '#6b4f2a' }, tagline: 'Shopify perfume store, set up by script' },
    },
    {
        id: 'ebay-de',
        name: 'eBay.de Auto Lister',
        sort: '2026-02',
        when: 'Jun 2025 – Feb 2026',
        kind: 'Client project',
        client: 'German vintage hi-fi reseller',
        title: 'Airtable button → live eBay.de listing.',
        body: [
            'Their Airtable base calls my Laravel webhooks to pick the eBay category, fetch its item specifics, pull seller policies and publish fixed-price or auction listings. Photos and video upload by SKU and sync back to Airtable.',
            'I first built an OpenAI step that picks the category and drafts every item specific. Once the team’s review habits were clear, eBay’s own top suggestion plus a required-aspect map did the job better, so the live flow dropped the AI step. The prototype taught us where AI wasn’t needed.',
        ],
        attachments: [
            {
                type: 'pipeline',
                steps: [
                    { label: 'Airtable', sub: 'automation', kind: 'trigger' },
                    { label: 'Laravel', sub: 'webhook', kind: 'rule' },
                    { label: 'Category', sub: 'eBay top suggestion', kind: 'rule' },
                    { label: 'Specifics', sub: 'required-aspect map', kind: 'rule' },
                    { label: 'Policies', sub: 'seller policies', kind: 'rule' },
                    { label: 'eBay.de', sub: 'publish', kind: 'out' },
                ],
            },
        ],
        stats: [],
        tags: ['eBay API', 'Airtable', 'Laravel', 'Webhooks'],
        url: null,
        cover: { tone: 'blue', pattern: 'dots', label: 'Airtable button → eBay.de', scene: 'EbayDeLister', brand: { text: 'AL', color: '#5aa9ff', color2: '#1e3a8a' }, tagline: 'Airtable → eBay.de auto-lister' },
    },
    {
        id: 'ebay-airtable-sync',
        name: 'eBay Sheets Sync',
        sort: '2025-10',
        when: 'Oct 2025',
        kind: 'Client project',
        client: 'eBay seller',
        title: 'Keeping Airtable and Google Sheets in step with an eBay store.',
        body: [
            'Sync workers pull listings and orders into Airtable and Google Sheets. Every item specific gets its own column, and missing Airtable fields are created on the fly. Paid orders lower stock and raise sold counts automatically.',
            'A resumable backfill loaded about two years of order history in 60-day windows, skipping cancelled and refunded orders and de-duplicating by order ID.',
        ],
        attachments: [],
        stats: [
            { value: '~2 yrs', label: 'order history backfilled' },
            { value: '2,278', label: 'unique orders loaded' },
            { value: '60-day', label: 'resumable windows' },
        ],
        tags: ['eBay API', 'Airtable', 'Google Sheets', 'PHP'],
        url: null,
        cover: { tone: 'amber', pattern: 'grid', scene: 'EbaySheetsSync', brand: { text: 'SY', color: '#ffba52', color2: '#92400e' }, tagline: 'eBay ↔ Airtable & Google Sheets sync' },
    },
    {
        id: 'ebay-shopify-seo',
        name: 'ShopSync SEO',
        sort: '2025-07',
        when: 'Jun – Jul 2025',
        kind: 'Client project',
        client: 'French industrial-parts reseller',
        title: 'eBay → Shopify, with AI-written SEO copy and a retry button.',
        body: [
            'New eBay listings go through an OpenAI step that writes SEO titles and descriptions as structured JSON, then get published to Shopify. Stock stays in sync through webhooks and scheduled jobs.',
            'I started with a Make.com scenario and moved the AI step into the app once the volume justified it. When the AI or an API call fails, the listing waits in a Filament dashboard where the team retries it with one click. Nothing is lost.',
        ],
        attachments: [
            {
                type: 'pipeline',
                steps: [
                    { label: 'eBay', sub: 'hourly check', kind: 'trigger' },
                    { label: 'OpenAI', sub: 'SEO JSON', kind: 'ai' },
                    { label: 'Shopify', sub: 'publish', kind: 'out' },
                    { label: 'Stock sync', sub: 'every 15 min', kind: 'rule' },
                    { label: 'Team', sub: 'retry on fail', kind: 'human' },
                ],
            },
        ],
        stats: [],
        tags: ['Shopify', 'eBay API', 'OpenAI', 'Laravel', 'Filament', 'Make'],
        url: null,
        cover: { tone: 'orange', pattern: 'lines', label: 'eBay → Shopify, SEO by AI', logos: ['eBay API', 'Shopify', 'OpenAI', 'Make'], scene: 'ShopSyncSeo', brand: { text: 'SS', color: '#ff915e', color2: '#9a3412' }, tagline: 'eBay → Shopify publisher with AI-written SEO' },
    },
    {
        id: 'inventory-ops',
        name: 'Inventory Ops Hub',
        sort: '2026-03',
        when: 'Apr 2025 – Mar 2026',
        kind: 'Client project',
        client: 'Multi-marketplace reseller',
        title: 'Back-office automation for a multi-marketplace reseller.',
        body: [
            'I co-built the internal ops platform. My parts: eBay inventory and order sync, a parser that finds the header row, UPC and price columns in messy vendor Excel sheets, role-based permissions for six warehouse and office roles, and low-stock email alerts.',
            'Unglamorous back-office automation, and exactly the kind of work that gives a small team its hours back.',
        ],
        attachments: [],
        stats: [
            { value: '6', label: 'roles with a permission matrix' },
            { value: '1 min', label: 'order sync cadence' },
        ],
        tags: ['Node.js', 'React', 'eBay API', 'MySQL'],
        url: null,
        cover: { tone: 'slate', pattern: 'grid', scene: 'InventoryOps', brand: { text: 'IO', color: '#94a3b8', color2: '#1e293b' }, tagline: 'Inventory and order ops for a multi-marketplace team' },
    },
    {
        id: 'ebay-api-support',
        name: 'eBay API on Call',
        sort: '2026-04',
        when: 'Aug 2023 – Apr 2026',
        kind: 'Client project',
        client: 'eBay sellers & PHP teams · via Upwork',
        title: 'The developer eBay sellers call when the API says no.',
        body: [
            'Three Upwork contracts, each rated 5.0. A PHP team that needed eBay API help hired me again for a second, longer contract that ran just over a year.',
            'A seller whose OAuth sign-in to their own eBay seller account kept failing got their existing developer account configured for API use, so their app could finally connect.',
        ],
        attachments: [
            { type: 'review', platform: 'Upwork', rating: '5.0', quote: 'Great thank you', name: 'Nicholas Y.', job: 'Configure eBay Developer Account for API usage', date: 'Apr 2026' },
        ],
        stats: [
            { value: '3', label: 'eBay API contracts' },
            { value: '5.0', label: 'rating on every one' },
            { value: '1', label: 'client who came back' },
        ],
        tags: ['eBay API', 'PHP', 'OAuth'],
        url: null,
        cover: { tone: 'blue', pattern: 'rings', label: 'eBay API, unblocked', logos: ['eBay API', 'PHP', 'Upwork'], scene: 'EbayApiDesk', brand: { glyph: 'ebay', color: '#5aa9ff', color2: '#1d4ed8' }, tagline: 'eBay API setup, OAuth and fixes' },
    },
    {
        id: 'php-backend-jobs',
        name: 'Backend Fix-ups',
        sort: '2025-03',
        when: 'Apr 2023 – Mar 2025',
        kind: 'Client project',
        client: 'Upwork clients',
        title: 'Small, urgent backend jobs, done properly.',
        body: [
            'A run of Upwork jobs where something had to work by a deadline: a PHP/MySQL query against the US National Weather Service API, a PHP project for a client who came back to rehire me, a bug in a live PHP/MySQL backend (that client then kept me on for 13 months), a fuzzy-match join between two SQL Server tables, a PHP script rewritten in Python, and a version of an event-tracking script that sends web events to AppMetrica instead of AppsFlyer.',
            'Every rated job here came back at 4.9 or 5.0.',
        ],
        attachments: [
            { type: 'review', platform: 'Upwork', rating: '5.0', quote: 'Very timely work, delivered as promised and then more. Excellent experience.', name: 'Upwork client', job: 'PHP MySQL National Weather Service API Query', date: 'Apr 2023' },
            { type: 'review', platform: 'Upwork', rating: '5.0', quote: 'Excellent work, great patience. Would rehire for sure!', name: 'Upwork client', job: 'Rehire for php project', date: 'Apr 2023' },
        ],
        stats: [
            { value: '6', label: 'jobs' },
            { value: '13 mo', label: 'longest contract' },
            { value: '4.9–5.0', label: 'client ratings' },
        ],
        tags: ['PHP', 'MySQL', 'SQL Server', 'Python', 'API integration'],
        url: null,
        cover: { tone: 'slate', pattern: 'lines', label: 'Fixed by the deadline', logos: ['PHP', 'Python', 'MySQL'], scene: 'BackendFixups', brand: { glyph: 'php', color: '#8892bf', color2: '#3f4a7a' }, tagline: 'PHP, SQL and Python fixes on a deadline' },
    },
    {
        id: 'redis-autocomplete',
        name: 'Redis Autocomplete',
        sort: '2023-10',
        when: 'Oct 2023',
        kind: 'Client project',
        client: 'PHP web app · via Upwork',
        title: 'Search-as-you-type on Redis full-text search.',
        body: [
            'The client needed a Redis full-text (FT) search index created from PHP and queried by a JavaScript autocomplete. I set up the index and the query, wired it to the search box and delivered in two days.',
        ],
        attachments: [
            { type: 'review', platform: 'Upwork', rating: '5.0', quote: 'Shakil completed the task quickly. Very skilled. Highly recommended. A+++', name: 'Jason M.', job: 'Redis FT Search (PHP)', date: 'Oct 2023' },
        ],
        stats: [
            { value: '2 days', label: 'start to finish' },
            { value: '5.0', label: 'client rating' },
        ],
        tags: ['Redis', 'PHP', 'JavaScript'],
        url: null,
        cover: { tone: 'rose', pattern: 'dots', label: 'Redis FT search', logos: ['Redis', 'PHP', 'JavaScript'], scene: 'RedisAutocomplete', brand: { glyph: 'redis', color: '#ff4438', color2: '#9f1d17' }, tagline: 'Redis full-text autocomplete' },
    },
    {
        id: 'maps-builds',
        name: 'Map Builds',
        sort: '2023-09',
        when: '2023',
        kind: 'Client project',
        client: 'Upwork & Fiverr clients',
        title: 'Maps that do more than show a pin.',
        body: [
            'Google Maps and Places API work for client websites, including nearby-place search. One example is priceindicatura.com, the project in my Upwork portfolio, built with the Google Maps API, PHP and JavaScript.',
            'On another job I took an interactive data map a client had already put together in JavaScript and built it out so it could go live on their site, in a few days, rated 5.0. A third was a PHP customisation that combined the Google Maps API with the Google Lens API.',
        ],
        attachments: [
            { type: 'review', platform: 'Upwork', rating: '5.0', quote: 'Well done ! Good Job ! Keep It Up !', name: 'Maestro Info T.', job: 'Minor Customisation - PHP, Google Map API, Google Lens API', date: 'Jul 2023' },
        ],
        stats: [],
        tags: ['Google Maps API', 'JavaScript', 'PHP', 'Data visualization'],
        url: null,
        cover: { tone: 'teal', pattern: 'grid', label: 'Google Maps & Places API', logos: ['Google Maps API', 'JavaScript', 'PHP'], scene: 'MapBuilds', brand: { glyph: 'googlemaps', color: '#34a853', color2: '#14532d' }, tagline: 'Google Maps and Places features' },
    },
    {
        id: 'science-fest',
        name: 'Al-Khwarizmi Science Fest 2025',
        sort: '2025-11',
        when: 'Nov 2025',
        kind: 'Community',
        client: 'Organised by Bangladesh Islami Chhatra Shibir',
        title: 'The website, registrations and CTF of a national science fest.',
        body: [
            'For the Al-Khwarizmi Science Fest 2025, organised by Bangladesh Islami Chhatra Shibir, I built the event website: a landing page for the fest and a page for every event, each with its own registration form, categories and FAQs. Solo and team events both work, collecting team members, school details, a student ID photo and T-shirt size in one form.',
            'Organisers run it from an admin panel: they set up each year’s fest, its events and media, open or close registration per event, and review every registration. I built it in five days, and I was also one of the challenge setters for the fest’s CTF contest.',
        ],
        attachments: [],
        stats: [
            { value: '5 days', label: 'to build the site' },
            { value: 'Solo + team', label: 'registration, one form' },
            { value: 'CTF', label: 'challenge setter' },
        ],
        tags: ['Laravel', 'Vue', 'Filament', 'Cyber security'],
        url: null,
        cover: { tone: 'blue', pattern: 'dots', scene: 'ScienceFest', brand: { text: 'AK', color: '#26a9e0', color2: '#1d4ed8' }, tagline: 'Event site, registrations and CTF for a national science fest' },
    },
    {
        id: 'ctf-nightjar',
        name: 'Operation Nightjar',
        sort: '2026-06',
        when: 'Jun 2026',
        kind: 'Community',
        client: 'Practical Cyber Security Intensive Program 2026',
        title: 'A breached server to investigate, built as a CTF lab.',
        body: [
            'For Session 4 of the Practical Cyber Security Intensive Program 2026, I built a self-contained CTF lab. One script builds a copy of a breached internal server, and players work through it like an incident responder to capture five flags.',
            'The flags teach the basics in order: hidden files users leave behind, a file that isn’t what its extension says, a secret in plain sight that unlocks something, a scheduled job that runs as root but anyone can edit, and the attacker’s IP hidden in the logs. It needs no root, writes nothing outside its own folder, and ships with a player brief, a cheat sheet, and scripts to check and reset the lab.',
        ],
        attachments: [],
        stats: [
            { value: '5', label: 'flags, recon to forensics' },
            { value: '1', label: 'script builds the whole lab' },
            { value: '0', label: 'root access needed' },
        ],
        tags: ['Cyber security', 'Linux', 'Bash'],
        url: null,
        cover: { tone: 'slate', pattern: 'grid', scene: 'CtfNightjar', brand: { text: 'ON', color: '#3ddc97', color2: '#065f46' }, tagline: 'A CTF lab for a cyber security course' },
    },
    {
        id: 'ppmt',
        name: 'Progressive Math Test',
        sort: '2025-03',
        when: 'Jun 2024 – Mar 2025',
        kind: 'Client project',
        client: 'IGCSE maths test provider',
        title: 'An IGCSE maths test bank, organised by syllabus.',
        body: [
            'A web app for an IGCSE progressive maths test. Teachers manage the question bank in an admin panel: rich text with maths formulas and images, four options with one correct answer, filed by country, syllabus and exam, plus a CSV import that checks every row.',
            'Students can try a demo question that loads straight from the bank, and the home page has room for the top-ranked students and results.',
        ],
        attachments: [],
        stats: [
            { value: 'IGCSE', label: 'maths syllabus' },
            { value: 'CSV', label: 'bulk question import, row by row' },
        ],
        tags: ['Laravel', 'Vue', 'Filament'],
        url: null,
        cover: { tone: 'violet', pattern: 'grid', scene: 'MathTest', brand: { text: 'PM', color: '#8b7cff', color2: '#4338ca' }, tagline: 'A question bank and test site for IGCSE maths' },
    },
    {
        id: 'early-work',
        name: 'Where I started',
        sort: '2021-08',
        when: '2019 – 2021',
        kind: 'Early work',
        client: null,
        title: 'From a student portal to an e-learning platform.',
        body: [
            'My first projects, before I moved into e-commerce automation: a student portal in PHP (2019), a travel guide where visitors browse places by country and city and leave reviews (2020, with teammates), and the first version of the back end for Shikhun, an e-learning platform with courses, lessons, live lesson booking, bundles, coupons and certificates (2021, with a teammate). I also built a pharmacy app with Ionic.',
        ],
        attachments: [],
        stats: [
            { value: '2019', label: 'first project' },
            { value: '4', label: 'early projects' },
        ],
        tags: ['PHP', 'Laravel', 'Vue'],
        url: null,
        cover: { tone: 'amber', pattern: 'lines', scene: 'EarlyWork', brand: { text: 'SA', color: '#ffba52', color2: '#b45309' }, tagline: 'My first projects, 2019 – 2021' },
    },
];

// "Automations I build": the services, shaped like workflow templates.
export const recipes = [
    {
        id: 'enrich',
        title: 'AI catalog enrichment',
        pitch: 'Photos and messy titles in, complete records out: specs, manuals, prices and descriptions, approved by your team before anything goes live.',
        trigger: 'New items in your catalog',
        steps: ['Vision AI reads labels', 'Search agent finds docs', 'Rules check prices', 'Your team approves'],
        outcome: 'Catalog-ready listings with evidence',
        posts: ['quick-annotation', 'vintbay'],
    },
    {
        id: 'crosslist',
        title: 'Marketplace listing & sync',
        pitch: 'List once, sell everywhere. eBay, Shopify, Vinted and Airtable kept in step on price, stock and orders.',
        trigger: 'A new product or a stock change',
        steps: ['Map to each marketplace', 'Validate before publish', 'Sync stock both ways', 'Alert on drift'],
        outcome: 'Live listings that stay correct',
        posts: ['vintbay', 'ebay-shopify-seo', 'ebay-de', 'ebay-airtable-sync', 'ebay-api-support'],
    },
    {
        id: 'orders',
        title: 'Order & inventory ops',
        pitch: 'Orders in from any channel, stock updated, tracking pushed back, low stock flagged, without anyone copying data between tabs.',
        trigger: 'An order on any channel',
        steps: ['Import via API or webhook', 'Update stock', 'Send tracking back', 'Low-stock alerts'],
        outcome: 'One source of truth for stock',
        posts: ['saas-suite', 'inventory-ops', 'ebay-airtable-sync'],
    },
    {
        id: 'support',
        title: 'AI support & knowledge',
        pitch: 'An AI assistant that answers from your own docs, videos and data, respects who can see what, and points customers to the right upgrade.',
        trigger: 'A customer question',
        steps: ['Search your content', 'Check permissions', 'Answer with sources', 'Point to the right upgrade'],
        outcome: 'Answers customers can actually open',
        posts: ['ai-support-agent', 'pii-masking'],
    },
    {
        id: 'content',
        title: 'Document & video pipelines',
        pitch: 'Reports, PDFs, tutorials and voiceovers generated from data and scripts, so updating them is a rebuild instead of a re-do.',
        trigger: 'New data or a script change',
        steps: ['Render from templates', 'AI voice or copy', 'Automatic QA pass', 'Publish files'],
        outcome: 'Always-current docs and videos',
        posts: ['express'],
    },
    {
        id: 'monitor',
        title: 'Monitors & alerts',
        pitch: 'Automations you can leave alone: heartbeats and error-burst alerts in Slack.',
        trigger: 'A schedule or an anomaly',
        steps: ['Watch queues & logs', 'Detect bursts & gaps', 'Alert the right channel', 'Flag recovery'],
        outcome: 'Failures surface in Slack, not silently',
        posts: ['saas-suite'],
    },
];

export const process = [
    { step: 'Map', text: 'We walk through the manual process and agree what “done” looks like.' },
    { step: 'Pilot', text: 'I run it on a small batch of your real records, not demo data.' },
    { step: 'Harden', text: 'Logging, replay, safeguards and approvals so it runs unattended.' },
    { step: 'Hand over', text: 'Editable settings, a runbook and a walkthrough for your team.' },
];

// Skills. `tag` links a skill to post tags so "used in N posts" is computed, not typed.
export const skillGroups = [
    {
        id: 'ai',
        title: 'AI engineering',
        note: 'What a 2026 developer needs to ship AI that a business can trust.',
        accent: 'ai',
        skills: [
            { name: 'LLM APIs: OpenAI, Claude, Gemini', tag: ['OpenAI', 'Anthropic Claude', 'Gemini'], logos: ['OpenAI', 'Anthropic Claude', 'Gemini'] },
            { name: 'Structured outputs & JSON schema', tag: ['Structured outputs'], logos: ['code'] },
            { name: 'AI agents & tool use', tag: ['AI agents'], logos: ['agents'] },
            { name: 'Vision & image AI', tag: ['Vision', 'Computer vision'], logos: ['vision'] },
            { name: 'Retrieval & grounding', tag: ['Retrieval'], logos: ['retrieval'] },
            { name: 'Human-in-the-loop review design', tag: ['Human-in-the-loop'], logos: ['hitl'] },
            { name: 'Prompt engineering & prompt ops', tag: ['Prompt engineering'], logos: ['prompt'] },
            { name: 'Voice AI: TTS & speech-to-text', tag: ['Voice AI'], logos: ['elevenlabs'] },
            { name: 'AI privacy: PII masking & minimisation', tag: ['Privacy'], logos: ['privacy'] },
            { name: 'AI cost controls & spend caps', tag: [], logos: ['cost'] },
            { name: 'AI-assisted engineering: Claude Code, Cursor', tag: ['Claude Code'], logos: ['claude', 'cursor'] },
        ],
        learning: ['MCP servers', 'Embeddings & vector search', 'LLM evals in CI'],
    },
    {
        id: 'automation',
        title: 'Automation & integration',
        accent: 'accent',
        skills: [
            { name: 'n8n', tag: ['n8n'], logos: ['n8n'] },
            { name: 'Make & Zapier', tag: ['Make', 'Zapier'], logos: ['make', 'zapier'] },
            { name: 'Airtable automations & API', tag: ['Airtable'], logos: ['airtable'] },
            { name: 'Google Sheets API', tag: ['Google Sheets'], logos: ['sheets'] },
            { name: 'Webhooks & event-driven sync', tag: ['Webhooks'], logos: ['webhooks'] },
            { name: 'eBay APIs (Trading, Inventory, Browse…)', tag: ['eBay API'], logos: ['ebay'] },
            { name: 'Shopify Admin GraphQL & Liquid', tag: ['Shopify', 'GraphQL'], logos: ['shopify', 'graphql'] },
            { name: 'Stripe, WhatsApp, Intercom, Slack', tag: ['Stripe', 'WhatsApp API', 'Intercom Fin'], logos: ['stripe', 'whatsapp', 'intercom', 'slack'] },
            { name: 'Google Maps & Places API', tag: ['Google Maps API'], logos: ['googlemaps'] },
            { name: 'Browser automation: Playwright', tag: ['Playwright'], logos: ['playwright'] },
            { name: 'Data pipelines & business rules', tag: ['Data pipelines', 'Business rules'], logos: ['pipelines'] },
        ],
    },
    {
        id: 'backend',
        title: 'Backend',
        accent: 'ink',
        skills: [
            { name: 'PHP & Laravel (Horizon, Filament)', tag: ['Laravel', 'Filament', 'PHP'], logos: ['laravel', 'php', 'filament'] },
            { name: 'Node.js (Express, Fastify, NestJS)', tag: ['Node.js', 'Fastify'], logos: ['node', 'fastify', 'nestjs'] },
            { name: 'Python & FastAPI', tag: ['Python', 'FastAPI'], logos: ['python', 'fastapi'] },
            { name: 'REST & OpenAPI design', tag: [], logos: ['openapi'] },
            { name: 'Queues, schedulers & workers', tag: [], logos: ['queues'] },
        ],
    },
    {
        id: 'frontend',
        title: 'Frontend',
        accent: 'ink',
        skills: [
            { name: 'Vue 3 & Inertia', tag: ['Vue'], logos: ['vue', 'inertia'] },
            { name: 'React, Next.js & TypeScript', tag: ['React', 'Next.js', 'TypeScript'], logos: ['react', 'next', 'typescript'] },
            { name: 'JavaScript & interactive maps', tag: ['JavaScript', 'Data visualization'], logos: ['javascript'] },
            { name: 'Electron desktop apps', tag: ['Electron'], logos: ['electron'] },
            { name: 'Tailwind CSS', tag: [], logos: ['tailwind'] },
        ],
    },
    {
        id: 'infra',
        title: 'Data & infrastructure',
        accent: 'ink',
        skills: [
            { name: 'MySQL, PostgreSQL, MongoDB, SQL Server', tag: ['MySQL', 'PostgreSQL', 'MongoDB', 'SQL Server'], logos: ['mysql', 'postgres', 'mongodb'] },
            { name: 'Redis (incl. full-text search)', tag: ['Redis'], logos: ['redis'] },
            { name: 'Docker & Caddy', tag: ['Docker'], logos: ['docker', 'caddy'] },
            { name: 'AWS, DigitalOcean, VPS', tag: [], logos: ['aws', 'digitalocean'] },
            { name: 'CI security: Gitleaks, Opengrep', tag: ['GitLab CI'], logos: ['gitlab'] },
            { name: 'Media: ffmpeg', tag: ['ffmpeg'], logos: ['ffmpeg'] },
            { name: 'Security: CTF labs, Linux forensics', tag: ['Cyber security', 'Linux', 'Bash'], logos: ['privacy'] },
        ],
    },
];

// Experience. `achievements` are the headline numbers, `points` the work, `projects` post ids shown as
// thumbnails (they open the case study or the live site). Commit counts are from the repos' git history (Oct 2026).
export const experience = [
    {
        role: 'Senior Software Engineer',
        org: 'Dropmatix Systema SL',
        logo: '/images/brands/dropmatix-icon.png',
        mono: 'DX',
        tone: 'violet',
        href: 'https://dropmatix.com',
        place: 'Balearic Islands, Spain · Remote',
        when: 'Jan 2022 – Present',
        current: true,
        achievements: [
            { value: '5,600+', label: 'commits to the core platform, 2nd most on the team' },
            { value: '#1', label: 'contributor to the background sync service' },
            { value: 'Since 2020', label: 'on the platform (first via Luminous Labs)' },
        ],
        points: [
            'Core developer on a multi-tenant SaaS business suite: integrations, payments, public API and launch hardening.',
            'Sales-channel and supplier automation: eBay, Amazon, OTTO, Kaufland, Shopify and WooCommerce, plus product imports, exports and feeds.',
            'Background sync that keeps stock and orders moving between suppliers and channels.',
            'Performance work on million-row tables, queues and background jobs.',
        ],
        projects: ['saas-suite', 'ai-support-agent', 'pii-masking'],
    },
    {
        role: 'CTO',
        org: 'Okkhor',
        logo: '/images/brands/okkhor-icon.png',
        mono: 'OK',
        tone: 'amber',
        href: 'https://okkhor.com',
        place: 'okkhor.com · Bangladesh’s online bookstore',
        when: 'Dec 2022 – Present',
        current: true,
        achievements: [
            { value: '4,000+', label: 'books in the catalog' },
            { value: '1,800+', label: 'authors' },
            { value: '120', label: 'publishers' },
        ],
        points: [
            'Lead the tech across the storefront (Next.js), the API (Node.js, MongoDB, Redis), the admin panels and the publisher portal.',
            'Led the 2026 storefront redesign: navigation, book cards, banners, cart and checkout.',
            'Built Admin v2 and the publisher portal’s campaign requests.',
            'Backend work on caching, query speed, Bengali search and order pricing.',
        ],
        projects: ['okkhor'],
    },
    {
        role: 'Freelance Automation Engineer',
        org: 'Independent',
        logo: null,
        mono: 'SA',
        tone: 'blue',
        href: 'https://www.upwork.com/freelancers/~01a0defc0f412196fa',
        place: 'Upwork, Fiverr & direct · clients in the UK, US, Germany, France, Austria & the Netherlands',
        when: '2023 – Present',
        current: true,
        achievements: [
            { value: 'Top Rated', label: 'on Upwork' },
            { value: '100%', label: 'Job Success on Upwork' },
            { value: '5.0', label: 'from 10 Upwork reviews' },
            { value: '4.9', label: 'on Fiverr' },
        ],
        points: [
            'n8n and LLM pipelines with human review, marketplace sync, and AI features in existing apps.',
            'eBay API, Google Maps and PHP/Python backend work for clients on Upwork and Fiverr.',
        ],
        projects: ['quick-annotation', 'vintbay', 'deal-finder', 'ebay-de', 'ebay-shopify-seo', 'express'],
    },
    {
        role: 'Software Engineer',
        org: 'Luminous Labs',
        logo: '/images/brands/luminouslabs-icon.png',
        mono: 'LL',
        tone: 'amber',
        href: 'https://luminouslabsbd.com',
        place: 'Dhaka, Bangladesh',
        when: 'Apr 2019 – Dec 2021',
        achievements: [
            { value: '2021', label: 'Employee of the Year' },
            { value: 'May 2021', label: 'Best Employee of the Month' },
            { value: '2,800+', label: 'commits to the Dropmatix platform, 2020–21' },
        ],
        points: [
            'Laravel, Angular and Node.js apps, automation workflows and microservices.',
            'From April 2020, part of the team building the Dropmatix platform, which I later joined directly.',
        ],
        projects: [],
    },
];

export const education = {
    degree: 'B.Sc. in Software Engineering',
    school: 'Daffodil International University',
    when: '2017 – 2021',
    detail: 'CGPA 3.56 / 4.00',
    logo: null,
    mono: 'DIU',
};

export const faqs = [
    { q: 'Are you available?', a: 'Yes. I take on automation and AI projects alongside my role at Dropmatix. Tell me what you want automated and I’ll reply with a plan.' },
    { q: 'How do you price work?', a: 'Usually fixed price per milestone after a short scoping call, so you know the cost up front. Small fixes can be hourly.' },
    { q: 'What if the AI gets it wrong?', a: 'It’s designed for that. AI output is a proposal until a person approves it, every value records its source, and anything the model can’t justify is flagged instead of guessed.' },
    { q: 'n8n, Make, Zapier or custom code?', a: 'Whatever fits. No-code for quick wins your team can maintain, and Laravel, Node or Python when you need volume, testing or logic the connectors can’t handle.' },
    { q: 'What time zone are you in?', a: 'GMT+6 (Bangladesh). I work with teams across Europe and the UK and am comfortable working async.' },
];

export const nowBuilding = {
    name: 'eBayfy',
    text: 'A Shopify ↔ eBay sync engine where every stock and price write is checked against the marketplace, instead of fired and forgotten.',
    status: 'Design docs done · laying the foundation (skeleton & CI in place)',
};

// Recommendations from clients or colleagues. Only add real, permitted quotes, copied word for word.
// { quote: '…', name: 'Jane Doe', role: 'Head of Ops', company: 'Acme', platform: 'Upwork' | 'Fiverr', rating: '5.0', date: 'Oct 2023', post: 'post-id', avatar: '/images/people/jane.jpg', url: 'https://…' }
// Client photos: put the file in public/images/clients/ and set `avatar: '/images/clients/jason.jpg'`.
// Without one, the card shows the client's initials. Fiverr reviews still to add: paste them from the gig page.
// Fiverr dates: the gig shows relative dates ("1 year ago"), converted on 2026-10-29.
export const testimonials = [
    {
        quote: 'Excellent experience! [Shakil] has outstanding API knowledge and solved a complex integration quickly and professionally. Communication was clear, fast, and solution-oriented. He understood the requirements immediately and delivered clean, reliable results. I would definitely work with him again and highly recommend him to anyone needing API or automation help.',
        name: 'Gerridt',
        role: 'Fiverr client',
        country: 'Germany',
        platform: 'Fiverr',
        rating: '5.0',
        date: 'Dec 2025',
        duration: '8 days',
        price: '$100–$200', // shown on Fiverr; not displayed here
        avatar: '/images/clients/gerridt.jpg',
        url: 'https://www.fiverr.com/shakilanwar/',
    },
    {
        quote: 'Better than I expected. Shakil is exceptional at his work and very attentive. Thank you again.',
        name: 'Morgan',
        role: 'Fiverr client',
        country: 'United Kingdom',
        platform: 'Fiverr',
        rating: '5.0',
        date: 'Sep 2026',
        duration: '2 days',
        price: '$400–$600', // shown on Fiverr; not displayed here
        avatar: null,
        url: 'https://www.fiverr.com/shakilanwar/',
    },
    {
        quote: 'Fast delivery and very helpful young man! Managed to fix what I’ve been trying for 6 days in 1 day. Would recommend!',
        name: 'dyonbouhof',
        role: 'Fiverr client',
        country: 'Netherlands',
        platform: 'Fiverr',
        rating: '5.0',
        date: '2025',
        duration: '2 days',
        price: '$100–$200', // shown on Fiverr; not displayed here
        avatar: '/images/clients/dyonbouhof.jpg',
        url: 'https://www.fiverr.com/shakilanwar/',
    },
    {
        quote: 'Shakil completed the task quickly. Very skilled. Highly recommended. A+++',
        name: 'Jason M.',
        role: 'Upwork client',
        company: 'Redis FT Search (PHP)',
        platform: 'Upwork',
        rating: '5.0',
        date: 'Oct 2023',
        post: 'redis-autocomplete',
        avatar: null,
        url: 'https://www.upwork.com/freelancers/~01a0defc0f412196fa',
    },
    {
        quote: 'Shakil Anwar delivered EXCEPTIONALLY! His professionalism, attention to detail, and ability to exceed expectations truly stood out. 😊 Working with him was a breeze; his politeness, going above and beyond, and language fluency made the process smooth. Very thorough and knowledgeable with APIs. Will definitely return for future projects! 👍',
        name: 'Osban Cerejo',
        role: 'Fiverr client',
        country: 'United States',
        platform: 'Fiverr',
        rating: '5.0',
        date: '2024',
        duration: '2 days',
        price: '$50–$100', // shown on Fiverr; not displayed here
        avatar: null,
        url: 'https://www.fiverr.com/shakilanwar/',
    },
    {
        quote: 'Very timely work, delivered as promised and then more. Excellent experience.',
        name: 'Upwork client',
        role: 'Upwork client',
        company: 'PHP MySQL National Weather Service API Query',
        platform: 'Upwork',
        rating: '5.0',
        date: 'Apr 2023',
        post: 'php-backend-jobs',
        anonymous: true, // Upwork hides this client's name
        avatar: null,
        url: 'https://www.upwork.com/freelancers/~01a0defc0f412196fa',
    },
    {
        quote: 'Delivered faster than quoted. Was able to complete my custom job that was not a standard service offered. He was patient with me and my much more limited understanding of coding. Offered tips on best practices in my implementation. Uses great English.',
        name: 'ephemeralechoes',
        role: 'Fiverr client',
        country: 'United States',
        platform: 'Fiverr',
        rating: '5.0',
        date: '2024',
        duration: '2 days',
        price: '$200–$400', // shown on Fiverr; not displayed here
        avatar: null,
        url: 'https://www.fiverr.com/shakilanwar/',
    },
    {
        quote: 'Excellent work by Shakil! Shakil delivered a perfectly working API integration with clean, efficient, and well-structured code. Communication was smooth, fast, and professional throughout the project. He fully understood the requirements, implemented everything exactly as discussed, and even added a few smart improvements on his own. I highly recommend Shakil to anyone looking for a reliable and skilled developer for API or automation projects — I’ll definitely work with him again!',
        name: 'Gerridt',
        role: 'Fiverr client',
        country: 'Germany',
        platform: 'Fiverr',
        rating: '5.0',
        date: '2025',
        duration: '6 days',
        price: '$100–$200', // shown on Fiverr; not displayed here
        avatar: '/images/clients/gerridt.jpg',
        url: 'https://www.fiverr.com/shakilanwar/',
    },
    {
        quote: 'Excellent work, great patience. Would rehire for sure!',
        name: 'Upwork client',
        role: 'Upwork client',
        company: 'Rehire for php project',
        platform: 'Upwork',
        rating: '5.0',
        date: 'Apr 2023',
        post: 'php-backend-jobs',
        anonymous: true, // Upwork hides this client's name
        avatar: null,
        url: 'https://www.upwork.com/freelancers/~01a0defc0f412196fa',
    },
    {
        quote: 'Well done ! Good Job ! Keep It Up !',
        name: 'Maestro Info T.',
        role: 'Upwork client',
        company: 'Minor Customisation - PHP, Google Map API, Google Lens API',
        platform: 'Upwork',
        rating: '5.0',
        date: 'Jul 2023',
        post: 'maps-builds',
        avatar: null,
        url: 'https://www.upwork.com/freelancers/~01a0defc0f412196fa',
    },
];
