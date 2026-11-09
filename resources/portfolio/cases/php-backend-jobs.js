export default {
    summary:
        'Six Upwork jobs where a backend had to work by a deadline: PHP and MySQL, a SQL Server query, a Python rewrite and a web analytics script. One urgent fix in a live backend turned into a 13-month contract. Every rated job came back at 4.9 or 5.0.',
    facts: [
        { label: 'Client', value: 'Upwork clients' },
        { label: 'My role', value: 'Freelance backend developer' },
        { label: 'Timeline', value: 'Apr 2023 – Mar 2025' },
        { label: 'Rating', value: '4.9 – 5.0 on Upwork' },
    ],
    sections: [
        {
            heading: 'The jobs',
            steps: [
                { title: 'National Weather Service query', text: 'Apr 2023 · PHP, MySQL. A PHP/MySQL query against the US National Weather Service API. Rated 5.0.' },
                { title: 'Rehire for a PHP project', text: 'Apr 2023 · PHP. A client came back to hire me again for their PHP project. Rated 5.0.' },
                { title: 'Fuzzy-match join', text: '18 – 21 Aug 2023 · SQL Server. A query that joins two tables whose values don’t match exactly, using a fuzzy lookup. Rated 4.9.' },
                { title: 'PHP script, rewritten in Python', text: 'Aug – Oct 2023 · PHP, Python. An existing PHP script rebuilt in Python.' },
                { title: 'Web events to AppMetrica', text: 'Apr – Aug 2023 · Web analytics. The client had a script sending web events to AppsFlyer; I made a version that sends them to AppMetrica.' },
                { title: 'Urgent fix in a live backend', text: '28 Jan 2024 – 18 Mar 2025 · PHP, MySQL. A bug in a backend already in production. After the fix, the client kept me on for 13 months. Rated 5.0.' },
            ],
        },
        {
            heading: 'What they had in common',
            body: [
                'Most of these meant working inside code someone else had written, against a deadline, with a result that had to be right: the right rows from the join, the same events arriving in a different analytics tool, the same behaviour from a script in a new language.',            ],
        },
    ],
};
