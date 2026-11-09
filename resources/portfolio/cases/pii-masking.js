export default {
    summary:
        'The CRM’s contact importer asks Claude which spreadsheet column belongs in which field, and it was sending up to five real values per column to do it: names, emails, phone numbers. I measured how well it would work without them, then changed it so only the shape of each value leaves the server, and taught the prompt to leave a column empty rather than guess.',
    facts: [
        { label: 'Company', value: 'Dropmatix · multi-tenant SaaS business suite' },
        { label: 'My role', value: 'Developer: test, build and follow-up' },
        { label: 'Timeline', value: 'Sep – Oct 2026' },
        { label: 'Stack', value: 'Laravel · Vue · Anthropic Claude' },
    ],
    sections: [
        {
            heading: 'The problem',
            body: [
                'While reviewing every outside service the app sends data to, one row stood out. When a customer imported a contact file, the AI column-matching sent each column’s header plus up to five sample values from the first rows. Those values were their own customers’ personal data, going to an AI provider in the US.',
                'The feature was useful: it saves people from matching thirty columns by hand. The question was whether it needed the real values at all.',
            ],
        },
        {
            heading: 'Measure first',
            body: [
                'Before changing anything, I tested it with made-up data only: four import files, 53 columns with a known correct answer, covering clear German headers, meaningless headers like “Feld1” (Field 1), abbreviations and Spanish. Each file went to the production prompt and model three times, in three forms.',
            ],
            bullets: [
                'Real-looking values: 156 of 159 mappings correct (98%).',
                'Masked values: 137 of 159 correct (86%), and none wrong. Every miss was a column left empty for the user.',
                'Headers only: 117 of 159 correct (74%).',
            ],
            figure: {
                type: 'bars',
                caption: 'Masked samples · 159 mappings over 3 runs',
                rows: [
                    { label: 'mapped correctly', value: 137, tone: 'ok' },
                    { label: 'left for the user', value: 22, tone: 'muted' },
                    { label: 'mapped wrongly', value: 0, tone: 'warn' },
                ],
            },
            callout: {
                title: 'Where the loss was',
                text: 'With readable headers, masking cost nothing: the German and Spanish files scored 100% and 92%, the same as with real values. The drop came only from meaningless headers, where a masked value can’t tell a first name from a city.',
            },
        },
        {
            heading: 'The mask',
            body: [
                'Every letter becomes “a” (or “A” if upper case), in any alphabet, umlauts and ñ included. Every digit becomes “9”. Spaces and punctuation stay where they are, and the result is cut at 40 characters. The model still sees that a value is an email, a phone number, a date or a five-digit code, but not whose.',
                'The mask runs on the server before anything else touches the samples: the AI request, the cache key and every log line are built from masked values only. A value that isn’t valid text is sent as nothing, never as the raw string.',
            ],
            figure: {
                type: 'mask',
                caption: 'What the model sees',
                illustrative: true,
                rows: [
                    { from: 'Müller', to: 'Aaaaaa' },
                    { from: '80331', to: '99999' },
                    { from: 'max@firma.de', to: 'aaa@aaaaa.aa' },
                    { from: '+49 30 123456', to: '+99 99 999999' },
                ],
            },
        },
        {
            heading: 'Teaching the model not to guess',
            body: [
                'I rewrote the prompt to say the samples are masked shapes and that the header carries the meaning. When the header doesn’t make a column clear, the model leaves it unmapped. For a meaningless header it may only map a shape that is unmistakable on its own: an email, a web address or an IBAN.',
                'I checked that prompt again before shipping, on four synthetic files with 57 columns. The old prompt with masked samples had made confident wrong guesses on meaningless headers, such as “Feld1” to last name and “Feld12” to bank code. The new prompt made none; it left those columns for the user. The only remaining mismatch was a Spanish tax header that could honestly mean either of two fields.',
                'The AI stays advisory: its suggestions carry an “AI” badge, and the user confirms or changes every mapping before the import runs. The import itself runs on the server with no AI involved.',
            ],
        },
        {
            heading: 'The gap I found afterwards',
            body: [
                'Headers still leave unmasked, which is fine for headers. But a file without a header row has its first contact read as the headers, so that one person would have been sent in clear text.',
                'I added a “this file has a header row” tick box, on by default. When it’s off, the columns are named “Column 1, 2, 3…” and the first row is masked like every other. When it’s on but the first row looks like data (it holds an email, web address, phone number, date or IBAN, or mostly numbers), the app warns instead of asking the AI, and the server refuses to send it until the user confirms.',
            ],
        },
    ],
    results: [
        { value: '0', label: 'real customer values sent to the AI' },
        { value: '86%', label: 'columns mapped with masked samples (98% with real values)' },
        { value: '0', label: 'wrong mappings in the masked test' },
    ],
    lessons: [
        'Measure before you argue. A round of synthetic tests turned a privacy debate into a clear trade-off the product owner could sign off.',
        'An AI that leaves a field empty is more useful than one that fills it wrongly, as long as a person reviews the result anyway.',
        'Masking the values wasn’t the end: following the data showed one more path where a real person could slip through as a header.',
    ],
};
