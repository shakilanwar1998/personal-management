export default {
    summary:
        'Profit on second-hand stock is decided when an item is bought, not when it’s listed. For the same reseller as VintBay, I built a Deal Finder that gives every Vinted item a Buy Score from 1 to 10, priced against live eBay listings and the business’s own costs, with the working shown and a confidence label on every score. A good buy goes into the listing pipeline in one click.',
    facts: [
        { label: 'Client', value: 'UK second-hand fashion reseller' },
        { label: 'My role', value: 'Sole developer: pricing model, scoring, AI, dashboard' },
        { label: 'Timeline', value: 'Sep – Oct 2026 · live 2 days after sign-off' },
        { label: 'Built on', value: 'The VintBay listing platform' },
    ],
    sections: [
        {
            heading: 'The problem',
            body: [
                'The reseller buys on Vinted and sells on eBay. Deciding what to buy meant checking eBay prices by hand for every Vinted search, then working out fees and postage in their head. That doesn’t scale, and the cheap-looking items are often the ones that lose money.',
                'They wanted to search Vinted, or paste a link, and see straight away whether an item was worth buying, and why. This is the flow I built for that:',
            ],
            figure: {
                type: 'pipeline',
                steps: [
                    { label: 'Vinted item', sub: 'search, link', kind: 'trigger' },
                    { label: 'eBay search', sub: 'from title', kind: 'rule' },
                    { label: 'Comparables', sub: 'odd ones out', kind: 'rule' },
                    { label: 'AI fallback', sub: 'few matches', kind: 'ai' },
                    { label: 'Buy Score', sub: '1–10 + label', kind: 'rule' },
                    { label: 'Buyer', sub: 'imports', kind: 'human' },
                ],
            },
        },
        {
            heading: 'How a score is worked out',
            steps: [
                { title: 'Ask eBay the right question', text: 'An eBay search is built from the Vinted title. Model numbers, storage and the word that says what the thing is (“box”, “case”) always survive the word limit.' },
                { title: 'Keep only true comparables', text: 'Cases, parts, faulty listings, other models and other storage sizes are set aside, each with a reason. Each eBay seller’s price counts once.' },
                { title: 'Count every penny', text: 'Vinted price, buyer fee and postage in; expected eBay sale less eBay’s fee out. Under a £5 profit floor the score drops, so a hoodie can’t be a good buy on 40p.' },
                { title: 'Weigh it the client’s way', text: 'Profit against a markup target, price reliability and competition, weighted as the client chose. A factor with no data is left out and the rest reweighted, never guessed.' },
            ],
        },
        {
            heading: 'Every score shows its working',
            body: [
                'Open any item and the score is broken down: where the money goes, what went into the score and how sure it is. The eBay Matches tab lists every listing behind the price, with the reason each one was set aside. Untick a wrong match, or count one back in, and the score updates.',
            ],
            figure: {
                type: 'score',
                illustrative: true,
                item: 'Waxed field jacket · size M',
                score: 7.8,
                parts: [
                    { label: 'Profit potential', value: 0.84 },
                    { label: 'Price reliability', value: 0.72 },
                    { label: 'Competition', value: 0.66 },
                ],
                rejected: ['Women’s gilet, not a men’s wax jacket', 'Kids’ size, not comparable'],
            },
            bullets: [
                'Bands run from Do not buy (1–2) through Risky, Maybe and Good buy to Perfect buy (9–10). An item that would lose money is capped at 2, however fast it might sell.',
                'Scores under 5 are hidden by default, and each good buy (7+) pops up on its own to import or skip.',
                'Import one deal or many; they join the normal listing pipeline, and Best Offer is switched on for every eBay listing.',
                'Weights, costs, targets and the profit floor are all editable in Settings.',
            ],
        },
        {
            heading: 'When the rules run out',
            body: [
                'The matching rules work well for things with model numbers. Clothes have none: a branded wax coat came back as “Not enough data”, and a French listing matched nothing at all.',
                'So when eBay has too few matches, an LLM writes a better search in English, the way eBay sellers title the item, then reads the matches the way a buyer would, sets aside the ones that aren’t the same kind of thing and estimates a sale price with a low–typical–high range. Every answer is structured output; anything outside the schema is dropped.',
            ],
            callout: {
                title: 'Boxed in',
                text: 'A score priced from the AI’s estimate is labelled as such and never rated above medium confidence. Its reasoning is shown, and its cost (about a fifth of a penny an item) sits next to it.',
            },
        },
        {
            heading: 'Tuned with the client, week by week',
            body: [
                'The client buys and sells every day, so their feedback came from real searches. Each report was reproduced with live data, fixed and shipped within a day or two, and became a rule with a test.',
            ],
            steps: [
                { title: 'A box priced as a phone', text: 'A phone’s box scored 6.4 with £248 profit, because “box” was cut from the eBay search. Now it stays, and phones are compared only with the same storage size.' },
                { title: '“New” isn’t new', text: 'A phone marked new on Vinted has been opened, so it’s priced as used unless sealed. Four sellers listing once per colour no longer count as 18 prices. The same listing now scores 2.0, not 7.9.' },
                { title: 'Phones aren’t jackets', text: '“Phones sell fast, 20% is enough. Clothes should still double.” Anything sold by its storage got its own markup target.' },
                { title: 'Scam protection', text: 'Sellers with no reviews are never scored. Review counts are read from Vinted in the background and cached for an hour.' },
            ],
        },
        {
            heading: 'Honest about the data',
            body: [
                'The plan was to score from eBay’s sold prices. Sold history sits behind a sign-in, and eBay’s Marketplace Insights API isn’t open to new apps, so today scores come from live asking prices (less a discount, because an asking price isn’t a sale) and carry a confidence label that says so. The sold-price path, including a “how often it sells” factor, is built and tested and switches on with one setting once eBay grants access.',
                'To show whether the scores are right, a results report sets each imported item’s predicted profit against what it actually sold for. The sale price comes from eBay’s own transaction record, because with Best Offer on, an accepted offer sells for less than the listing price. The report groups results by band, brand and category, so the client can see whether a Good buy really beats a Maybe.',
            ],
        },
    ],
    results: [
        { value: '1–10', label: 'Buy Score on every item, with its working' },
        { value: '2 days', label: 'from agreed proposal to a live score' },
        { value: '1–2 days', label: 'from client feedback to a shipped fix' },
        { value: '≈⅕p', label: 'AI cost per item it prices' },
    ],
    lessons: [
        'A score someone will act on has to show its working, down to the listings behind it, so a wrong match can be spotted and removed in a click.',
        '“Not enough data” is a valid answer. Refusing to score is better than a confident number built on two listings.',
        'Real feedback beats test cases. Every early mis-score came from a search the client actually ran, and each one became a rule and a test.',
    ],
};
