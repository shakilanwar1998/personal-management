export default {
    summary:
        'The product’s support chat runs on Intercom’s Fin AI agent, but Fin couldn’t see the product’s own video lessons. I built the search endpoint Fin now calls: it ranks lessons for German questions, puts the screen the customer is on first, and only ever returns a video that customer is allowed to open.',
    facts: [
        { label: 'Company', value: 'Dropmatix · multi-tenant SaaS business suite' },
        { label: 'My role', value: 'Backend developer, endpoint end to end' },
        { label: 'Timeline', value: 'Sep 2026 · shipped, then tuned after first tests' },
        { label: 'Stack', value: 'Laravel · MySQL · Intercom Fin data connector' },
    ],
    sections: [
        {
            heading: 'The problem',
            body: [
                'Customers ask the support chat things like “how do I pause payment reminders?”. The product already had short video lessons answering exactly that, but the AI agent knew nothing about them, so it answered from general knowledge or not at all.',
                'Connecting the two raised two risks. An AI that makes up answers or links. And an AI that hands out lessons from a plan the customer hasn’t paid for. Chat answers also end up in transcripts, email digests and exports, so whatever the bot says has to be safe to repeat.',
            ],
        },
        {
            heading: 'How a question becomes an answer',
            body: [
                'Fin calls one endpoint with the customer’s question and the screen they’re on. The endpoint works in a fixed order, and the order is the safety feature: first decide what this customer may see, then rank only those videos, then use the current screen to reorder them.',
                'Because context comes after access, opening a page of a module you don’t have shows you nothing extra. And because ranking only sees what passed the access check, no tuning of the search can ever surface a draft or another customer’s course.',
            ],
            figure: {
                type: 'pipeline',
                steps: [
                    { label: 'Fin asks', sub: 'question + screen', kind: 'trigger' },
                    { label: 'Caller check', sub: '5-min token', kind: 'rule' },
                    { label: 'Access', sub: 'may they open it?', kind: 'rule' },
                    { label: 'Rank', sub: 'BM25F + threshold', kind: 'rule' },
                    { label: 'Fin answers', sub: '≤ 3 linked videos', kind: 'ai' },
                ],
            },
        },
        {
            heading: 'Search that understands German',
            body: [
                'The ticket asked for PostgreSQL’s German full-text search, but the platform runs MySQL. Its full-text index has no German stemming, no German stopword list, and ignores words shorter than three letters, including “KI”, the German for AI.',
                'So I built the same technique in PHP: split the question into words, drop filler words in German and English, reduce each word to its stem with the CISTEM stemmer, and rank with BM25F, with a match in the title counting 2.5× a match in the summary. German compounds meet their parts at half weight, so “Rechnungsvorlage” (invoice template) still finds a video about “Rechnungen” (invoices).',
                'Ranking in PHP is a fair trade at this size: the candidates are already narrowed to one customer’s visible videos (eleven at launch, a few hundred at most). The ranker sits behind an interface, so it can be swapped for embeddings later without changing what Fin receives.',
            ],
        },
        {
            heading: 'Tuning it on the first real questions',
            body: [
                'The first tests against the live library showed two weaknesses. A video came back as soon as one word of the question appeared in it, so a broad word like “erstellen” (create) or “Mail” made an unrelated video the basis of Fin’s answer. And the current screen only multiplied a score by 1.5, which nudged the order but didn’t decide it.',
            ],
            steps: [
                { title: 'Minimum match', text: 'A video must match at least two content words of the question, or one specific word that appears in no more than 34% of the catalogue.' },
                { title: 'Relative threshold', text: 'Every result must score at least half of the best hit. The list is never padded to fill three slots: an empty answer beats a wrong one.' },
                { title: 'Context tiers', text: 'Videos from the module on screen come first. A video from elsewhere moves above them only when it scores at least 1.5× the best of them.' },
                { title: 'Scores in the audit log', text: 'Each request records every candidate’s score and what the threshold dropped, so the numbers can be tuned from real traffic instead of guesses.' },
            ],
        },
        {
            heading: 'Only links the customer can open',
            body: [
                'My first version returned a locked video’s title with an upgrade link, so Fin could pitch from it. The product owner then tightened the rule: content a customer isn’t entitled to must not appear in any field of the response. I rewrote the access check so locked videos are filtered out before ranking, a deactivated account gets nothing, and a training sold to one account leaves no trace for anyone else. The upgrade suggestion now comes from the plan description Fin already has in Intercom.',
                'The access rule is the same one the video player uses, so “can this customer open this course?” has one answer in the whole product.',
            ],
            callout: {
                title: 'A pattern for the next resources',
                text: 'I also set three sensitivity levels for whatever Fin connects to next. Public content can be quoted. Account data can be named but not attached. Money is never stated in the chat: Fin gives the status and a link into the logged-in portal.',
            },
        },
        {
            heading: 'Locking the door',
            body: [
                'Intercom’s data connectors can’t sign each request; they send fixed headers and can fetch a token. So the endpoint checks three things before it reads a word of the question: the call comes from Intercom’s own IP addresses, it carries the shared secret, and its token is signed and less than five minutes old. Each customer is limited to 60 searches a minute.',
                'It fails closed. With no secret configured, or one shorter than 32 characters, every request is refused. Refusals are logged with the reason and IP only, never the question, and the secret can be rotated without downtime.',
            ],
        },
    ],
    lessons: [
        'Grounding an AI agent is mostly plumbing: decide who may see what before any ranking happens, and the AI can’t leak what it never received.',
        'A relevance threshold matters as much as the ranking. Letting the bot say “there’s no video on that yet” is better than handing it a weak match.',
        'Record the scores. Thresholds agreed in a meeting are a starting point; the audit log is what lets you tune them.',
    ],
};
