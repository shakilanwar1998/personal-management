export default {
    summary:
        'A French industrial-parts reseller sold on eBay and wanted the same stock in its own Shopify shop, with copy written for search rather than for eBay. I built a Laravel app that picks up new eBay listings, has OpenAI rewrite the title and description for SEO, publishes them to Shopify and keeps stock in step between the two. Anything that fails waits in a dashboard with a retry button.',
    facts: [
        { label: 'Client', value: 'French industrial-parts reseller' },
        { label: 'My role', value: 'Sole developer' },
        { label: 'Timeline', value: 'Jun – Jul 2025' },
        { label: 'Stack', value: 'Laravel · Filament · eBay Trading API · Shopify · OpenAI · Make' },
    ],
    sections: [
        {
            heading: 'The problem',
            body: [
                'The shop’s whole catalogue lived on eBay.fr. Copying each part into Shopify by hand was slow, and a title written for eBay’s search doesn’t make a good web page title or meta description.',
                'Stock was the other risk. A part that sells on one channel has to disappear from the other quickly, or the shop sells something it no longer has.',
            ],
        },
        {
            heading: 'From eBay listing to Shopify product',
            steps: [
                { title: 'Spot new listings', text: 'A scheduled check reads the seller’s listings from eBay and saves any it hasn’t seen, with price, quantity, SKU and photos.' },
                { title: 'Write the SEO copy', text: 'Each new listing goes to an OpenAI step that returns structured JSON: a title, meta title, description and meta description.' },
                { title: 'Publish to Shopify', text: 'The app creates the Shopify product with that copy, the eBay photos, price, SKU and stock, and links the two records so later updates find each other.' },
                { title: 'Keep stock in step', text: 'A Shopify inventory webhook updates the eBay quantity, scheduled jobs push Shopify price and stock changes to eBay, and new eBay orders update the Shopify product.' },
            ],
            figure: {
                type: 'pipeline',
                steps: [
                    { label: 'eBay', sub: 'hourly check', kind: 'trigger' },
                    { label: 'OpenAI', sub: 'SEO JSON', kind: 'ai' },
                    { label: 'Shopify', sub: 'publish', kind: 'out' },
                    { label: 'Stock sync', sub: 'every 15 min', kind: 'rule' },
                    { label: 'Team', sub: 'retry on fail', kind: 'human' },
                ],
            },
        },
        {
            heading: 'Make.com first, then the app',
            body: [
                'I started the AI step as a Make.com scenario. The app sent each new listing to Make, the scenario called OpenAI, and the finished copy came back to a webhook in the app, which then created the Shopify product.',
                'That was quick to set up and easy to change. Once the volume justified it, I moved the AI step into the app itself, so the whole flow runs and is logged in one place.',
            ],
        },
        {
            heading: 'Nothing gets lost',
            body: [
                'Every listing has a status: pending, processing, synced or failed. If the AI step or an API call fails, the listing simply waits in a Filament dashboard instead of disappearing.',
            ],
            bullets: [
                'A Retry button on each listing, and a bulk retry for every failed one, sends it through the AI step again.',
                'A “Sync from eBay” button runs the new-listings check on demand instead of waiting for the schedule.',
                'Every sync event is written to a log the team can read in the same dashboard, with the item it concerns.',
                'Price and stock are only pushed when they actually changed, which keeps API calls down and the logs readable.',
                'Only eBay.fr listings belong in the shop. A clean-up job finds any that don’t and removes them from Shopify too.',
            ],
            callout: {
                title: 'The point of the retry button',
                text: 'AI and API calls fail now and then. Instead of hiding that, the dashboard makes it a one-click job for the team, so a failed run never means a missing product.',
            },
        },
    ],
    results: [
        { value: '4 fields', label: 'of SEO copy written per product' },
        { value: '2-way', label: 'stock sync between eBay and Shopify' },
        { value: '1 click', label: 'to retry a failed listing' },
    ],
    lessons: [
        'A no-code tool is a fine place to start an AI step while the prompt is still changing. Move it into code when the volume makes it worth it.',
        'Give every record a visible status. When something fails, the team can see it and fix it without calling me.',
    ],
};
