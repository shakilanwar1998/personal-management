// Case studies, one file per project: cases/<post-id>.js (default export).
// A project without a public link gets one, shown in a modal from the portfolio and as a page
// at /case-studies/<post-id>. The project's name, cover, stats, tags and reviews come from data.js;
// a case file only adds the story.
//
// export default {
//     summary: 'Two or three sentences: the problem, what I built, the outcome.',
//     facts: [{ label: 'Client', value: '…' }, { label: 'My role', value: '…' }, { label: 'Timeline', value: '…' }],
//     sections: [
//         {
//             heading: 'The problem',
//             body: ['Paragraph.', 'Paragraph.'],            // optional
//             bullets: ['…', '…'],                           // optional
//             steps: [{ title: '…', text: '…' }],            // optional, numbered
//             figure: { type: 'pipeline', steps: [...] },    // optional, any PostAttachment type
//             callout: { title: '…', text: '…' },            // optional highlighted note
//         },
//     ],
//     results: [{ value: '…', label: '…' }],                 // optional, defaults to the post's stats
//     lessons: ['…'],                                        // optional
// };
//
// Rules: anonymise clients (no names, domains or people), no secrets, and only numbers that come
// from the project itself. Anything illustrative is marked `illustrative: true` on its figure.

const files = import.meta.glob('./*.js', { eager: true });

export const caseStudies = Object.fromEntries(
    Object.entries(files)
        .filter(([path]) => !path.endsWith('/index.js'))
        .map(([path, mod]) => [path.split('/').pop().replace('.js', ''), mod.default]),
);

export const hasCase = (id) => !!caseStudies[id];
export const caseUrl = (id) => `/case-studies/${id}`;
