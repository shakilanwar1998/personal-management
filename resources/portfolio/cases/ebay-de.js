export default {
    summary:
        'A German vintage hi-fi reseller kept its stock in Airtable but typed every eBay.de listing by hand. I built a small Laravel service behind a set of webhooks, so the team fills in a record, presses a button and gets a live listing with the right category, item specifics, policies, photos and video.',
    facts: [
        { label: 'Client', value: 'German vintage hi-fi reseller' },
        { label: 'My role', value: 'Sole developer' },
        { label: 'Timeline', value: 'Jun 2025 – Feb 2026' },
        { label: 'Stack', value: 'Laravel · eBay Trading, Taxonomy & Media APIs · Airtable' },
    ],
    sections: [
        {
            heading: 'The problem',
            body: [
                'Every amplifier, turntable and speaker is a one-off, so every item needs its own listing. On eBay.de that means choosing a category out of thousands, filling the item specifics that category demands, picking payment, return and shipping policies, and uploading photos.',
                'The team already kept everything in Airtable: titles, brand, model, condition, price, weight. The data was there. Getting it onto eBay was still copy and paste, one listing at a time.',
            ],
        },
        {
            heading: 'How it works',
            body: [
                'Airtable stays the place where the team works. Its automations call my webhooks, and each webhook does one job and writes its result back to the record, so the team can see every step and correct it before anything goes live.',
            ],
            steps: [
                { title: 'Pick the category', text: 'Title, brand and model go to eBay’s category suggestions for the German site. The top suggestion is selected; the full list is kept on the record in case the team wants another.' },
                { title: 'List the specifics', text: 'The service fetches the item aspects for that category and returns which ones eBay requires, so the team knows exactly what to fill. Aspects are cached per category, so repeat categories don’t call eBay again.' },
                { title: 'Load the policies', text: 'Payment, return and fulfilment policies and the shop’s location keys are pulled from eBay into Airtable, so shipping is chosen from a list instead of typed.' },
                { title: 'Publish', text: 'One call builds the listing and sends it to eBay.de as fixed price, with optional Best Offer, or as an auction with a start price. The new listing’s details come back to the record.' },
            ],
            figure: {
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
        },
        {
            heading: 'The AI step we took out',
            body: [
                'My first version used OpenAI twice: once to choose the best category from eBay’s suggestions, and once to draft every item specific from the title, brand and model.',
                'It worked, but once the team’s review habits were clear, something simpler did the job better: eBay’s own top category suggestion, plus a clear list of the fields that category requires, instead of an AI guess at all of them. So the live flow uses those two, and the AI step was dropped.',
            ],
            callout: {
                title: 'Why this mattered',
                text: 'The prototype showed where AI wasn’t needed. The live flow has fewer moving parts and no per-listing AI cost, and nothing gets filled in that the team didn’t check.',
            },
        },
        {
            heading: 'Photos, video and the jobs around a listing',
            bullets: [
                'An upload page where staff enter a SKU and drop in photos and a video. Files go to cloud storage and are attached to the matching Airtable record automatically, with previews and a progress bar.',
                'Product videos are uploaded through eBay’s Media API and attached to the listing.',
                'Ending a listing takes its eBay link from Airtable. The service ends it as fixed price first and falls back to ending it as an auction.',
                'Files for a SKU can be deleted in one call when an item is gone.',
                'When eBay rejects something, the reason comes back as one readable message on the record instead of a raw API error.',
            ],
        },
    ],
    results: [
        { value: '1 button', label: 'from Airtable record to live eBay.de listing' },
        { value: '2 formats', label: 'fixed price (with Best Offer) and auction' },
        { value: '0', label: 'AI calls in the live listing flow' },
    ],
    lessons: [
        'Build the AI version first if it’s quick, then watch what people actually keep. Here the platform’s own suggestion was good enough.',
        'Let the tool the team already uses stay the front end. They never had to learn a new screen.',
        'One webhook per job, each writing back to the record, makes problems easy to find and fix.',
    ],
};
