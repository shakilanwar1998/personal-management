export default {
    summary:
        'These are the projects I built while learning, before I moved into e-commerce automation: a student portal in plain PHP, a pharmacy app, a travel guide and the back end of an e-learning platform. Two of them were team projects, and each card says which parts were mine.',
    facts: [
        { label: 'Projects', value: '4, solo and team' },
        { label: 'My role', value: 'Developer' },
        { label: 'Timeline', value: '2019 – 2021' },
        { label: 'Stack', value: 'PHP, MySQL, Laravel, Ionic' },
    ],
    sections: [
        {
            heading: 'The projects',
            steps: [
                {
                    title: 'Student portal · 2019',
                    text: 'A school results and attendance system in plain PHP and MySQL, built on my own. Admins register students, teachers take attendance and upload marks, results are published class by class, students log in to see them, and at year end students move up to the next class.',
                },
                {
                    title: 'Pharmacy app · 2019',
                    text: 'A mobile app for a pharmacy’s customers, built with Ionic and Angular and packaged with Cordova. Customers photograph a prescription to have it filled, ask for refills or transfers, add family members, find branches on a map, see deals and chat with the pharmacy.',
                },
                {
                    title: 'Travel guide · 2020',
                    text: 'A Laravel site where visitors browse places by country and city, see each place on a Google map and leave reviews. A team project: I set it up and wrote the admin panel for countries, cities and places, the place details page with its map and the city pages. Teammates built the login, layouts, search and ratings.',
                },
                {
                    title: 'Shikhun back end · 2021',
                    text: 'The Laravel back end of an e-learning platform, built with a teammate. I set up the project and its database, including live lesson slots and bookings, bundles, coupons, certificates, orders, invoices and teacher earnings, and started the API with user roles and course categories. My teammate built the course module and later reorganised the code; the Nuxt front end was mostly theirs.',
                },
            ],
        },
        {
            heading: 'Solo and team work',
            body: [
                'The portal and the pharmacy app were mine from start to finish. The travel guide and Shikhun were shared, and their history shows it: merges, fixed conflicts and parts handed from one person to another. I would rather say plainly which parts I wrote than claim the whole thing.',
            ],
        },
        {
            heading: 'What these taught me',
            bullets: [
                'My 2019 code put form values straight into database queries. Learning why that is dangerous, and how a framework prevents it, was one of my first real lessons.',
                'Moving from plain PHP to Laravel showed me what structure buys: migrations, routes and models that anyone on the team can follow.',
                'On a team, agreeing who owns which part matters as much as the code itself.',
                'The pharmacy app was my first app that ran entirely on an API, and APIs are most of what I work with today.',
            ],
        },
    ],
};
