export default {
    summary:
        'A multi-tenant suite of CRM, invoicing, a unified mailbox, video courses and a public API was being split off an older platform and made ready for launch. These are the parts I owned as a core developer, and why they were built the way they were.',
    facts: [
        { label: 'Company', value: 'Dropmatix · multi-tenant SaaS business suite' },
        { label: 'My role', value: 'Core developer, mostly backend' },
        { label: 'Timeline', value: 'Aug – Oct 2026' },
        { label: 'Stack', value: 'Laravel · Vue · MySQL · Horizon · GitLab CI' },
    ],
    sections: [
        {
            heading: 'The setting',
            body: [
                'The new product was being cut out of a large, older platform. That meant two kinds of work at once: building the features the launch needed, and removing or locking down what the new product shouldn’t inherit, from old payment pages to whole modules. Removed features got a guard test that fails if they ever come back.',
                'I worked with Claude Code as a pair programmer throughout. Every commit message records the problem, what I checked, the fix and the proof, so a reviewer could follow a change without asking.',
            ],
        },
        {
            heading: 'An order API any shop can push into',
            body: [
                'A customer wanted orders from their Shopify shop, via Make, to appear in the suite and get invoiced. The order endpoint existed but answered “not implemented”, and webhook subscriptions were stored but never sent.',
                'I made the endpoint hand each order to the same entry point the existing shop sync had used for years, rather than writing rows directly. That way invoice numbering, tax splits, currency conversion and payment reminders behave exactly as for any other order.',
            ],
            bullets: [
                'Safe to resend: the source and the shop’s own order id are unique per account, so a repeated call returns the existing order instead of a twin, even when two automation runs race.',
                'Totals come from the shop when it sends them. The shop already charged that amount, and an invoice that’s a cent off is worse than useless.',
                'Unknown products import with a warning instead of being rejected: an imported order records a sale that already happened.',
                'Any source works, not just Shopify. The docs show a WooCommerce order and the smallest valid request, three fields.',
                'Outbound webhooks are signed and retried on a fixed schedule kept in the database, so the retry clock is the same on every server and visible when an integrator asks why nothing arrived.',
            ],
            figure: {
                type: 'pipeline',
                steps: [
                    { label: 'Any shop', sub: 'POST /orders', kind: 'trigger' },
                    { label: 'Dedupe', sub: 'source + id', kind: 'rule' },
                    { label: 'Order path', sub: 'as shop sync', kind: 'rule' },
                    { label: 'Invoice', sub: 'tax, reminders', kind: 'out' },
                    { label: 'Webhook', sub: 'signed, retried', kind: 'out' },
                ],
            },
        },
        {
            heading: 'Payments that update the product by themselves',
            body: [
                'Two payment paths had to act without anyone clicking. A Stripe payment marks its invoice paid; I found and fixed why the Stripe webhook had silently done nothing for paid, failed and refunded payments alike. And a course creator’s sale on Digistore24 has to turn into a buyer account with access to the right course.',
                'For Digistore24 I built the payment notifications as a sequence of refusals: nothing is believed until the signature over the whole message checks out with that seller’s own passphrase, and a notification already handled is caught by a database constraint, because the provider retries, sometimes in parallel.',
                'A buyer’s membership and each course they own are separate records, so a refund on one course never touches the others. Course emails go out when access actually changes, not on every raw payment event.',
            ],
        },
        {
            heading: 'One inbox, and AI that waits for a person',
            body: [
                'WhatsApp had been a separate module, so customers left their inbox to use it. I made a connected WhatsApp number another account in the mailbox switcher: the same conversation list, with a chat thread in the reading pane.',
                'Course videos get AI-generated subtitles, titles and descriptions from the video host. They stay drafts until a person publishes them, and the AI label stays on a text until someone edits it, at which point they’re the author. I also fixed the jobs around it: subtitles in every language the user picked, each paid for once, and a scheduled pass that picks up results the host finished late.',
            ],
        },
        {
            heading: 'Knowing when something breaks',
            body: [
                'One background error had repeated every three seconds for weeks, writing about 170 MB of log a day, and nobody knew. I added a watcher that runs every minute and posts to a Slack #alerts channel when the same error appears 50 times in five minutes, or the logs grow more than 5 MB in an hour. Messages carry the error type, count and location, never customer data.',
                'Later the same channel learned about silent stops: the scheduler and every queue worker write a heartbeat, and a server check reports a stale one, then a single “back to normal” line when it recovers. Each problem is repeated at most once an hour.',
            ],
            figure: {
                type: 'terminal',
                title: '#alerts',
                illustrative: true,
                lines: [
                    { t: '09:14', tone: 'warn', text: 'Error burst · same exception 50× in 5 min on queue "default"' },
                    { t: '09:14', tone: 'muted', text: 'type, count and location only · no payload' },
                    { t: '09:20', tone: 'warn', text: 'Scheduler heartbeat older than 5 min' },
                    { t: '09:31', tone: 'ok', text: 'Back to normal · heartbeat fresh, queue drained' },
                ],
            },
        },
        {
            heading: 'Security checks in every merge request',
            body: [
                'A secret scan over the full history found far more hard-coded credentials than the earlier inventory had listed. That became a rotation plan, and a gate so it can’t happen again.',
                'Gitleaks scans every merge request’s commits for secrets. Opengrep checks the changed files for risky PHP patterns and fails only on what the change introduces, while a full scan against a recorded baseline blocks a release. The tools are pinned by version and checksum and run on the build server without sending code anywhere. I proved the gate by pushing a branch with a planted key and two unsafe patterns: all three jobs failed on exactly those lines.',
            ],
        },
    ],
    lessons: [
        'When you add a new door into an old system, send it through the old hallway. Reusing the existing order path meant invoices, reminders and reports were right on day one.',
        'Assume every outside service retries, sometimes twice at once. A unique index is a better guard than a check-then-insert.',
        'Alerts are only useful if they’re rare and safe to read: rate-limited, and never carrying customer data.',
    ],
};
