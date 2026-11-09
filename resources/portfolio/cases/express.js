export default {
    summary:
        'Express Group is a members’ investment society: members save every month, the society invests the pool and shares the profit. It ran on a spreadsheet and a printed constitution. I built one web app that keeps the books, applies the constitution’s rules on its own and prints the society’s official reports in Bengali.',
    facts: [
        { label: 'Client', value: 'Express Group, a members’ investment society' },
        { label: 'My role', value: 'Sole developer, from rules to deployment' },
        { label: 'Timeline', value: 'May – Oct 2026' },
        { label: 'Stack', value: 'Laravel, Vue, Docker on FrankenPHP' },
    ],
    sections: [
        {
            heading: 'The problem',
            body: [
                'Every month the committee collected deposits, tracked who was late, worked out fines by hand and copied it all into a yearly accounts sheet. At year end someone calculated each member’s share of the profit from the same sheet.',
                'The rules for all of this live in the society’s written constitution: when a payment counts as late, how much each late month costs, what a missed meeting costs, how much profit goes to charity and to the risk fund. A formula error or a missed row meant a member was charged the wrong amount, and nobody could easily check why.',
            ],
        },
        {
            heading: 'One place for the society’s money',
            body: ['The app covers everything the committee used to track by hand, each with its own screen and its own permissions:'],
            bullets: [
                'Members and their capital deposits, monthly and yearly.',
                'Investments and what each one returns, profit or principal.',
                'Running expenses, the society’s funds and a ledger of every movement between them.',
                'Committees, their posts and what each post is allowed to do.',
                'Meetings and attendance.',
                'A member view, so every member can see their own deposits, fines and pending share.',
            ],
        },
        {
            heading: 'The constitution, as code',
            steps: [
                { title: 'Fines (Article 9)', text: 'Late monthly and yearly payments create fines on their own; a missed meeting creates one when attendance is recorded. Running the job twice never charges twice.' },
                { title: 'Frozen amounts', text: 'Once a fine is recorded, its amount never changes, so a member pays exactly the figure they were shown.' },
                { title: 'Profit is held', text: 'Investment returns build up as the year’s pending profit. Nothing reaches a member’s capital until a manager closes the year.' },
                { title: 'Reversible year-end', text: 'Closing a year moves the 2.5% Fi Sabilillah and 5% risk-fund cuts between funds and allocates each member’s dividend. Reopening the year undoes all of it.' },
            ],
            callout: {
                title: 'Why “run it twice” matters',
                text: 'Committee members click buttons more than once, and jobs get re-run after a fix. Every fine has a unique key per member and obligation, so a second run finds it already exists instead of creating a duplicate.',
            },
        },
        {
            heading: 'Getting the money right',
            body: [
                'Profit for the year is investment profit, minus investment losses, minus running costs. Returning an investment’s principal is not profit, and money spent on an investment is capital at work, so neither touches the figure.',
                'The charity and risk-fund cuts are transfers between the society’s funds, written to the ledger, so the society’s total cash stays the same. A member’s dividend is an allocation of money already in the funds, so it is recorded against their capital without a second ledger credit, which would have counted the profit twice.',
            ],
        },
        {
            heading: 'Official reports in Bengali',
            body: [
                'The society needs paper: member statements, the yearly accounts, the half-yearly profit report, collection status, fines, expenses, the investment portfolio, the financial statement, the ledger, profit distribution and meeting minutes. That is 11 reports.',
                'Bengali text has to be shaped correctly to be readable, which most PHP PDF libraries get wrong. Each report is rendered as an HTML page and printed to PDF by headless Chromium inside the app’s container, so the PDFs look exactly like the screen.',
            ],
        },
        {
            heading: 'Shipping it',
            body: [
                'The app runs in Docker on FrankenPHP, with Chromium and the fonts it needs installed in the same image. The rules that move money are covered by automated tests, and the interface works in Bangla and English.',
            ],
        },
    ],
    lessons: [
        'Read the rule book before the spreadsheet. The constitution, not the old formulas, was the source of truth.',
        'Anything that moves money should be safe to run twice and possible to undo.',
        'For Bengali PDFs, a real browser is the most reliable renderer.',
    ],
};
