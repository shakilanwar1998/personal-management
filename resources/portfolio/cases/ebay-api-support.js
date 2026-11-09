export default {
    summary:
        'Three Upwork contracts for clients stuck on the eBay API, each rated 5.0. A PHP team hired me for eBay API help, then came back to the same posting for a second contract that ran just over a year. A seller whose sign-in to their own eBay account kept failing got their developer account set up so their app could finally connect.',
    facts: [
        { label: 'Client', value: 'eBay sellers & PHP teams · via Upwork' },
        { label: 'My role', value: 'eBay API developer' },
        { label: 'Timeline', value: 'Aug 2023 – Apr 2026' },
        { label: 'Rating', value: '5.0 on all three contracts' },
    ],
    sections: [
        {
            heading: 'The contracts',
            steps: [
                { title: 'eBay API Help (PHP)', text: '1 – 3 Aug 2023. A PHP team needed help with the eBay API. Three days, rated 5.0.' },
                { title: 'Same team, rehired', text: '15 Jan 2024 – 24 Feb 2025. The client reopened the same posting and kept me on for just over a year. Rated 5.0.' },
                { title: 'Configure eBay Developer Account for API usage', text: '30 Sep 2025 – 14 Apr 2026. A seller’s OAuth sign-in to their own eBay account kept failing. Rated 5.0.' },
            ],
        },
        {
            heading: 'When the sign-in won’t work',
            body: [
                'eBay only lets an app work on a seller’s account after the seller signs in on eBay and approves it. That hand-off depends on settings in the developer account that have to match what the app sends; when they don’t, the seller just sees the sign-in fail, with little to say why.',
                'This seller already had a developer account and an app waiting to use it. I configured the account for API use, so the sign-in went through and the app could connect to their seller account.',
            ],
            figure: {
                type: 'pipeline',
                steps: [
                    { label: 'Seller’s app', sub: 'asks for access', kind: 'trigger' },
                    { label: 'eBay sign-in', sub: 'seller approves', kind: 'human' },
                    { label: 'Developer account', sub: 'settings must match', kind: 'rule' },
                    { label: 'Token', sub: 'issued to the app', kind: 'rule' },
                    { label: 'eBay API', sub: 'connected', kind: 'out' },
                ],
            },
        },
    ],
};
