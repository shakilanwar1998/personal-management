export default {
    summary:
        'An eBay seller ran the business from Airtable and Google Sheets, but both were filled in by hand from eBay. I wrote PHP sync workers that keep listings, stock and orders flowing from eBay into both, and a resumable backfill that loaded about two years of order history, 2,278 unique orders, de-duplicated by order ID.',
    facts: [
        { label: 'Client', value: 'eBay seller' },
        { label: 'My role', value: 'Sole developer' },
        { label: 'Timeline', value: 'Oct 2025' },
        { label: 'Stack', value: 'PHP · eBay Trading & Fulfillment APIs · Airtable · Google Sheets' },
    ],
    sections: [
        {
            heading: 'The problem',
            body: [
                'Airtable held the product list and Google Sheets held the order log. Neither talked to eBay, so every new listing, every sale and every shipping address had to be copied over by hand, and stock in Airtable drifted away from what eBay showed.',
            ],
        },
        {
            heading: 'Three sync workers',
            body: [
                'I kept each job in its own small script that remembers where it stopped, so the scripts can run on a schedule and one failure never blocks the others.',
            ],
            steps: [
                { title: 'Listings → Airtable', text: 'New eBay listings are pulled with their item specifics, and every specific gets its own Airtable column. If a column doesn’t exist yet, the script creates it before inserting the record.' },
                { title: 'Orders → stock', text: 'Paid orders are read from eBay. For each item sold, Airtable’s available quantity goes down and its sold count goes up. Cancelled orders and cancelled lines are ignored.' },
                { title: 'Orders → Google Sheets', text: 'Each order becomes one row with date, order ID, buyer, items, total and shipping address, in the layout the team already used.' },
                { title: 'Remember progress', text: 'Each worker keeps a small state file: when it last ran, which listings it has pushed, which order IDs it has written. A rerun only does what is still missing.' },
            ],
        },
        {
            heading: 'Backfilling two years of orders',
            body: [
                'The Sheet also needed the history, not just new orders. eBay only returns orders from roughly the last two years, and asking for all of it at once is slow and fragile.',
                'So the backfill walks through that history in 60-day windows. After each window it saves where the next one starts, so if a run stops halfway, the next run carries on from that point. It starts two days inside eBay’s two-year limit, so a long backfill never asks for a date eBay has already dropped.',
                'Orders are checked against the saved list of IDs before they are written, so nothing is entered twice. Cancelled, refunded and partly refunded orders are skipped, so the Sheet only shows sales that actually happened.',
            ],
            figure: {
                type: 'terminal',
                title: 'php sync_ebay_orders_backfill.php',
                illustrative: true,
                lines: [
                    { t: '$', tone: 'ink', text: 'php sync_ebay_orders_backfill.php' },
                    { t: '', tone: 'muted', text: 'Processing window: 2024-03-02T00:00:00Z .. 2024-05-01T00:00:00Z' },
                    { t: '', tone: 'muted', text: '  Received 100 orders at offset 0' },
                    { t: '', tone: 'muted', text: '  Received 37 orders at offset 100' },
                    { t: '', tone: 'warn', text: '  Skipped order 12-34567-89012 (canceled or refunded)' },
                    { t: '', tone: 'ok', text: 'Appended 134 new orders.' },
                    { t: '', tone: 'ok', text: 'Advanced next_start_at to 2024-05-01T00:00:00Z' },
                ],
            },
        },
        {
            heading: 'Details that kept it reliable',
            bullets: [
                'Short pauses between API calls keep the scripts gentle on eBay’s and Airtable’s APIs.',
                'The eBay token is refreshed on every run, and Google Sheets is accessed with a service account, so nobody has to sign in for it to work.',
                'If eBay has no shipping address on an order, the row falls back to the buyer’s registered address instead of staying blank.',
                'A listing is only marked as pushed after Airtable accepts it, and progress is saved after every record. If Airtable rejects an unknown column, the script creates it and tries once more.',
            ],
        },
    ],
    lessons: [
        'For any long import, save progress after every chunk. A backfill you can stop and restart is one you can trust.',
        'Creating missing columns on the fly meant new eBay categories, with item specifics nobody had seen before, never broke the sync.',
    ],
};
