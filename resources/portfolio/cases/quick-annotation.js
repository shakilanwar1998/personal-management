export default {
    summary:
        'A reseller of used process and lab equipment had a catalog full of photos and almost no data behind them. I built an n8n pipeline that reads each nameplate, finds the real manual, drafts the specs and prices the item from filtered market offers, then hands it all to a reviewer. No catalog record changes until a person approves it.',
    facts: [
        { label: 'Client', value: 'European used-equipment reseller' },
        { label: 'My role', value: 'Solution design, n8n build, prompts, review-app integration, docs' },
        { label: 'Timeline', value: 'Apr – Jun 2026 · 4 milestones' },
        { label: 'Pilot', value: '20 real catalog items' },
    ],
    sections: [
        {
            heading: 'The problem',
            body: [
                'The catalog had photos but no answers. A typical record was a short title like “Homogenizer with feed pump”, a brand name and a few pictures. Before an item could go on sale, someone had to read the nameplate, hunt for a manual, compare prices across dealers and auctions, write a description, type it all into the catalog and then prepare the eBay listing as well.',
                'That was hours of research per item, and none of it could be repeated or checked afterwards.',
            ],
            bullets: [
                'Brand, model and year were pieced together from blurry photos and Google, one item at a time.',
                'Prices were a gut feel. Search results for used equipment mix real offers with spare parts, accessories and odd auctions.',
                'Real manuals were buried among shop listings, forum threads and parts catalogs.',
                'Nobody could see which source justified a model number or a price, so mistakes were hard to catch.',
            ],
            callout: {
                title: 'The client’s rules',
                text: 'The catalog stays the only source of truth, with no silent overwrites. AI output is a proposal until a person approves it. Every step can be run, checked and re-run on its own. And people who aren’t developers can change prompts, rules and prices.',
            },
        },
        {
            heading: 'How it works',
            body: [
                'Quick Annotation is a single n8n workflow. It pulls equipment from the catalog’s REST API, has AI research each item and stores the results as proposals, never as catalog edits. Each stage has a fixed input and output, keeps its own log and can be re-run by itself.',
            ],
            figure: {
                type: 'pipeline',
                steps: [
                    { label: 'Catalog', sub: 'batch pull', kind: 'trigger' },
                    { label: 'Vision', sub: 'GPT-4o', kind: 'ai' },
                    { label: 'Manual', sub: 'search agent', kind: 'ai' },
                    { label: 'Draft', sub: 'specs + price', kind: 'rule' },
                    { label: 'Reviewer', sub: 'per field', kind: 'human' },
                    { label: 'Write-back', sub: 'catalog, XLSX', kind: 'out' },
                ],
            },
            callout: {
                title: 'The real work',
                text: 'Calling an LLM was the easy part. Making its output reliable enough to write into a production catalog was the job.',
            },
        },
        {
            heading: 'Stage by stage',
            steps: [
                { title: 'Ingest', text: 'Pages through the catalog in batches with a safety cut-off, or takes a list of QR codes. Items already pending or approved are skipped, so a re-run never wipes a reviewer’s work.' },
                { title: 'Annotate', text: 'Sends up to 8 photos per item to GPT-4o vision, which copies nameplate text exactly and returns strict JSON: brand, model, year, type, condition, confidence and an estimated new price. If the photo and the catalog text disagree, the photo wins.' },
                { title: 'Find the manual', text: 'Searches for brand + model + “user manual” and scores up to 20 results to decide which are real technical documents. Items with no brand or model are logged and skipped, not searched blindly.' },
                { title: 'Specify', text: 'Combines the catalog entry, the photo findings and the accepted documents into a checked brand and model, a description and standard key specs. The model can’t browse; it only uses the evidence it is given.' },
                { title: 'Price', text: 'Sets a reference price for the item new, then works out new, refurbished and used ranges from filtered market offers in euros.' },
                { title: 'Review, then write back', text: 'A reviewer approves each field. Approved fields go to the catalog straight away, the manual is attached as a file, and approved records are exported in the Excel format the eBay import uses.' },
            ],
            callout: {
                title: 'Fewer tokens, same separation',
                text: 'Specify and Price share one structured LLM call but return separate outputs, so each stage can still be checked on its own.',
            },
        },
        {
            heading: 'Telling a manual from a shop listing',
            body: [
                'Search for any piece of used equipment and most results are shops selling it. The search agent applies four rule checks before a link counts as a manual, and the LLM applies a fifth.',
            ],
            bullets: [
                'Brand and model must match: words are normalised, filler like “GmbH” or “Inc” is ignored, and at least half must match.',
                'Marketplaces such as eBay, Amazon and AliExpress are blocked outright.',
                'URL paths like /manuals/ or /support/ score higher; /product/ or /shop/ are rejected.',
                'The result must use document words: manual, datasheet, operator, service guide, handbook.',
                'Finally the LLM rejects parts lists, forum threads and category pages that slipped through.',
            ],
            figure: {
                type: 'terminal',
                title: 'manual search · “<brand> <model> user manual”',
                illustrative: true,
                lines: [
                    { t: 'ok', tone: 'ok', text: 'Operating & Maintenance Manual   manufacturer · /support/manuals/…pdf   match 1.00' },
                    { t: 'drop', tone: 'warn', text: 'Used homogenizer for sale         marketplace · /itm/…                 marketplace' },
                    { t: 'drop', tone: 'warn', text: 'In stock, ready to ship           dealer · /shop/product/…             listing path' },
                    { t: 'drop', tone: 'warn', text: 'Valve leaking, any tips?          forum · /thread/…                    match 0.33' },
                    { t: 'drop', tone: 'ai', text: 'Seal kit & spare parts list       parts supplier · /downloads/…pdf     LLM: parts list' },
                ],
            },
        },
        {
            heading: 'Prices a reviewer can check',
            body: [
                'A price only helps if the reviewer can see where it came from. The pricing stage combines an AI estimate with fixed rules and real market offers.',
            ],
            steps: [
                { title: 'Reference price', text: 'Estimates what the complete unit costs new, using euro-priced offers only. Currencies are never converted.' },
                { title: 'Condition ranges', text: 'New is 80–110% of the reference, refurbished 30–80% and used 10–30%.' },
                { title: 'Market evidence', text: 'Searches offers in the client’s local market and filters out spare parts and accessories.' },
                { title: 'Outlier removal', text: 'Drops outliers with the interquartile range (IQR × 1.5), only when there are at least 4 offers. With thin data it falls back to an estimate with capped confidence.' },
            ],
            callout: {
                title: 'Example (illustrative figures)',
                text: 'A used homogenizer: 7 offers found, 2 removed as outliers, median of the rest €10,500. That sits inside the 10–30% used range of the reference price, and the reviewer can open every offer behind it.',
            },
        },
        {
            heading: 'People make the call',
            body: [
                'Reviewers work in a queue inside the client’s Laravel listing tool, which talks to n8n through two webhooks: one loads proposals, the other records decisions. Each proposal sits next to the original record with its evidence one click away.',
                'Approval is per field, so a correct brand can go live while the price is still being checked. A record moves from Pending to Partial to Approved, and nothing reaches the catalog without an explicit yes.',
            ],
            figure: {
                type: 'fields',
                caption: 'One proposal, six fields to sign off',
                illustrative: true,
                rows: [
                    { field: 'Brand', value: 'Read from nameplate · confidence 0.94', source: 'AI' },
                    { field: 'Model', value: 'Dataplate text, matches manual title', source: 'AI' },
                    { field: 'Description', value: 'Drafted from catalog entry + manual', source: 'AI' },
                    { field: 'Specifications', value: 'Taken from the operating manual', source: 'AI' },
                    { field: 'Price', value: '€10,500 used · 5 of 7 offers kept', source: 'Rule' },
                    { field: 'Manual', value: 'PDF, at least 10 pages · attached', source: 'Rule' },
                ],
            },
        },
        {
            heading: 'Built to run without a developer',
            body: [
                'The first version had prompts, keyword lists, price ranges and API keys written into the workflow, so every change needed me. In the fourth milestone I moved every one of those settings into a single Google Sheet, read once at the start of each run.',
            ],
            bullets: [
                'Keyword and block lists grow as rows are added; blank cells fall back to safe defaults, and the workflow never writes to the sheet.',
                'Switching to another catalog account is one cell, and proposals, lookups and approvals follow it.',
                'Every step logs its input, output and any error by equipment ID and run ID. A replay endpoint re-runs a single item, and a failed step leaves a checkpoint to resume from.',
                'Records are updated in place, never duplicated. Malformed JSON is flagged rather than guessed, and categories are checked against a fixed list.',
                'Runs on a schedule, from a webhook, or for a chosen list of items. A separate job takes an XLSX, enriches each row in the background and posts the finished file to a callback URL.',
                'Handed over with a user guide for non-developers, an operations runbook, a troubleshooting table and a recorded walkthrough.',
            ],
        },
        {
            heading: 'What changed for the team',
            body: [
                'Delivered in four milestones, each with written deliverables and pass conditions, so the client could accept the work step by step. Reviewers now check a finished draft with the evidence attached instead of starting from a blank form.',
            ],
            bullets: [
                'Brand and model: from zooming into photos to a vision model quoting the nameplate text it saw, with a confidence score.',
                'Manuals: from searching Google by hand to filtered results, attached only if the file is a PDF of at least 10 pages.',
                'Prices: from a guess off a few listings to new, refurbished and used ranges built on filtered market offers.',
                'Catalog updates: from copy and paste to one click per approved field, sent through the API.',
                'Rule changes: from asking a developer to editing a cell.',
                'Audit trail: from none to evidence, decisions and errors saved for every item and run.',
            ],
        },
    ],
    lessons: [
        'With AI in a production system, most of the work is around the model: fixed contracts between stages, validation, logs and a person who signs off.',
        'Approving field by field matters. If reviewers can only accept or reject a whole record, one doubtful price holds back everything that was right.',
        'Settings that business people will want to change belong outside the workflow from the start. Moving them later took a whole milestone.',
    ],
};
