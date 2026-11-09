export default {
    summary:
        'An IGCSE maths test provider wanted its multiple-choice questions online: an admin panel where question writers build the bank, and a website where students take the tests. I built the admin panel and the question bank behind it, and turned the site’s page designs into Vue components, with real maths notation on screen.',
    facts: [
        { label: 'Client', value: 'An IGCSE maths test provider' },
        { label: 'My role', value: 'Sole developer' },
        { label: 'Timeline', value: 'Jun 2024 – Mar 2025' },
        { label: 'Stack', value: 'Laravel, Inertia + Vue, Filament, MathJax' },
    ],
    sections: [
        {
            heading: 'The problem',
            body: [
                'Maths questions are awkward to put on the web. They have formulas and diagrams, and many have a second part that builds on the first. A question with a typo in a fraction, or two answers marked correct, is a broken question.',
                'The people writing the questions needed to add them one by one or in bulk, file them so they can be found again, and see each one the way a student will before it goes live.',
            ],
        },
        {
            heading: 'The question bank',
            body: ['Writers manage the bank from an admin panel. Each question has:'],
            bullets: [
                'The question in rich text, with maths written as notation that MathJax draws properly on the student’s screen.',
                'An optional image, plus an optional second part with its own text and image.',
                'Four options, A to D. Ticking one as correct unticks the others, so a question can’t end up with two answers.',
                'A question number, a category and a reference code. One reference can be marked as the default, and new questions start with it already selected.',
            ],
            figure: {
                type: 'fields',
                caption: 'One question in the bank',
                illustrative: true,
                rows: [
                    { field: 'Reference', value: 'The default reference, pre-selected', source: 'Rule' },
                    { field: 'Number', value: '12', source: 'Manual' },
                    { field: 'Question', value: 'x is a whole number and 3x + 5 = 20', source: 'Manual' },
                    { field: 'Second part', value: 'Find the value of 2x − 1', source: 'Manual' },
                    { field: 'Options', value: 'A 5 · B 9 · C 10 · D 15', source: 'Manual' },
                    { field: 'Answer', value: 'B, the only box ticked', source: 'Rule' },
                ],
            },
        },
        {
            heading: 'Getting questions in, and filed',
            body: [
                'Typing every question in by hand is slow, so the bank also imports questions from a CSV spreadsheet. Every row is checked as it comes in: the question has to be there and fit the length limit, and all four options, the answer and the category are required.',
                'Next to every question in the list is a View button that opens it exactly as a student will see it, formulas and images included, so writers can check it before it is used.',
                'Tests are set up in the same panel, each with a title, a banner, a category, a time limit, total marks and a number of questions. In the last phase I started a wider structure for filing: countries, each with its own syllabus, plus levels, topics and forms, so a question can be found by country, level, syllabus, topic, exam and reference.',
            ],
        },
        {
            heading: 'The student side',
            body: [
                'I turned the site’s page designs into reusable Vue components. The home page is built from a ranking of the top students, with their school and points, a list of tests, a results counter and page links.',
                'The demo page is where the two sides meet. It loads any question from the bank and shows it under the test header with the date and timer, with its images, its second part and the four options to choose from.',
            ],
        },
    ],
    lessons: [
        'For maths, how a question looks is part of whether it is right, so writers need to see it as students will.',
        'Let the form prevent mistakes. One tick per question is easier than finding double answers later.',
        'A bulk import is only useful if it checks every row on the way in.',
    ],
};
