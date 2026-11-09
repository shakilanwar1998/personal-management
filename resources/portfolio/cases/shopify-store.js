export default {
    summary:
        'A fragrance retailer was considering a Shopify store. To show them what it could be rather than describe it, I built a working prototype in about a day: a storefront designed around how people shop for perfume, and Python tooling that sets up the whole store (fields, products, collections, menu and images) through Shopify’s Admin GraphQL API.',
    facts: [
        { label: 'Client', value: 'Fragrance retailer (prospect)' },
        { label: 'My role', value: 'Sole developer' },
        { label: 'Timeline', value: 'Sep 2026 · prototype in about 24 hours' },
        { label: 'Stack', value: 'Shopify (Dawn theme) · Admin GraphQL · Python' },
    ],
    sections: [
        {
            heading: 'Shopping the way people buy perfume',
            body: [
                'People choose perfume by house, by scent and by bottle size, and they often want a sample before a full bottle. The storefront is built around that, on Shopify’s free Dawn theme with the design replaced.',
            ],
            bullets: [
                'Product cards show the house above the name, up to three sizes, the price range and the best discount on offer.',
                'Every size on the product page shows its own price, and sold-out sizes are struck through rather than hidden. Every perfume comes in 5 ml and 10 ml samples, and larger bottles cost less per millilitre.',
                'Filters for house, gender, scent family, concentration, scent notes, size, price and availability.',
                'An A–Z brands page with type-to-filter, a hero slideshow that pauses on hover and respects reduced motion, and product rails tuned for phones.',
            ],
        },
        {
            heading: 'A store set up by script, not by clicking',
            body: [
                'Setting up a Shopify store by hand means hours in the admin, and nobody can repeat it exactly. I wrote Python scripts that do it through the Admin GraphQL API, in order: the custom product fields, the products with their sizes and stock, publishing, the collections, the brands page and the main menu.',
                'Each step looks up what already exists before it creates anything, so running a script again changes nothing. They also respect Shopify’s rate limits: every response reports how much query budget is left, and the scripts pause when it runs low and retry with back-off when the API is busy.',
            ],
            figure: {
                type: 'pipeline',
                steps: [
                    { label: 'Catalogue', sub: 'CSV', kind: 'trigger' },
                    { label: 'Products', sub: '8 fields, sizes', kind: 'rule' },
                    { label: 'Publish', sub: 'every page', kind: 'rule' },
                    { label: 'Collections', sub: 'menu, images', kind: 'rule' },
                    { label: 'Storefront', sub: 'theme push', kind: 'out' },
                ],
            },
        },
        {
            heading: 'Filling in the scent notes',
            body: [
                'To make the demo feel real, the catalogue was built from public product pages of a large online fragrance retailer, used only on the password-protected demo store. Those pages rarely list the classic top, heart and base notes, so the first count of products with notes was zero.',
                'The notes were in the descriptions, though. I scanned each one for an 88-term list of notes, matching whole words only, dropping a term when a longer one contained it, and keeping at most ten per product. That found notes for 305 of the 317 perfumes, about seven each. The scent family is scored from the notes, replacing a first-match rule that had labelled 58% of the catalogue Gourmand or Amber.',
            ],
            figure: {
                type: 'bars',
                caption: 'Scent notes found in 317 product descriptions',
                rows: [
                    { label: 'notes found', value: 305, tone: 'ok' },
                    { label: 'no known note in the text', value: 12, tone: 'muted' },
                ],
            },
        },
        {
            heading: 'The bug that said nothing',
            body: [
                'After the full import, 67 perfumes were created but not for sale, and the script had reported success. The cause: it asked Shopify for products(first: 250) and treated the answer as the whole catalogue. 250 is the size of one page, not a total, and 317 − 250 = 67.',
                'The fix was to follow Shopify’s page cursor until there are no more pages. I then found the same trap in the tagging and image-upload scripts and fixed it there too.',
            ],
            figure: {
                type: 'terminal',
                title: 'import-catalog.py',
                illustrative: true,
                lines: [
                    { t: '$', tone: 'ink', text: 'python scripts/import-catalog.py --confirm' },
                    { t: '', tone: 'ok', text: 'created 317 products' },
                    { t: '', tone: 'warn', text: 'published 250 · products(first: 250) is one page' },
                    { t: '', tone: 'muted', text: 'fix: follow pageInfo.endCursor until hasNextPage is false' },
                    { t: '', tone: 'ok', text: 'published 317 · 0 left unpublished' },
                ],
            },
        },
        {
            heading: 'Checks Shopify doesn’t do',
            body: [
                'Shopify’s Theme Check reports 0 errors on the theme. But some mistakes it doesn’t look at, and Shopify ignores them silently: a setting value that isn’t on its slider’s step, a block type a section doesn’t declare, HTML in a plain-text setting. I wrote two small validators for theme settings and templates. They caught exactly these, including HTML that Shopify then rejected on upload.',
            ],
        },
        {
            heading: 'From prototype to proposal',
            body: [
                'The prototype did its job: it turned a vague “we want an online store” into concrete decisions. My proposal for the real build follows from it: decide the product fields first, because they drive the filters; one listing per perfume with sizes as options; a bulk-upload spreadsheet so the retailer’s own staff can load their catalogue; and the usual reviews, email, WhatsApp, analytics and shopping-feed setup.',
                'The demo’s product photos came from the reference catalogue and were for the private demo only. The plan replaces them with licensed images before anything goes live.',
            ],
        },
    ],
    lessons: [
        'products(first: 250) is a page, not your catalogue. Any API that pages its results needs a loop, even when your data fits today.',
        'Scripts that look before they create can be re-run without fear, which makes the store’s setup something you can repeat and review.',
        'A working prototype answers questions a document can’t. It’s easier to agree on filters when you can click them.',
    ],
};
