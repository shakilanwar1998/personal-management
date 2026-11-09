export default {
    summary:
        'A UK reseller buys second-hand fashion on Vinted and sells it on eBay, and every listing was retyped and re-photographed by hand. I built a platform that turns a Vinted link into an eBay listing that passes eBay’s checks, keeps it in step with Vinted afterwards, and leaves every judgement call to a person. It was live in four weeks and has kept growing since.',
    facts: [
        { label: 'Client', value: 'UK second-hand fashion reseller' },
        { label: 'My role', value: 'Architecture, full-stack build, computer vision, deployment' },
        { label: 'Timeline', value: '22 Aug – 26 Oct 2026 · live in 4 weeks' },
        { label: 'Scale', value: '≈31k lines of app code + 17k of tests' },
    ],
    sections: [
        {
            heading: 'The problem',
            body: [
                'Cross-listing second-hand fashion is death by a thousand fields. A few listings a day is tedious; at volume it’s the whole job. And the two marketplaces make it harder than copy and paste.',
            ],
            bullets: [
                'Vinted has no public API. Its pages change shape without notice and sit behind bot protection.',
                'eBay is strict. Every category has its own required item specifics, each with a list of allowed values that changes over time. “S / UK 8-10” isn’t a size eBay accepts.',
                'Vinted photos are taken on beds, floors and doors. eBay favours a clean main photo at 1600px, the size that turns on zoom.',
                'A retry, a double click or a second seller account can list the same item twice, and two listings for one item means one angry buyer.',
                'When an item sells or changes price on Vinted, the eBay listing has to follow before a second buyer pays.',
            ],
            callout: {
                title: 'What success had to look like',
                text: 'Paste a URL and get a listing that passes eBay’s validation. Never publish the same item twice, whatever fails. A person makes every judgement call, and every field records where its value came from.',
            },
        },
        {
            heading: 'The approach',
            body: [
                'Laravel owns the data, the files and every call that changes something on eBay. A stateless Python worker does the heavy, restartable work: fetching pages, searching Vinted and running vision models. It takes a request and returns JSON or image bytes, so it can be restarted, rate-limited or moved to another server without touching a single listing.',
                'Three rules came from the development plan and held throughout:',
            ],
            bullets: [
                'Machines do the provable work. Rules fill what can be checked; anything that needs judgement is shown to a person as missing, never filled with a guess.',
                'Every field remembers its source: Vinted, Rule, AI or Manual. A value a person typed is never overwritten automatically.',
                'Every write to eBay is idempotent. Retries, double clicks and crashed jobs can’t create a second listing.',
            ],
            figure: {
                type: 'pipeline',
                steps: [
                    { label: 'Intake', sub: 'link or CSV', kind: 'trigger' },
                    { label: 'Scrape', sub: 'Python', kind: 'rule' },
                    { label: 'Photos', sub: 'cut-out', kind: 'ai' },
                    { label: 'Map', sub: 'eBay rules', kind: 'rule' },
                    { label: 'Review', sub: 'fills gaps', kind: 'human' },
                    { label: 'Publish', sub: 'Trading API', kind: 'out' },
                ],
            },
        },
        {
            heading: 'From link to listing',
            steps: [
                { title: 'Intake', text: 'Paste URLs, upload a CSV or import a good buy. A live preview marks each line new, already here, repeated or invalid, and a unique index stops duplicates even when two pastes arrive at once.' },
                { title: 'Scrape', text: 'A session pool and circuit breaker in the worker. Each field falls back from the page payload to JSON-LD to the HTML.' },
                { title: 'Photos', text: 'Background removed, cropped to the item, squared to 1600px on white and given a confidence score.' },
                { title: 'Map', text: 'Category from eBay’s Taxonomy API; condition, size and colour mapped to eBay’s lists; title (within 80 characters) and price set by rule.' },
                { title: 'Review', text: 'One screen per listing, with the Vinted value next to every field it was mapped from. An item becomes Ready once nothing eBay requires is missing.' },
                { title: 'Publish', text: 'One listing per item, even when a call is retried. A kill switch in Settings stops every write to eBay.' },
            ],
            callout: {
                title: 'Then it keeps watching',
                text: 'On a schedule, the stock monitor re-reads each listed item on Vinted and revises the eBay price or quantity, ends the listing when it sells and relists it when stock comes back. A surge in changes pauses the monitor for an admin instead of ending dozens of listings on one bad reading.',
            },
        },
        {
            heading: 'Only values eBay will accept',
            body: [
                'Item specifics decide whether a listing gets found, so the review screen shows one row per specific for the chosen category, taken from eBay and checked against eBay’s allowed values. Missing required rows are highlighted and block publishing, and each value carries a badge for where it came from.',
                'Sizes are matched to eBay’s own list, so “S / UK 8-10” becomes S, “UK12” becomes 12 and “XXL” becomes 2XL; anything unclear is left for a person. Before that change, 15 of 22 live sizes were being rejected.',
            ],
            figure: {
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
            callout: {
                title: 'Choosing the older API on purpose',
                text: 'Sellers can’t edit listings made through eBay’s newer Inventory API in Seller Hub, so publishing uses the Trading API and the seller keeps full control. That meant handling failures that come back as HTTP 200, and titles whose ampersands broke the XML.',
            },
        },
        {
            heading: 'Bedroom snapshots in, catalogue photos out',
            body: [
                'Every photo is cut out, cropped, squared and placed on white, then scored. The score is what makes it safe: a cut-out the model is unsure about (under 80% confidence) is flagged for a person instead of published.',
                'I chose the model by measuring it on real listing photos. The first model damaged 8 of 25 whole-item photos (it kept a door handle and erased laces); BiRefNet damaged none. It needs more memory than the 2-vCPU server has, so it runs as a hosted API.',
            ],
            figure: {
                type: 'bars',
                caption: 'Photo check · local run on 14 Vinted listings, 98 photos (Sep 2026, earlier model)',
                rows: [
                    { label: 'Passed', value: 72, tone: 'ok' },
                    { label: 'Flagged for a person', value: 9, tone: 'warn' },
                    { label: 'Refused (under eBay’s 500px)', value: 17, tone: 'muted' },
                ],
            },
            bullets: [
                'When the model misses, the operator clicks the garment and SAM 2 draws its outline. Encoding a photo takes 0.6 s once; each click after that about 30 ms.',
                'Measuring the cut-out rather than how the photo was framed cut false flags from 17 to 8 in a 40-photo sample.',
                'If the vision provider can’t be reached, the photo fails with the provider’s reason and keeps its original. It is never cut blind.',
            ],
        },
        {
            heading: 'AI that suggests, never decides',
            body: [
                'Listing enrichment is built but gated: admins only, with a monthly spending cap. The model may only name specifics that are still empty, its output is held to a JSON schema, and the server throws away any value that isn’t on eBay’s allowed list. A person then accepts or rejects each row.',
            ],
            figure: {
                type: 'terminal',
                title: 'EnrichmentService.php · excerpt as shipped',
                lines: [
                    { t: '', tone: 'muted', text: '// The model may only name specifics that are actually empty.' },
                    { t: '', tone: 'ink', text: "'aspect' => ['type' => 'string', 'enum' => array_column($unfilled, 'name')]," },
                    { t: '', tone: 'muted', text: '// One a person has filled since belongs to them.' },
                    { t: '', tone: 'ink', text: 'if ($aspect->isFilled() && $aspect->source === AspectSource::Manual) {' },
                    { t: '', tone: 'warn', text: '    $rejected[] = $name; continue;' },
                    { t: '', tone: 'ink', text: '}' },
                    { t: '', tone: 'muted', text: '// Never write a value outside eBay’s allowed list, whatever came back.' },
                    { t: '', tone: 'ink', text: "if ($aspect->mode === 'selection'" },
                    { t: '', tone: 'ink', text: "    && ! in_array($suggestion['value'], $aspect->allowed_values, true)) {" },
                    { t: '', tone: 'warn', text: '    $rejected[] = $name; continue;' },
                    { t: '', tone: 'ink', text: '}' },
                ],
            },
        },
        {
            heading: 'Problems found in production',
            body: ['Several of the hardest problems turned up after launch. Each one ended with a code change and a test that keeps it fixed.'],
            bullets: [
                'Blocked by IP, not headers. Bot protection refused the server because of its IP’s reputation, so fetching became pluggable across three scraping providers, chosen in Settings. Photos skip the paid proxy.',
                'The seller’s avatar kept getting listed, because it comes from the same image server at the same size as product photos. Photos are now chosen by where they sit on the page.',
                'Duplicates made impossible: atomic state changes, a SHA-256 hash so unchanged items aren’t resent, a stable UUID per listing call, and eBay’s “already listed” error adopted as the existing listing.',
                'One pair of trainers took the items screen down: eBay’s 16,052-value brand list was stored on one 311 KB row, past MySQL’s sort buffer. Fixed the same day.',
                'Vinted moved its search API overnight. I found the new host in Vinted’s own JavaScript and searches worked again the same day.',
                'Women’s trousers were filed under Men’s, a category with no “Women” department, so the item could never be made ready. Categories are now picked inside Vinted’s own department.',
            ],
        },
        {
            heading: 'Delivery and safeguards',
            body: [
                'It started with a written plan: architecture, data model, phases, a testing strategy and a risk register. A first version was on a live server within a week, so real-world problems showed up early, and plan to production took about four weeks. It then grew in small, well-described releases: the stock monitor, the photo editor, several eBay seller accounts and the Deal Finder.',
                'There are 470 Laravel tests and 258 Python tests that run against real saved Vinted pages, including UK and French listings and sold items.',
            ],
            bullets: [
                'Refresh tokens and API keys encrypted at rest; keys are never sent back to the browser.',
                'A daily eBay API budget that queues work instead of flooding eBay.',
                'Every state change saved with its attempt number, duration and full request and response, so the operator can see why something failed without asking me.',
                'Business rules (pricing, policies, accounts, models, providers) live in Settings, not code.',
            ],
        },
    ],
    lessons: [
        'Pick models by measuring them on the client’s real data. The first background-removal model damaged 8 of 25 whole-item photos, and only real listings showed it.',
        'Treat every external system as something that will change under you. Vinted’s pages and search API both moved mid-project, and loud failures made each one a same-day fix.',
        'A confidence score is only useful if something acts on it. Flagging the uncertain cut-outs for a person is what made automatic photo editing safe to ship.',
    ],
};
