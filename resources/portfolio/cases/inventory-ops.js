export default {
    summary:
        'A multi-marketplace reseller runs its warehouse and office from an internal ops platform. I co-built it with another developer. My parts were the eBay stock and order sync, a parser for messy vendor price sheets, role-based permissions for six roles, and low-stock email alerts.',
    facts: [
        { label: 'Client', value: 'Multi-marketplace reseller' },
        { label: 'My role', value: 'Co-developer (the parts below)' },
        { label: 'Timeline', value: 'Apr 2025 – Mar 2026' },
        { label: 'Stack', value: 'Node.js · React · MySQL · eBay Inventory & Fulfillment APIs' },
    ],
    sections: [
        {
            heading: 'Where I came in',
            body: [
                'The platform covers products, inbound stock, orders and packing for a small team selling on several marketplaces. The other developer started it and built most of it. I took on specific pieces, mostly around eBay, vendor price lists and who can see what.',
            ],
        },
        {
            heading: 'eBay stock and orders, kept in step',
            body: [
                'When a quantity changes in the platform, the change is queued, not sent straight away. A worker sends the queue to eBay in batches of up to 100 using eBay’s bulk price-and-quantity update. If eBay refuses a batch, the error is saved and the items are tried again, up to three times, so one bad SKU doesn’t block the rest and nothing fails silently.',
                'Orders come the other way. New eBay orders are imported on a one-minute cycle and lower stock in the platform. If an order is later cancelled, the quantity is put back. Orders are matched by order ID and SKU, so the same order is never counted twice.',
            ],
            bullets: [
                'Some products are listed on eBay under a different SKU. An “alternative SKU” field maps the two, in both directions.',
                'Only orders from the last 24 hours change stock, so re-importing older orders can never knock quantities off.',
                'Every stock change is kept in a history table with its status, try count and eBay’s response, so there’s always a trail behind a number.',
            ],
        },
        {
            heading: 'Reading messy vendor price sheets',
            body: [
                'Vendors send price lists as Excel or CSV files, each in its own layout. The real table doesn’t always start on the first row, and the barcode column might be called UPC, EAN, Code or Item Number.',
                'My parser scans the top rows of each sheet and scores them against the names product columns usually have, to find the real header row. Then it looks at the values themselves: the column where most cells look like barcodes is the UPC, and the one where most look like prices is the price. The team sees the suggested mapping and can correct it before the list is saved and becomes searchable.',
            ],
            figure: {
                type: 'fields',
                caption: 'One vendor sheet, mapped',
                illustrative: true,
                rows: [
                    { field: 'Header row', value: 'Row 6 (rows 1–5 are the vendor’s letterhead)', source: 'Rule' },
                    { field: 'UPC', value: '“Item Code”: most values look like barcodes', source: 'Rule' },
                    { field: 'Price', value: '“Cost”: most values are numbers', source: 'Rule' },
                    { field: 'Product name', value: '“Description”', source: 'Manual' },
                ],
            },
        },
        {
            heading: 'Six roles, one permission matrix',
            body: [
                'Warehouse and office staff share the platform, but a packer doesn’t need vendor prices and a listing assistant doesn’t need user management. I replaced scattered checks with a single permission file: for each of six roles (admin, listing, packing, two warehouse levels and accounts), which areas they can view, create, edit or delete, and which menu items they see.',
                'The same file drives both sides. The API refuses any request the role isn’t allowed, the screens hide what the role can’t use, and each role lands on its own home page after signing in. Changing what a role can do is one edit in one place.',
            ],
        },
        {
            heading: 'Low-stock email alerts',
            body: [
                'Each product can have stock tracking switched on with a minimum quantity. When stock falls below it, the team gets one email with the product’s details. The alert isn’t repeated on every sale; it resets only once stock is back above the minimum, or tracking is switched off and on again. A low-stock page lists everything currently under its minimum.',
            ],
        },
    ],
    lessons: [
        'Queue changes to a marketplace and keep the answer. A stock number you can trace is worth more than a fast one.',
        'Vendor files will never share a format. Scoring the data itself beats guessing from column names alone.',
        'Put permissions in one place that both the API and the screens read, or they drift apart.',
    ],
};
