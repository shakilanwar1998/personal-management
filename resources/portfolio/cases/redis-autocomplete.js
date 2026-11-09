export default {
    summary:
        'A PHP web app needed search-as-you-type. I set up a Redis full-text (FT) index from PHP, wrote the prefix query behind it and wired it to a JavaScript autocomplete box, start to finish in two days.',
    facts: [
        { label: 'Client', value: 'PHP web app owner · via Upwork' },
        { label: 'My role', value: 'Sole developer' },
        { label: 'Timeline', value: '3 – 5 Oct 2023' },
        { label: 'Rating', value: '5.0 on Upwork' },
    ],
    sections: [
        {
            heading: 'The ask',
            body: [
                'The brief was short: a few lines of code to create a Redis FT search index and query it from a JavaScript autocomplete. The app already had Redis; it just wasn’t being used for search.',
            ],
        },
        {
            heading: 'What I built',
            steps: [
                { title: 'Index', text: 'An FT index created from PHP over the records the search box needs, with the searchable fields marked as text.' },
                { title: 'Query', text: 'A small PHP endpoint that turns what the user has typed into a prefix query and returns the top matches.' },
                { title: 'Autocomplete', text: 'A JavaScript box that calls the endpoint as the user types and lists the suggestions under it.' },
                { title: 'Hand-over', text: 'Kept to the few lines the client asked for, so their team can read and extend it.' },
            ],
            figure: {
                type: 'terminal',
                title: 'redis-cli',
                illustrative: true,
                lines: [
                    { t: '>', tone: 'ink', text: 'FT.SEARCH idx:products "@name:lapt*" LIMIT 0 5' },
                    { t: '', tone: 'muted', text: '1) (integer) 5' },
                    { t: '', tone: 'ok', text: '2) "Laptop stand, aluminium"' },
                    { t: '', tone: 'ok', text: '3) "Laptop sleeve 14\\", grey"' },
                ],
            },
        },
    ],
    results: [
        { value: '2 days', label: 'from brief to working search' },
        { value: '5.0', label: 'client rating' },
    ],
};
