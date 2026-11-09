export default {
    summary:
        'A national science fest needed a website where students could read about each event and sign up for it, and a way for organisers to check every sign-up. I built the site, the registration forms and the admin panel in five days. I was also one of the people who set challenges for the fest’s CTF contest.',
    facts: [
        { label: 'Organiser', value: 'Bangladesh Islami Chhatra Shibir' },
        { label: 'My role', value: 'Sole developer · CTF challenge setter' },
        { label: 'Timeline', value: '3 – 7 Nov 2025' },
        { label: 'Stack', value: 'Laravel, Inertia + Vue, Filament' },
    ],
    sections: [
        {
            heading: 'The brief',
            body: [
                'The fest is made up of separate events, and each one has its own rules, sometimes its own categories, and its own sign-up. Some events are for one student, others are for teams.',
                'Organisers needed to publish all of this quickly, see every registration in one place, and decide when each event stops taking entries. The fest also comes back every year, so the site had to be reusable rather than rebuilt each time.',
            ],
        },
        {
            heading: 'One site, every year',
            body: [
                'Each year’s fest is a record of its own: name, slogan, cover image, intro video, venue, dates, daily opening hours and a list of FAQs. Events belong to a fest.',
                'The website always shows the latest fest that has been published. Next year the organisers add a new fest, fill it in and publish it, and the site switches over. Last year’s fest and its registrations stay in the records.',
            ],
        },
        {
            heading: 'A page for every event',
            body: ['Every event gets its own page and its own web address, built from what the organisers enter:'],
            bullets: [
                'A poster, a banner and an optional YouTube intro video.',
                'A short description and a longer write-up, written in the admin panel.',
                'Categories, such as school levels, when an event has them. The student picks one when they register.',
                'The event’s own FAQs, shown as questions that open to show the answer.',
                'For team events, the smallest and largest team allowed.',
            ],
        },
        {
            heading: 'One form for solo and team entries',
            body: [
                'Every event uses the same registration form, and it changes shape to fit the event. A solo event asks about one student. A team event also asks for a team name and lets the student add or remove members, but never fewer or more than the event allows.',
                'Each registration includes an uploaded photo of the student’s ID card, and every entry starts as pending until an organiser reviews it.',
            ],
            figure: {
                type: 'fields',
                caption: 'What the form asks for',
                rows: [
                    { field: 'Student', value: 'Name, class, school, date of birth, gender', source: 'Every event' },
                    { field: 'Contact', value: 'Phone, email, division, district, address', source: 'Every event' },
                    { field: 'Guardian', value: 'Name and phone number', source: 'Every event' },
                    { field: 'ID photo', value: 'An image of the student ID, up to 5 MB', source: 'Every event' },
                    { field: 'T-shirt', value: 'Size, XS to XXL', source: 'Every event' },
                    { field: 'Category', value: 'One of the event’s categories', source: 'If set' },
                    { field: 'Team', value: 'Team name and each member', source: 'Team events' },
                ],
            },
        },
        {
            heading: 'Run from an admin panel',
            body: [
                'Organisers manage everything from an admin panel: the fest for each year, its events and their media, and the registrations. Each registration shows its team members underneath, and an organiser marks it approved or rejected.',
                'My first version opened and closed registration on set dates. On the last day I replaced that with a switch per event, so an organiser opens or closes an event with one click from the events list, after a confirmation.',
            ],
            callout: {
                title: 'Closed means closed',
                text: 'When an event is closed, its page hides the form. The server checks too, so someone who left the page open in their browser can’t send a late entry.',
            },
        },
    ],
    lessons: [
        'Anything that changes every year belongs in the admin panel, not in the code.',
        'A switch the organisers control is simpler on the day than dates set in advance.',
        'Check rules like “registration is closed” on the server, not only on the page.',
    ],
};
