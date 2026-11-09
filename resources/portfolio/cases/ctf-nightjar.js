export default {
    summary:
        'For Session 4 of a practical cyber security course, I built a CTF lab: a copy of a breached server that participants investigate like an incident responder. One script builds it, five flags lead them from basic file searching to reading logs, and the lab runs safely on a throwaway machine without root access.',
    facts: [
        { label: 'Programme', value: 'Practical Cyber Security Intensive Program 2026' },
        { label: 'My role', value: 'Designed and built the lab' },
        { label: 'Timeline', value: 'Jun 2026 · Session 4' },
        { label: 'Stack', value: 'Bash, standard Linux tools' },
    ],
    sections: [
        {
            heading: 'The scenario',
            body: [
                'Players are told that web-01, an internal portal server, has been breached. They are handed a copy of its files and asked to work through it like a real responder: find the flags, then work out what happened.',
                'The server is made to look lived in. There are user folders, a small web app, a scheduled maintenance job and system logs, with ordinary activity mixed in around the attack. As on a real server, the clues are in the leftovers: things people saved, configured or forgot to tidy up.',
            ],
        },
        {
            heading: 'Five flags, in a learning order',
            body: [
                'Each flag teaches one habit, and they get harder in order. The player brief gives every one a single-line goal and nothing more.',
            ],
            steps: [
                { title: 'Recon', text: 'Users leave traces in hidden files. The first habit is to list everything, not just what shows up by default.' },
                { title: 'Bonus', text: 'A file isn’t always what its extension says. Players learn to look inside a file instead of trusting its name.' },
                { title: 'Loot', text: 'A secret sits in plain sight, and it unlocks something else. The lesson is how one leaked secret leads to the next.' },
                { title: 'Root', text: 'Something privileged runs on a schedule, and anyone can edit it. This is the classic misconfiguration that hands over a server.' },
                { title: 'Forensics', text: 'The logs know who did this. Players count and rank what they find to pick out the attacker’s address.' },
            ],
            callout: {
                title: 'No spoilers here',
                text: 'This write-up describes how the lab is designed, not how to solve it. The flags, the passwords and where things are hidden stay in the lab.',
            },
        },
        {
            heading: 'Safe by design',
            body: ['A lab about attacks has to be impossible to misuse on the machine it runs on. I built it around these rules:'],
            bullets: [
                'No root needed. Everything runs as a normal user.',
                'Nothing is written outside the lab’s own folder.',
                'It runs on a disposable virtual machine, and the brief says the scope is that folder only.',
                'The scheduled job never really runs as root. A small script plays the scheduler’s part from inside the lab folder, so the lesson works but can’t reach the host.',
                'IP addresses in the logs come from ranges reserved for documentation, so none of them belongs to a real person or company.',
            ],
        },
        {
            heading: 'Built, checked and reset by script',
            body: [
                'One setup script builds the whole server from scratch. If a tool it relies on is missing from the machine, it falls back to another, so the lab still builds.',
                'A verify script then walks every flag the way a player would and prints a pass or fail for each step. It tries the privileged step on a throwaway copy, so running the check never changes the lab. A reset script wipes everything and rebuilds a clean copy between groups.',
            ],
            figure: {
                type: 'terminal',
                title: 'verify_lab.sh',
                illustrative: true,
                lines: [
                    { t: '$', tone: 'ink', text: './verify_lab.sh' },
                    { t: '', tone: 'muted', text: '=== Operation Nightjar — flag verification ===' },
                    { t: '', tone: 'ok', text: '[PASS] Recon flag resolves' },
                    { t: '', tone: 'ok', text: '[PASS] Bonus flag resolves' },
                    { t: '', tone: 'ok', text: '[PASS] Loot flag resolves' },
                    { t: '', tone: 'ok', text: '[PASS] Root flag resolves' },
                    { t: '', tone: 'ok', text: '[PASS] Forensics flag resolves' },
                    { t: '', tone: 'ink', text: 'All flags resolve. Lab is good to ship.' },
                ],
            },
        },
        {
            heading: 'The write-up is the real skill',
            body: [
                'Players also get a one-page cheat sheet of the commands for each stage: looking around a file system, looking inside files, checking for privilege problems and reading logs. It teaches the tools, not the answers.',
                'Capturing the flags is only half the task. The brief ends by asking each player to put the flags together and write the story of the breach in their own words. Flags show that someone found the evidence; the write-up shows they understood it, and that is what a responder hands over at the end of a real investigation.',
            ],
        },
    ],
    lessons: [
        'A lab should be safe to run before it is fun to play.',
        'If a check script can solve the lab end to end, the lab can be trusted in front of a class.',
        'Give one clear goal per flag and let the player find the path.',
    ],
};
