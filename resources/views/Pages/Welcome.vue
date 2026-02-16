<script setup>
import Nav from "../Shared/Nav.vue";
</script>

<template>
    <div class="min-h-screen bg-slate-950 text-slate-100">

        <main class="mx-auto max-w-6xl px-4 pb-16 pt-10 sm:px-6 lg:px-8 lg:pt-16">
            <!-- Hero -->
            <section class="grid items-center gap-10 border-b border-slate-800 pb-12 lg:grid-cols-[minmax(0,3fr)_minmax(0,2fr)] lg:gap-14">
                <div class="flex flex-col items-center text-center lg:items-start lg:text-left">

                    <h1 class="mt-5 text-balance text-4xl font-semibold tracking-tight sm:text-5xl lg:text-6xl">
                        Shakil Anwar
                    </h1>
                    <p class="mt-2 text-base font-medium text-emerald-300 sm:text-lg">
                        Software Engineer
                    </p>

                    <p class="mt-5 max-w-2xl text-sm leading-relaxed text-slate-300 sm:text-base">
                        Building scalable, performant backend systems &amp; automation for marketplaces,
                        suppliers, and high‑traffic applications.
                    </p>

                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-300 sm:text-base">
                        Software Engineer with 6+ years of experience in PHP (Laravel), Node.js, and modern JavaScript frameworks.
                        Specialized in API integrations, e‑commerce automation, AI‑powered workflows, and performance optimization.
                        Experienced in building scalable microservices and managing cloud infrastructure (AWS, DigitalOcean).
                    </p>

                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <a
                            href="#contact"
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-400 px-4 py-2 text-sm font-semibold text-slate-950 shadow-lg shadow-emerald-500/30 transition hover:bg-emerald-300"
                        >
                            Get in touch
                            <span aria-hidden="true">↗</span>
                        </a>

                        <a
                            href="#projects"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-700 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:border-emerald-400/70 hover:text-emerald-300"
                        >
                            View projects
                        </a>
                    </div>

                    <dl class="mt-8 grid gap-4 text-xs text-slate-300 sm:grid-cols-2 sm:text-sm">
                        <div class="space-y-1">
                            <dt class="flex items-center gap-1.5 text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Location
                            </dt>
                            <dd>Dinajpur, Bangladesh</dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="flex items-center gap-1.5 text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Email
                            </dt>
                            <dd>
                                <a href="mailto:shakilanwar1998@gmail.com" class="hover:text-emerald-300">
                                    shakilanwar1998@gmail.com
                                </a>
                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="flex items-center gap-1.5 text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Phone
                            </dt>
                            <dd>
                                <a href="tel:+8801738362296" class="hover:text-emerald-300">
                                    +880 1738 362 296
                                </a>
                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-slate-400">Profiles</dt>
                            <dd class="flex flex-wrap gap-2">
                                <a href="https://github.com/shakilanwar1998" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-full border border-slate-700 px-2.5 py-0.5 text-xs transition hover:border-emerald-400/70 hover:text-emerald-300">
                                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                    </svg>
                                    GitHub
                                </a>
                                <a href="https://linkedin.com/in/shakilanwar1998" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-full border border-slate-700 px-2.5 py-0.5 text-xs transition hover:border-emerald-400/70 hover:text-emerald-300">
                                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                    LinkedIn
                                </a>
                                <a href="https://facebook.com/shakilanwar1998" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-full border border-slate-700 px-2.5 py-0.5 text-xs transition hover:border-emerald-400/70 hover:text-emerald-300">
                                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    Facebook
                                </a>
                            </dd>
                        </div>
                    </dl>
                </div>

                <aside class="space-y-4 rounded-2xl border border-slate-800 bg-slate-900/60 p-5 shadow-[0_0_80px_rgba(16,185,129,0.15)] sm:p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-400/80">
                        Core Focus
                    </p>
                    <ul class="mt-3 space-y-3 text-sm text-slate-200">
                        <li class="flex gap-2">
                            <span class="mt-1 h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            API integrations for marketplaces (eBay, Amazon SP‑API, Shopify) and supplier systems.
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-1 h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            E‑commerce automation, inventory synchronization, and background job optimization.
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-1 h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            AI‑powered workflows using OpenAI and Google AI for content and data enrichment.
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-1 h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            High‑performance MySQL, Redis caching, and scalable microservices on AWS & DigitalOcean.
                        </li>
                    </ul>
                </aside>
            </section>

            <!-- Experience -->
            <section id="experience" class="mt-12 border-b border-slate-800 pb-12">
                <div class="flex items-baseline justify-between gap-4">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-50 sm:text-xl">
                        Experience
                    </h2>
                    <span class="text-xs text-slate-400">2019 — Present</span>
                </div>

                <div class="mt-6 grid gap-6 lg:grid-cols-2">
                    <!-- Dropmatix -->
                    <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-5 sm:p-6">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-50 sm:text-base">
                                    Sr. Software Engineer · Laravel, Node.js, Vue
                                </h3>
                                <p class="text-xs text-slate-400">
                                    Dropmatix Systema SL · Balearic Islands, Spain
                                </p>
                            </div>
                            <p class="text-xs font-medium text-emerald-300">
                                Jan 2022 – Present
                            </p>
                        </div>

                        <ul class="mt-4 space-y-2.5 text-sm text-slate-200">
                            <li class="flex gap-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-400"></span>
                                Automated product synchronization across eBay, Amazon SP‑API, and Shopify, reducing manual work by 60%.
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-400"></span>
                                Integrated multiple supplier APIs with Dropmatix inventory for real‑time product and stock updates.
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-400"></span>
                                Optimized million‑row database tables and queries, reducing response times by up to 80%.
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-400"></span>
                                Tuned background jobs to lower RAM and CPU usage while maintaining high throughput.
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-400"></span>
                                Designed and maintained microservices architecture and multi‑environment servers to improve scalability and uptime.
                            </li>
                        </ul>
                    </article>

                    <!-- Luminous Labs -->
                    <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-5 sm:p-6">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-50 sm:text-base">
                                    Software Engineer · Laravel
                                </h3>
                                <p class="text-xs text-slate-400">
                                    Luminous Labs · Dhaka, Bangladesh
                                </p>
                            </div>
                            <p class="text-xs font-medium text-slate-300">
                                Apr 2019 – Dec 2021
                            </p>
                        </div>

                        <ul class="mt-4 space-y-2.5 text-sm text-slate-200">
                            <li class="flex gap-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-400"></span>
                                Developed scalable web applications with Laravel, Angular, and Node.js with a focus on performance and maintainability.
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-400"></span>
                                Implemented automation workflows and microservices architectures to handle complex business logic efficiently.
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-400"></span>
                                Optimized DigitalOcean server infrastructure with Laravel Forge, improving deployment speed and stability.
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-400"></span>
                                Recognized as Employee of the Year 2021 and Best Employee of the Month (May 2021).
                            </li>
                        </ul>
                    </article>
                </div>
            </section>

            <!-- Skills & Education -->
            <section id="skills" class="mt-12 border-b border-slate-800 pb-12">
                <div class="grid gap-8 lg:grid-cols-[minmax(0,3fr)_minmax(0,2fr)]">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-slate-50 sm:text-xl">
                            Technical Skills
                        </h2>
                        <div class="mt-5 grid gap-5 text-sm text-slate-200 sm:grid-cols-2">
                            <div>
                                <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                                    Languages
                                </h3>
                                <p class="mt-2 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">PHP</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">JavaScript</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Node.js</span>
                                </p>
                            </div>
                            <div>
                                <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                                    Frameworks & Libraries
                                </h3>
                                <p class="mt-2 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Laravel</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">FilamentPHP</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">ExpressJS</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">NestJS</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Vue</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Angular</span>
                                </p>
                            </div>
                            <div>
                                <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                                    Databases & Caching
                                </h3>
                                <p class="mt-2 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">MySQL</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">MongoDB</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Redis</span>
                                </p>
                            </div>
                            <div>
                                <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                                    Cloud & DevOps
                                </h3>
                                <p class="mt-2 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">AWS</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Docker</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Git</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">DigitalOcean</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Laravel Forge</span>
                                </p>
                            </div>
                            <div class="sm:col-span-2">
                                <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                                    AI & Automation
                                </h3>
                                <p class="mt-2 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">OpenAI API</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">ChatGPT Integration</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Google AI APIs</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Cursor AI</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">AI Workflow Automation</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <aside id="about" class="rounded-2xl border border-slate-800 bg-slate-900/60 p-5 sm:p-6">
                        <h2 class="text-lg font-semibold tracking-tight text-slate-50 sm:text-xl">
                            Education
                        </h2>
                        <p class="mt-3 text-sm font-medium text-slate-100">
                            B.Sc in Software Engineering
                        </p>
                        <p class="mt-1 text-sm text-slate-300">
                            Daffodil International University
                        </p>
                        <p class="mt-1 text-xs text-slate-400">
                            Dhaka, Bangladesh · Apr 2017 – May 2021
                        </p>
                        <p class="mt-3 inline-flex items-baseline gap-1 rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-200">
                            <span class="font-semibold text-emerald-300">CGPA:</span> 3.56 / 4.0
                        </p>
                    </aside>
                </div>
            </section>

            <!-- Projects -->
            <section id="projects" class="mt-12 border-b border-slate-800 pb-12">
                <h2 class="text-lg font-semibold tracking-tight text-slate-50 sm:text-xl">
                    Selected Projects
                </h2>

                <div class="mt-6 grid gap-6 lg:grid-cols-2">
                    <!-- Dropmatix -->
                    <article class="group rounded-2xl border border-slate-800 bg-slate-900/60 p-5 transition hover:border-emerald-400/70 hover:shadow-[0_0_60px_rgba(16,185,129,0.18)] sm:p-6">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-50 sm:text-base">
                                    Dropmatix – Core Developer
                                </h3>
                                <p class="mt-1 text-xs text-slate-400">
                                    Marketplace & supplier integrations · E‑commerce automation
                                </p>
                            </div>
                        </div>

                        <p class="mt-3 text-sm text-slate-200">
                            Developed core modules for supplier product imports/exports to eBay and Amazon,
                            ensuring data accuracy and operational efficiency across large catalogs.
                        </p>

                        <ul class="mt-3 space-y-1.5 text-xs text-slate-300">
                            <li>• Optimized performance using Redis caching, background job tuning, and database optimization.</li>
                            <li>• Reduced query times, CPU load, and RAM usage to keep the system stable under high load.</li>
                        </ul>

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Laravel</span>
                            <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Node.js</span>
                            <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Vue</span>
                            <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">MySQL</span>
                            <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Redis</span>
                        </div>

                        <p class="mt-3 text-xs text-slate-400">
                            Website: <span class="text-emerald-300">dropmatix.com</span>
                        </p>
                    </article>

                    <!-- AI Integrations -->
                    <article class="group rounded-2xl border border-slate-800 bg-slate-900/60 p-5 transition hover:border-emerald-400/70 hover:shadow-[0_0_60px_rgba(16,185,129,0.18)] sm:p-6">
                        <h3 class="text-sm font-semibold text-slate-50 sm:text-base">
                            AI‑Powered API Integrations
                        </h3>
                        <p class="mt-1 text-xs text-slate-400">
                            Freelance & personal projects
                        </p>

                        <p class="mt-3 text-sm text-slate-200">
                            Integrated OpenAI GPT APIs into Laravel/Node.js apps for automated content generation,
                            customer support, and product data enrichment pipelines.
                        </p>

                        <ul class="mt-3 space-y-1.5 text-xs text-slate-300">
                            <li>• Built AI‑assisted product data enrichment workflows to improve marketplace catalog accuracy.</li>
                            <li>• Experimented with Cursor AI for AI‑assisted coding and workflow automation.</li>
                        </ul>

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">OpenAI API</span>
                            <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Laravel</span>
                            <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Node.js</span>
                            <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Vue</span>
                            <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-100">Automation</span>
                        </div>
                    </article>
                </div>
            </section>

            <!-- Achievements & Contact -->
            <section id="achievements" class="mt-12 grid gap-10 lg:grid-cols-[minmax(0,3fr)_minmax(0,2fr)]">
                <div>
                    <h2 class="text-lg font-semibold tracking-tight text-slate-50 sm:text-xl">
                        Achievements
                    </h2>
                    <ul class="mt-5 space-y-3 text-sm text-slate-200">
                        <li class="flex gap-3">
                            <span class="mt-1 h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            <div>
                                <p class="font-medium text-slate-50">
                                    Employee of the Year 2021
                                </p>
                                <p class="text-xs text-slate-400">
                                    Luminous Labs · Recognized for exceptional performance and impact.
                                </p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            <div>
                                <p class="font-medium text-slate-50">
                                    Best Employee of the Month (May 2021)
                                </p>
                                <p class="text-xs text-slate-400">
                                    Luminous Labs · Awarded for consistently delivering high‑quality solutions.
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>

                <aside id="contact" class="rounded-2xl border border-slate-800 bg-slate-900/60 p-5 sm:p-6">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-50 sm:text-xl">
                        Let’s build something
                    </h2>
                    <p class="mt-3 text-sm text-slate-300">
                        Open to backend‑heavy roles, API integrations, marketplace automation,
                        and AI‑powered workflow projects.
                    </p>

                    <div class="mt-4 space-y-2 text-sm text-slate-200">
                        <p class="flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-slate-400">Email:</span>
                            <a href="mailto:shakilanwar1998@gmail.com" class="ml-1 text-emerald-300 hover:text-emerald-200">
                                shakilanwar1998@gmail.com
                            </a>
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="text-slate-400">Phone:</span>
                            <a href="tel:+8801738362296" class="ml-1 text-emerald-300 hover:text-emerald-200">
                                +880 1738 362 296
                            </a>
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .96 4.534.96 10.09c0 1.79.463 3.47 1.275 4.935L.96 24l9.07-2.38a11.722 11.722 0 001.92.16h.01c5.554 0 10.089-4.534 10.089-10.09 0-2.735-1.095-5.224-2.876-7.05"/>
                            </svg>
                            <span class="text-slate-400">WhatsApp:</span>
                            <a href="https://wa.me/8801738362296" target="_blank" rel="noopener noreferrer" class="ml-1 text-emerald-300 hover:text-emerald-200">
                                +880 1738 362 296
                            </a>
                        </p>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <a href="https://github.com/shakilanwar1998" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-full border border-slate-700 px-3 py-1.5 text-xs text-slate-300 transition hover:border-emerald-400/70 hover:text-emerald-300">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                            </svg>
                            GitHub
                        </a>
                        <a href="https://linkedin.com/in/shakilanwar1998" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-full border border-slate-700 px-3 py-1.5 text-xs text-slate-300 transition hover:border-emerald-400/70 hover:text-emerald-300">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            LinkedIn
                        </a>
                        <a href="https://facebook.com/shakilanwar1998" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-full border border-slate-700 px-3 py-1.5 text-xs text-slate-300 transition hover:border-emerald-400/70 hover:text-emerald-300">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Facebook
                        </a>
                    </div>
                </aside>
            </section>
        </main>
    </div>
</template>
