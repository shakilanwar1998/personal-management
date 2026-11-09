@verbatim
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Quick Annotation · AI Catalog Enrichment Case Study | Shakil Anwar</title>
  <meta name="description" content="How an n8n + LLM pipeline turned equipment photos into reviewed, catalog-ready listings: vision annotation, manual retrieval, specification, market-based pricing and human approval before any write-back.">
  <meta property="og:title" content="Quick Annotation — AI catalog enrichment with a human at the gate">
  <meta property="og:description" content="Vision AI, search agents and LLMs draft brand, model, manual, specs and price for every equipment record. People approve each field before it reaches the catalog.">
  <meta property="og:type" content="article">
  <meta name="theme-color" content="#F2F4F3">
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='12' fill='%231F4FD8'/%3E%3Crect x='14' y='20' width='36' height='24' rx='3' fill='none' stroke='%23F4B400' stroke-width='4'/%3E%3Cpath d='M14 32h36' stroke='white' stroke-width='3'/%3E%3C/svg%3E">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@62..125,400..900&family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap">

  <script>
    try { var t = localStorage.getItem('qa-theme'); if (t === 'light' || t === 'dark') document.documentElement.dataset.theme = t; } catch (e) {}
  </script>

  <!-- Tailwind (play CDN). For production, compile with the Tailwind CLI — see README.md -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    const tok = (v) => `rgb(var(--${v}) / <alpha-value>)`;
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            ground: tok('ground'),
            surface: tok('surface'),
            sunken: tok('sunken'),
            ink: tok('ink'),
            muted: tok('muted'),
            line: tok('line'),
            accent: { DEFAULT: tok('accent'), ink: tok('accent-ink'), soft: tok('accent-soft') },
            signal: { DEFAULT: tok('signal'), ink: tok('signal-ink') },
            ok: tok('ok'),
            bad: tok('bad'),
            warn: tok('warn'),
          },
          fontFamily: {
            display: ['Archivo', 'ui-sans-serif', 'system-ui', 'Segoe UI', 'sans-serif'],
            sans: ['"IBM Plex Sans"', 'ui-sans-serif', 'system-ui', 'Segoe UI', 'Helvetica', 'Arial', 'sans-serif'],
            mono: ['"IBM Plex Mono"', 'ui-monospace', 'SFMono-Regular', 'Menlo', 'Consolas', 'monospace'],
          },
          maxWidth: { prose: '65ch' },
        },
      },
    };
  </script>

  <style>
    :root {
      --ground: 242 244 243;
      --surface: 255 255 255;
      --sunken: 231 235 236;
      --ink: 15 23 30;
      --muted: 84 97 108;
      --line: 212 219 222;
      --accent: 31 79 216;
      --accent-ink: 255 255 255;
      --accent-soft: 225 233 252;
      --signal: 244 180 0;
      --signal-ink: 28 21 0;
      --ok: 21 124 82;
      --bad: 190 49 46;
      --warn: 158 98 0;
      color-scheme: light;
    }
    @media (prefers-color-scheme: dark) {
      :root:not([data-theme="light"]) {
        --ground: 11 16 21;
        --surface: 19 26 33;
        --sunken: 27 36 45;
        --ink: 230 236 239;
        --muted: 150 163 173;
        --line: 43 54 64;
        --accent: 124 157 255;
        --accent-ink: 7 13 30;
        --accent-soft: 30 42 74;
        --signal: 246 196 70;
        --signal-ink: 28 21 0;
        --ok: 88 204 148;
        --bad: 242 116 106;
        --warn: 236 176 72;
        color-scheme: dark;
      }
    }
    :root[data-theme="dark"] {
      --ground: 11 16 21;
      --surface: 19 26 33;
      --sunken: 27 36 45;
      --ink: 230 236 239;
      --muted: 150 163 173;
      --line: 43 54 64;
      --accent: 124 157 255;
      --accent-ink: 7 13 30;
      --accent-soft: 30 42 74;
      --signal: 246 196 70;
      --signal-ink: 28 21 0;
      --ok: 88 204 148;
      --bad: 242 116 106;
      --warn: 236 176 72;
      color-scheme: dark;
    }

    html { scroll-behavior: smooth; scroll-padding-top: 5rem; }
    body { background: rgb(var(--ground)); color: rgb(var(--ink)); overflow-x: clip; }
    h1, h2, h3 { text-wrap: balance; }
    p { text-wrap: pretty; }
    .font-display { font-stretch: 112%; }
    .display-tight { font-stretch: 118%; letter-spacing: -0.025em; line-height: 0.98; }
    .eyebrow { font-family: "IBM Plex Mono", ui-monospace, monospace; font-size: 0.72rem; letter-spacing: 0.16em; text-transform: uppercase; }
    .tnum { font-variant-numeric: tabular-nums; }
    :focus-visible { outline: 2px solid rgb(var(--accent)); outline-offset: 3px; border-radius: 4px; }

    /* The nameplate is a physical object: brushed aluminium in both themes */
    .plate {
      color: #1a232a;
      background:
        repeating-linear-gradient(90deg, rgba(255,255,255,.18) 0 1px, rgba(0,0,0,.035) 1px 3px),
        linear-gradient(135deg, #dfe4e7 0%, #b9c2c8 38%, #e8ecee 58%, #aab3b9 100%);
      box-shadow: inset 0 1px 0 rgba(255,255,255,.7), inset 0 -2px 0 rgba(0,0,0,.12), 0 24px 48px -24px rgba(8, 20, 40, .45);
      overflow: hidden;
    }
    .plate .engraved { text-shadow: 0 1px 0 rgba(255,255,255,.55); }
    .rivet {
      position: absolute; width: 11px; height: 11px; border-radius: 999px;
      background: radial-gradient(circle at 35% 30%, #fff 0 18%, #aeb6bb 45%, #6f787e 100%);
      box-shadow: 0 1px 1px rgba(0,0,0,.35);
    }
    .bbox { position: absolute; inset: -4px -7px; border: 1.5px solid #1F4FD8; border-radius: 3px; pointer-events: none; }
    .bbox::after {
      content: attr(data-label); position: absolute; left: calc(100% + 8px); top: 50%; transform: translateY(-50%);
      white-space: nowrap; background: #1F4FD8; color: #fff; font: 500 10px/1 "IBM Plex Mono", ui-monospace, monospace;
      padding: 4px 6px; border-radius: 3px; letter-spacing: .02em;
    }
    .scanline {
      position: absolute; left: 0; right: 0; top: 8%; height: 2px;
      background: linear-gradient(90deg, transparent, #1F4FD8 20%, #6f97ff 50%, #1F4FD8 80%, transparent);
      box-shadow: 0 0 18px 4px rgba(31, 79, 216, .35);
      animation: scan 4.5s ease-in-out infinite alternate;
    }
    @keyframes scan { from { top: 8%; } to { top: 92%; } }

    .field-row { transition: background-color .25s ease; }
    .field-row.is-changed { background-color: rgb(var(--accent-soft) / .55); }

    .hatch { background-image: repeating-linear-gradient(135deg, currentColor 0 1px, transparent 1px 7px); }

    @media (prefers-reduced-motion: reduce) {
      html { scroll-behavior: auto; }
      .scanline { animation: none; top: 55%; }
      * { transition: none !important; }
    }
  </style>
</head>

<body class="bg-ground text-ink font-sans antialiased text-[15px] leading-relaxed">

  <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-surface focus:px-3 focus:py-2">Skip to content</a>

  <!-- ============ NAV ============ -->
  <header class="sticky z-40 border-b border-line/70 bg-ground/85 backdrop-blur" style="top: env(safe-area-inset-top, 0px)">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
      <a href="#top" class="flex items-center gap-3">
        <span class="grid h-8 w-8 place-items-center rounded-md bg-ink font-mono text-[11px] font-semibold text-ground">SA</span>
        <span class="leading-tight">
          <span class="block text-sm font-semibold">Shakil Anwar</span>
          <span class="block text-xs text-muted">Full-Stack Developer | Laravel & Node.js Architect | SaaS & E-Commerce Platforms | API & Marketplace Integration Specialist | Business Automation | AI-Driven Solutions</span>
        </span>
      </a>
      <nav class="hidden items-center gap-7 text-sm text-muted md:flex" aria-label="Sections">
        <a class="hover:text-ink" href="#problem">Problem</a>
        <a class="hover:text-ink" href="#solution">Solution</a>
        <a class="hover:text-ink" href="#review">Human review</a>
        <a class="hover:text-ink" href="#results">Results</a>
        <a class="hover:text-ink" href="#services">Services</a>
      </nav>
      <div class="flex items-center gap-2">
        <a href="/projects" class="inline-flex h-9 shrink-0 items-center gap-1.5 whitespace-nowrap rounded-md border border-line px-3 text-sm font-medium text-muted hover:text-ink">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
          <span class="hidden sm:inline">All projects</span><span class="sm:hidden">Projects</span>
        </a>
        <button id="themeToggle" type="button" class="grid h-9 w-9 place-items-center rounded-md border border-line text-muted hover:text-ink" aria-label="Switch colour theme">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 3a9 9 0 1 0 9 9 7 7 0 0 1-9-9Z"/></svg>
        </button>
      </div>
    </div>
  </header>

  <main id="main">

    <!-- ============ HERO ============ -->
    <section id="top" class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
      <div class="grid items-center gap-12 py-14 lg:grid-cols-12 lg:gap-10 lg:py-20">
        <div class="lg:col-span-6">
          <p class="eyebrow inline-flex items-center gap-2 text-muted">
            <span class="h-2 w-2 rounded-full bg-signal"></span>
            Case study · <span class="font-semibold text-ink">Quick Annotation</span>
          </p>
          <h1 class="font-display display-tight mt-5 text-[2.2rem] font-extrabold sm:text-6xl">
            Equipment photos in.<br>
            <span class="text-accent">Approved, catalog-ready listings</span> out.
          </h1>
          <p class="mt-6 max-w-prose text-lg text-muted">
            A reseller of used process and lab equipment had a catalog full of photos but almost no data behind them. I built <strong class="font-semibold text-ink">Quick Annotation</strong>, an AI pipeline that reads the nameplate, finds the manual, drafts the specs and prices each item from real market offers. Then it hands everything to a reviewer. <strong class="font-semibold text-ink">No catalog record changes until a person approves it.</strong>
          </p>
          <div class="mt-8 flex flex-wrap gap-3">
            <a href="#solution" class="rounded-md bg-accent px-5 py-3 text-sm font-semibold text-accent-ink hover:opacity-90">See how it works</a>
          </div>

          <dl class="mt-12 grid grid-cols-2 gap-x-6 gap-y-6 border-t border-line pt-6 sm:grid-cols-4">
            <div>
              <dt class="text-xs text-muted">Workflow nodes</dt>
              <dd class="font-display tnum mt-1 text-3xl font-bold">85</dd>
            </div>
            <div>
              <dt class="text-xs text-muted">AI stages</dt>
              <dd class="font-display tnum mt-1 text-3xl font-bold">4</dd>
            </div>
            <div>
              <dt class="text-xs text-muted">Fields a human signs off</dt>
              <dd class="font-display tnum mt-1 text-3xl font-bold">6</dd>
            </div>
            <div>
              <dt class="text-xs text-muted">Hard-coded settings left</dt>
              <dd class="font-display tnum mt-1 text-3xl font-bold">0</dd>
            </div>
          </dl>
        </div>

        <!-- Nameplate + record -->
        <div class="lg:col-span-6">
          <figure class="plate relative mx-auto w-full max-w-md rounded-[10px] px-7 pb-9 pt-7 lg:mr-14" aria-label="Illustrative equipment nameplate, with the fields the vision model reads outlined">
            <span class="rivet left-3 top-3"></span><span class="rivet right-3 top-3"></span>
            <span class="rivet bottom-3 left-3"></span><span class="rivet bottom-3 right-3"></span>
            <div class="engraved flex items-start justify-between">
              <span class="relative">
                <span class="font-display text-3xl font-black tracking-tight">GEA</span>
                <span class="bbox" data-label="brand · 0.94"></span>
              </span>
              <span class="font-mono text-[10px] font-semibold tracking-[.2em]">CE</span>
            </div>
            <p class="engraved mt-1 font-mono text-[11px] uppercase tracking-[.28em]">Homogenizer</p>
            <dl class="engraved mt-5 grid grid-cols-[auto_1fr] gap-x-5 gap-y-2 font-mono text-[13px]">
              <dt class="opacity-60">TYPE</dt>
              <dd><span class="relative inline-block">Ariete NS2006H<span class="bbox" data-label="model · 0.91"></span></span></dd>
              <dt class="opacity-60">SER.NO</dt>
              <dd>82&#8226;&#8226;&#8211;&#8226;&#8226;&#8226;</dd>
              <dt class="opacity-60">YEAR</dt>
              <dd><span class="relative inline-block">2016<span class="bbox" data-label="year"></span></span></dd>
              <dt class="opacity-60">SUPPLY</dt>
              <dd>400 V · 50 Hz · 3~</dd>
            </dl>
            <span class="scanline" aria-hidden="true"></span>
          </figure>

          <section class="relative mx-auto mt-4 w-full max-w-md rounded-xl border border-line bg-surface shadow-[0_30px_60px_-30px_rgba(8,20,40,.35)] lg:-mt-10 lg:mr-0" aria-label="Catalog record before and after enrichment">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line px-4 py-3">
              <div>
                <p class="eyebrow text-muted">Catalog record</p>
                <p class="text-sm font-semibold">Homogenizer with feed pump</p>
              </div>
              <div class="flex rounded-md bg-sunken p-0.5 text-xs font-semibold" role="group" aria-label="Show record state">
                <button type="button" data-view="before" class="rounded px-3 py-1.5 text-muted">Before</button>
                <button type="button" data-view="after" class="rounded px-3 py-1.5">After AI</button>
              </div>
            </div>
            <dl id="record" class="divide-y divide-line text-sm"></dl>
            <div class="flex items-center justify-between gap-3 border-t border-line px-4 py-3 text-xs">
              <span id="recordState" class="inline-flex items-center gap-1.5 rounded-full bg-signal/20 px-2.5 py-1 font-semibold text-warn">
                <span class="h-1.5 w-1.5 rounded-full bg-signal"></span>Pending review
              </span>
              <span class="text-muted">Illustrative example, not client data</span>
            </div>
          </section>
        </div>
      </div>
    </section>

    <!-- ============ CONTEXT ============ -->
    <section class="border-y border-line bg-surface">
      <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <dl class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
          <div>
            <dt class="eyebrow text-muted">Client</dt>
            <dd class="mt-2 text-sm">European company that catalogs and resells used process, food and lab equipment (name withheld)</dd>
          </div>
          <div>
            <dt class="eyebrow text-muted">Starting point</dt>
            <dd class="mt-2 text-sm">A SaaS asset catalog with a REST API: photos per item, short free-text titles, few structured fields</dd>
          </div>
          <div>
            <dt class="eyebrow text-muted">Timeline</dt>
            <dd class="mt-2 text-sm">April – June 2026, four milestones, piloted on a batch of 20 real items</dd>
          </div>
          <div>
            <dt class="eyebrow text-muted">My role</dt>
            <dd class="mt-2 text-sm">Solution design, n8n build, prompt design, review app integration and operator documentation</dd>
          </div>
        </dl>
        <ul class="mt-8 flex flex-wrap gap-2 text-xs font-medium" aria-label="Technology used">
          <li class="rounded-full border border-line px-3 py-1">n8n</li>
          <li class="rounded-full border border-line px-3 py-1">OpenAI GPT-4o (vision)</li>
          <li class="rounded-full border border-line px-3 py-1">GPT-4o-mini</li>
          <li class="rounded-full border border-line px-3 py-1">Serper · Google Search API</li>
          <li class="rounded-full border border-line px-3 py-1">Google Sheets</li>
          <li class="rounded-full border border-line px-3 py-1">n8n Data Tables</li>
          <li class="rounded-full border border-line px-3 py-1">REST &amp; webhooks</li>
          <li class="rounded-full border border-line px-3 py-1">Laravel</li>
          <li class="rounded-full border border-line px-3 py-1">Excel / XLSX</li>
        </ul>
      </div>
    </section>

    <!-- ============ PROBLEM ============ -->
    <section id="problem" class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
      <div class="grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-5">
          <p class="eyebrow text-accent">The problem</p>
          <h2 class="font-display mt-3 text-4xl font-extrabold tracking-tight sm:text-5xl">The catalog had photos. It didn&rsquo;t have answers.</h2>
          <p class="mt-6 max-w-prose text-muted">
            A typical record looked like <span class="whitespace-nowrap rounded bg-sunken px-1.5 py-0.5 font-mono text-[13px] text-ink">Homogenizer (Brand: GEA) with Feed Pump</span> plus a few photos. Before an item could go on sale, someone had to read the nameplate, look for a manual, compare prices across dealers and auctions, write a description and type it all into the catalog. After that, they had to prepare the eBay listing too.
          </p>
          <p class="mt-4 max-w-prose text-muted">That meant hours of research for every item, and none of it could be repeated or audited.</p>
        </div>

        <div class="lg:col-span-7">
          <ul class="grid gap-px overflow-hidden rounded-xl border border-line bg-line sm:grid-cols-2">
            <li class="bg-surface p-6">
              <h3 class="font-semibold">Research by hand, item by item</h3>
              <p class="mt-2 text-sm text-muted">Brand, model and year were pieced together from blurry photos and Google, one listing at a time.</p>
            </li>
            <li class="bg-surface p-6">
              <h3 class="font-semibold">Prices based on gut feel</h3>
              <p class="mt-2 text-sm text-muted">Used-equipment search results are noisy: spare parts, accessories and odd auctions sit right next to real offers.</p>
            </li>
            <li class="bg-surface p-6">
              <h3 class="font-semibold">Manuals hard to find</h3>
              <p class="mt-2 text-sm text-muted">Real datasheets were buried among marketplace listings, forum threads and parts catalogs.</p>
            </li>
            <li class="bg-surface p-6">
              <h3 class="font-semibold">No trail of evidence</h3>
              <p class="mt-2 text-sm text-muted">Nobody could see which source justified a model number or price, so errors were hard to catch.</p>
            </li>
          </ul>

          <div class="mt-6 rounded-xl border border-ink/80 p-6">
            <p class="eyebrow">Non-negotiables from the client</p>
            <ul class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
              <li class="flex gap-3"><span class="mt-2 h-1.5 w-1.5 shrink-0 bg-accent"></span>The catalog stays the only source of truth. No second data model and no silent overwrites.</li>
              <li class="flex gap-3"><span class="mt-2 h-1.5 w-1.5 shrink-0 bg-accent"></span>AI output is only a proposal until a person approves it.</li>
              <li class="flex gap-3"><span class="mt-2 h-1.5 w-1.5 shrink-0 bg-accent"></span>Each step can be run, checked and re-run on its own, and is tracked by item and run.</li>
              <li class="flex gap-3"><span class="mt-2 h-1.5 w-1.5 shrink-0 bg-accent"></span>People who aren't developers can adjust prompts, rules and prices.</li>
            </ul>
          </div>
        </div>
      </div>

      <blockquote class="mt-16 border-l-4 border-signal pl-6">
        <p class="font-display max-w-3xl text-2xl font-bold leading-snug sm:text-3xl">Calling an LLM was the easy part. The real work was making its output reliable enough to write into a production catalog.</p>
      </blockquote>
    </section>

    <!-- ============ SOLUTION: PIPELINE ============ -->
    <section id="solution" class="border-t border-line bg-surface">
      <div class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
        <div class="max-w-3xl">
          <p class="eyebrow text-accent">The solution</p>
          <h2 class="font-display mt-3 text-4xl font-extrabold tracking-tight sm:text-5xl">An AI agent pipeline with a person approving the results</h2>
          <p class="mt-5 text-muted">Quick Annotation is a single n8n workflow that runs everything: it pulls equipment from the catalog, has AI research each item and stores the results as proposals. Proposals move on only after a reviewer approves them. Each stage has a fixed input and output, keeps its own log and can be re-run by itself.</p>
        </div>

        <!-- Flow diagram -->
        <div class="mt-12 overflow-x-auto pb-2">
          <div class="flex min-w-[900px] items-stretch gap-2 text-sm">
            <div class="flex w-28 shrink-0 flex-col justify-center rounded-lg border border-line bg-sunken px-3 py-4">
              <span class="eyebrow text-[10px] text-muted">System</span>
              <span class="mt-1 font-semibold">Asset catalog API</span>
            </div>
            <div class="grid place-items-center text-muted" aria-hidden="true"><svg class="h-4 w-5" viewBox="0 0 20 16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M1 8h16M12 3l5 5-5 5"/></svg></div>

            <div class="relative flex flex-1 gap-2 rounded-xl border-2 border-dashed border-accent/60 p-3 pt-7">
              <span class="eyebrow absolute left-3 top-2 text-[10px] text-accent">AI enrichment · saved as proposals only</span>
              <div class="flex flex-1 flex-col rounded-lg bg-accent px-3 py-3 text-accent-ink">
                <span class="font-mono text-[10px] opacity-80">GPT-4o vision</span>
                <span class="mt-auto font-semibold">Annotate</span>
              </div>
              <div class="flex flex-1 flex-col rounded-lg bg-accent px-3 py-3 text-accent-ink">
                <span class="font-mono text-[10px] opacity-80">Search agent</span>
                <span class="mt-auto font-semibold">Find manual</span>
              </div>
              <div class="flex flex-1 flex-col rounded-lg bg-accent px-3 py-3 text-accent-ink">
                <span class="font-mono text-[10px] opacity-80">LLM</span>
                <span class="mt-auto font-semibold">Specify</span>
              </div>
              <div class="flex flex-1 flex-col rounded-lg bg-accent px-3 py-3 text-accent-ink">
                <span class="font-mono text-[10px] opacity-80">Market + rules</span>
                <span class="mt-auto font-semibold">Price</span>
              </div>
            </div>

            <div class="grid place-items-center text-muted" aria-hidden="true"><svg class="h-4 w-5" viewBox="0 0 20 16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M1 8h16M12 3l5 5-5 5"/></svg></div>
            <div class="flex w-32 shrink-0 flex-col justify-center rounded-lg bg-signal px-3 py-4 text-signal-ink">
              <span class="eyebrow text-[10px] opacity-75">Human gate</span>
              <span class="mt-1 font-semibold">Review queue</span>
            </div>
            <div class="grid place-items-center text-muted" aria-hidden="true"><svg class="h-4 w-5" viewBox="0 0 20 16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M1 8h16M12 3l5 5-5 5"/></svg></div>
            <div class="flex w-36 shrink-0 flex-col gap-2">
              <div class="flex flex-1 flex-col justify-center rounded-lg border border-line bg-sunken px-3 py-2">
                <span class="font-semibold">Catalog write-back</span>
                <span class="text-xs text-muted">approved fields only</span>
              </div>
              <div class="flex flex-1 flex-col justify-center rounded-lg border border-line bg-sunken px-3 py-2">
                <span class="font-semibold">eBay export</span>
                <span class="text-xs text-muted">Excel import format</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Stage detail table -->
        <ol class="mt-14 border-t border-line">
          <li class="hidden grid-cols-12 gap-6 border-b border-line py-3 md:grid">
            <span class="eyebrow col-span-3 text-muted">Stage</span>
            <span class="eyebrow col-span-5 text-muted">What it does</span>
            <span class="eyebrow col-span-4 text-muted">Guardrail</span>
          </li>

          <li class="grid gap-3 border-b border-line py-6 md:grid-cols-12 md:gap-6">
            <div class="md:col-span-3">
              <span class="font-mono text-xs text-muted">01</span>
              <h3 class="font-display text-xl font-bold">Ingest</h3>
              <p class="font-mono text-xs text-muted">Catalog REST API</p>
            </div>
            <p class="text-sm md:col-span-5">Pages through the catalog export in batches with a safety cutoff, cleans up each record and checks it against the proposal table. You can also give it a list of QR codes or inventory numbers to process only those items.</p>
            <p class="text-sm text-muted md:col-span-4">Items that are already pending, partly approved or approved are skipped, so running it again never wipes a reviewer&rsquo;s work.</p>
          </li>

          <li class="grid gap-3 border-b border-line py-6 md:grid-cols-12 md:gap-6">
            <div class="md:col-span-3">
              <span class="font-mono text-xs text-muted">02</span>
              <h3 class="font-display text-xl font-bold">Annotate</h3>
              <p class="font-mono text-xs text-muted">GPT-4o vision</p>
            </div>
            <p class="text-sm md:col-span-5">Sends up to 8 photos per item at high detail. The model copies the text on nameplates and labels exactly, then returns strict JSON: brand, model, year, equipment type, condition, confidence score and an estimated new price.</p>
            <p class="text-sm text-muted md:col-span-4">If the catalog text and the photo disagree, the photo wins. Output the model can't parse is flagged instead of guessed, and condition must be one of new, refurbished, used or unknown.</p>
          </li>

          <li class="grid gap-3 border-b border-line py-6 md:grid-cols-12 md:gap-6">
            <div class="md:col-span-3">
              <span class="font-mono text-xs text-muted">03</span>
              <h3 class="font-display text-xl font-bold">Find the manual</h3>
              <p class="font-mono text-xs text-muted">Serper · Google Search</p>
            </div>
            <p class="text-sm md:col-span-5">Searches for brand + model + &ldquo;user manual&rdquo;, then scores up to 20 results to decide which ones are real technical documents.</p>
            <p class="text-sm text-muted md:col-span-4">Needs at least a 50% brand and model match (ignoring filler words like &ldquo;GmbH&rdquo;). Marketplaces and product-listing pages are rejected. Items with no brand or model are logged and skipped instead of searched blindly.</p>
          </li>

          <li class="grid gap-3 border-b border-line py-6 md:grid-cols-12 md:gap-6">
            <div class="md:col-span-3">
              <span class="font-mono text-xs text-muted">04</span>
              <h3 class="font-display text-xl font-bold">Specify</h3>
              <p class="font-mono text-xs text-muted">GPT-4o-mini</p>
            </div>
            <p class="text-sm md:col-span-5">Combines the catalog entry, the photo findings and the accepted documents into a checked brand and model, a general description and standardized key specs.</p>
            <p class="text-sm text-muted md:col-span-4">The model can't browse the web. It uses only the evidence it is given, and everything it returns is marked as a proposal.</p>
          </li>

          <li class="grid gap-3 border-b border-line py-6 md:grid-cols-12 md:gap-6">
            <div class="md:col-span-3">
              <span class="font-mono text-xs text-muted">05</span>
              <h3 class="font-display text-xl font-bold">Price</h3>
              <p class="font-mono text-xs text-muted">Search + LLM + rules engine</p>
            </div>
            <p class="text-sm md:col-span-5">Searches offers in the local market in euros, sets a reference price for the item new, and works out price ranges for new, refurbished and used condition.</p>
            <p class="text-sm text-muted md:col-span-4">Spare parts are filtered out and currencies are never converted. Outlier prices are removed, and confidence is capped when there is little market data.</p>
          </li>

          <li class="grid gap-3 border-b border-line py-6 md:grid-cols-12 md:gap-6">
            <div class="md:col-span-3">
              <span class="font-mono text-xs text-muted">06</span>
              <h3 class="font-display text-xl font-bold">Human review</h3>
              <p class="font-mono text-xs text-muted">Data Table + Laravel app</p>
            </div>
            <p class="text-sm md:col-span-5">Reviewers see each proposal next to the original record, with links to the evidence, and approve or reject it one field at a time.</p>
            <p class="text-sm text-muted md:col-span-4">Nothing reaches the catalog without an explicit approval.</p>
          </li>

          <li class="grid gap-3 border-b border-line py-6 md:grid-cols-12 md:gap-6">
            <div class="md:col-span-3">
              <span class="font-mono text-xs text-muted">07</span>
              <h3 class="font-display text-xl font-bold">Write back &amp; export</h3>
              <p class="font-mono text-xs text-muted">Catalog API + XLSX</p>
            </div>
            <p class="text-sm md:col-span-5">Each approved field is sent to the catalog right away. The manual is downloaded and attached as a file, and approved records are exported in the Excel format the eBay listing import uses.</p>
            <p class="text-sm text-muted md:col-span-4">A manual is uploaded only if it is a PDF with at least 10 pages, which keeps one-page spec sheets and parts lists out.</p>
          </li>
        </ol>
        <p class="mt-4 max-w-prose text-xs text-muted">Stages 04 and 05 share one structured LLM call but return separate outputs, so each stage stays distinct while using fewer tokens.</p>
      </div>
    </section>

    <!-- ============ DEEP DIVE: MANUALS ============ -->
    <section id="manuals" class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
      <div class="grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-5">
          <p class="eyebrow text-accent">A closer look · manual search</p>
          <h2 class="font-display mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Tells a real manual apart from a sales listing</h2>
          <p class="mt-5 max-w-prose text-muted">Search for any piece of used equipment and most of the results are shops selling it. The search agent applies four checks before a link counts as a manual, and the LLM applies one more:</p>
          <ol class="mt-6 space-y-4 text-sm">
            <li class="flex gap-4"><span class="font-mono text-xs text-muted">1</span><span><strong class="font-semibold">Brand + model match.</strong> Words are normalized, filler like &ldquo;Inc&rdquo; or &ldquo;GmbH&rdquo; is ignored, and at least half the words must match (the threshold can be changed).</span></li>
            <li class="flex gap-4"><span class="font-mono text-xs text-muted">2</span><span><strong class="font-semibold">Marketplace block list.</strong> eBay, Amazon, AliExpress, LabX, Mascus, Catawiki and other sites are rejected outright.</span></li>
            <li class="flex gap-4"><span class="font-mono text-xs text-muted">3</span><span><strong class="font-semibold">URL path check.</strong> Paths with words like <code class="font-mono text-[13px]">/manuals/</code> or <code class="font-mono text-[13px]">/support/</code> score higher; <code class="font-mono text-[13px]">/product/</code> or <code class="font-mono text-[13px]">/shop/</code> are rejected.</span></li>
            <li class="flex gap-4"><span class="font-mono text-xs text-muted">4</span><span><strong class="font-semibold">Document keywords.</strong> Words like manual, datasheet, operator, service guide or handbook in the result.</span></li>
            <li class="flex gap-4"><span class="font-mono text-xs text-muted">5</span><span><strong class="font-semibold">LLM check.</strong> The model rejects parts lists, forum threads and category pages that got through the rules.</span></li>
          </ol>
        </div>

        <div class="lg:col-span-7">
          <div class="rounded-xl border border-line bg-surface">
            <div class="flex items-center justify-between gap-3 border-b border-line px-4 py-3">
              <p class="font-mono text-xs"><span class="text-muted">query</span> &ldquo;GEA Ariete NS2006H user manual&rdquo;</p>
              <span class="eyebrow text-[10px] text-muted">Illustrative</span>
            </div>
            <ul class="divide-y divide-line text-sm">
              <li class="flex flex-col gap-2 px-4 py-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                  <p class="font-medium">Ariete NS2006H — Operating &amp; Maintenance Manual</p>
                  <p class="truncate font-mono text-xs text-muted">manufacturer support site · /support/manuals/ns2006h.pdf</p>
                </div>
                <span class="inline-flex shrink-0 items-center gap-1.5 self-start rounded-full bg-ok/15 px-2.5 py-1 text-xs font-semibold text-ok">Accepted · match 1.00</span>
              </li>
              <li class="flex flex-col gap-2 px-4 py-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                  <p class="font-medium">GEA Ariete Homogenisator gebraucht</p>
                  <p class="truncate font-mono text-xs text-muted">ebay.at · /itm/…</p>
                </div>
                <span class="inline-flex shrink-0 items-center gap-1.5 self-start rounded-full bg-bad/10 px-2.5 py-1 text-xs font-semibold text-bad">Rejected · marketplace</span>
              </li>
              <li class="flex flex-col gap-2 px-4 py-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                  <p class="font-medium">Used homogenizer NS2006H for sale — in stock</p>
                  <p class="truncate font-mono text-xs text-muted">equipment dealer · /shop/product/…</p>
                </div>
                <span class="inline-flex shrink-0 items-center gap-1.5 self-start rounded-full bg-bad/10 px-2.5 py-1 text-xs font-semibold text-bad">Rejected · listing path</span>
              </li>
              <li class="flex flex-col gap-2 px-4 py-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                  <p class="font-medium">Homogenizer valve leaking — any tips?</p>
                  <p class="truncate font-mono text-xs text-muted">process engineering forum · /thread/…</p>
                </div>
                <span class="inline-flex shrink-0 items-center gap-1.5 self-start rounded-full bg-bad/10 px-2.5 py-1 text-xs font-semibold text-bad">Rejected · match 0.33</span>
              </li>
              <li class="flex flex-col gap-2 px-4 py-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                  <p class="font-medium">NS2006H seal kit &amp; spare parts list</p>
                  <p class="truncate font-mono text-xs text-muted">parts supplier · /downloads/parts.pdf</p>
                </div>
                <span class="inline-flex shrink-0 items-center gap-1.5 self-start rounded-full bg-bad/10 px-2.5 py-1 text-xs font-semibold text-bad">Rejected by LLM · parts list</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ DEEP DIVE: PRICING ============ -->
    <section id="pricing" class="border-y border-line bg-surface">
      <div class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
        <div class="grid gap-10 lg:grid-cols-12">
          <div class="lg:col-span-5">
            <p class="eyebrow text-accent">A closer look · pricing</p>
            <h2 class="font-display mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Prices backed by real market offers</h2>
            <p class="mt-5 max-w-prose text-muted">A price only helps if a reviewer can see where it came from. The pricing stage combines an AI estimate with fixed rules and filtered market offers:</p>
          </div>
          <ol class="grid gap-5 text-sm sm:grid-cols-2 lg:col-span-7">
            <li><span class="font-mono text-xs text-muted">Step 1</span><p class="mt-1"><strong class="font-semibold">Reference price.</strong> Estimates what the complete unit costs new, using euro-priced offers only.</p></li>
            <li><span class="font-mono text-xs text-muted">Step 2</span><p class="mt-1"><strong class="font-semibold">Condition ranges.</strong> New is 80–110% of the reference, refurbished 30–80% and used 10–30%.</p></li>
            <li><span class="font-mono text-xs text-muted">Step 3</span><p class="mt-1"><strong class="font-semibold">Market evidence.</strong> Searches the local market (Austria, German-language results) and filters out spare parts and accessories.</p></li>
            <li><span class="font-mono text-xs text-muted">Step 4</span><p class="mt-1"><strong class="font-semibold">Outlier removal.</strong> Removes outliers with the interquartile range (IQR × 1.5), and only when there are at least 4 offers. When data is thin, it falls back to an estimate with capped confidence.</p></li>
          </ol>
        </div>

        <figure class="mt-12 rounded-xl border border-line bg-ground p-5 sm:p-6">
          <figcaption class="flex flex-wrap items-baseline justify-between gap-2">
            <span class="font-semibold">Used homogenizer: price proposal from 7 market offers</span>
            <span class="eyebrow text-[10px] text-muted">Illustrative figures · EUR</span>
          </figcaption>

          <div class="mt-6 overflow-x-auto">
            <div class="min-w-[600px] pr-6">
              <!-- bands -->
              <div class="relative h-12">
                <div class="absolute inset-y-0 rounded-sm bg-accent/15" style="left:8%; width:16%"><span class="absolute left-2 top-1.5 whitespace-nowrap font-mono text-[10px] text-accent">Used 10–30%</span></div>
                <div class="absolute inset-y-0 rounded-sm bg-accent/10" style="left:24%; width:40%"><span class="absolute left-2 top-1.5 whitespace-nowrap font-mono text-[10px] text-accent">Refurbished 30–80%</span></div>
                <div class="absolute inset-y-0 rounded-sm bg-accent/20" style="left:64%; width:24%"><span class="absolute left-2 top-1.5 whitespace-nowrap font-mono text-[10px] text-accent">New 80–110%</span></div>
                <div class="absolute -bottom-1 -top-1 border-l-2 border-dashed border-ink" style="left:80%"><span class="absolute bottom-1 left-1.5 whitespace-nowrap font-mono text-[10px] font-semibold">Ref. new €48,000</span></div>
              </div>
              <!-- offers -->
              <div class="relative mt-3 h-16 border-t border-line">
                <div class="absolute -bottom-0 top-0 border-l-2 border-signal" style="left:17.5%"></div>
                <span class="absolute h-3 w-3 -translate-x-1/2 rounded-full border-2 border-bad bg-ground" style="left:4.83%; top:22px" title="€2,900 · removed: below 60% of median"></span>
                <span class="absolute h-3 w-3 -translate-x-1/2 rounded-full bg-accent" style="left:14.83%; top:12px" title="€8,900"></span>
                <span class="absolute h-3 w-3 -translate-x-1/2 rounded-full bg-accent" style="left:16.33%; top:32px" title="€9,800"></span>
                <span class="absolute h-3 w-3 -translate-x-1/2 rounded-full bg-accent ring-2 ring-signal" style="left:17.5%; top:12px" title="€10,500 · median"></span>
                <span class="absolute h-3 w-3 -translate-x-1/2 rounded-full bg-accent" style="left:18.67%; top:32px" title="€11,200"></span>
                <span class="absolute h-3 w-3 -translate-x-1/2 rounded-full bg-accent" style="left:20.67%; top:12px" title="€12,400"></span>
                <span class="absolute h-3 w-3 -translate-x-1/2 rounded-full border-2 border-bad bg-ground" style="left:51.67%; top:22px" title="€31,000 · removed: above 200% of median"></span>
                <span class="absolute whitespace-nowrap font-mono text-[10px] text-bad" style="left:53.5%; top:20px">€31,000 · above 2× median, removed</span>
                <span class="absolute whitespace-nowrap font-mono text-[10px] font-semibold" style="left:18.5%; top:48px">median €10,500</span>
              </div>
              <!-- axis -->
              <div class="relative mt-1 h-6 border-t border-ink/60 font-mono text-[10px] text-muted tnum">
                <span class="absolute top-1" style="left:0">€0</span>
                <span class="absolute top-1 -translate-x-1/2" style="left:16.667%">10k</span>
                <span class="absolute top-1 -translate-x-1/2" style="left:33.333%">20k</span>
                <span class="absolute top-1 -translate-x-1/2" style="left:50%">30k</span>
                <span class="absolute top-1 -translate-x-1/2" style="left:66.667%">40k</span>
                <span class="absolute top-1 -translate-x-1/2" style="left:83.333%">50k</span>
                <span class="absolute top-1 -translate-x-full" style="left:100%">60k</span>
              </div>
            </div>
          </div>

          <div class="mt-5 flex flex-wrap gap-x-6 gap-y-2 text-xs text-muted">
            <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-accent"></span>Offer kept</span>
            <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full border-2 border-bad"></span>Outlier removed</span>
            <span class="inline-flex items-center gap-2"><span class="h-3 w-0.5 bg-signal"></span>Median of kept offers</span>
            <span class="inline-flex items-center gap-2"><span class="h-3 w-0 border-l-2 border-dashed border-ink"></span>AI reference (new)</span>
          </div>
          <p class="mt-4 border-t border-line pt-4 text-sm"><strong class="font-semibold">Proposal sent to the reviewer:</strong> used, <span class="tnum">€10,500</span> (offers kept: <span class="tnum">€8,900–€12,400</span>). This falls inside the 10–30% used range, and the reviewer can open every offer behind it.</p>
        </figure>
      </div>
    </section>

    <!-- ============ DEEP DIVE: HUMAN REVIEW (interactive) ============ -->
    <section id="review" class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
      <div class="grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-5">
          <p class="eyebrow text-accent">A closer look · human in the loop</p>
          <h2 class="font-display mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">AI does the research. People make the call.</h2>
          <p class="mt-5 max-w-prose text-muted">Reviewers work in a queue inside the client&rsquo;s Laravel listing tool, which talks to n8n through two webhooks: one to load proposals and one to record decisions. They approve or reject each field separately, so a correct brand can go live even while the price is still under review.</p>
          <p class="mt-4 max-w-prose text-muted">Try it: approve a few fields and watch the status and the catalog updates change.</p>

          <div class="mt-8">
            <p class="eyebrow text-muted">Record status</p>
            <ol class="mt-3 flex items-center gap-2 text-sm font-semibold" id="stateTrack">
              <li data-state="pending" class="rounded-full border border-line px-3 py-1">Pending</li>
              <li aria-hidden="true" class="text-muted">→</li>
              <li data-state="partial" class="rounded-full border border-line px-3 py-1">Partial</li>
              <li aria-hidden="true" class="text-muted">→</li>
              <li data-state="approved" class="rounded-full border border-line px-3 py-1">Approved</li>
            </ol>
          </div>
        </div>

        <div class="lg:col-span-7">
          <div class="rounded-xl border border-line bg-surface">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line px-4 py-3">
              <div>
                <p class="eyebrow text-muted">Review queue · 1 of 20</p>
                <p class="text-sm font-semibold">Homogenizer with feed pump</p>
              </div>
              <button type="button" id="reviewReset" class="rounded-md border border-line px-3 py-1.5 text-xs font-semibold text-muted hover:text-ink">Reset demo</button>
            </div>
            <ul id="reviewFields" class="divide-y divide-line"></ul>
          </div>

          <div class="mt-4 rounded-xl border border-line bg-ground">
            <p class="eyebrow border-b border-line px-4 py-2.5 text-muted">Sent to catalog API</p>
            <ol id="reviewLog" class="max-h-44 space-y-1.5 overflow-y-auto px-4 py-3 font-mono text-xs" aria-live="polite">
              <li class="text-muted">Nothing sent yet. Proposals stay in the review table until someone approves them.</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ DEEP DIVE: CONTROL PANEL ============ -->
    <section id="control" class="border-y border-line bg-surface">
      <div class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
        <div class="grid gap-12 lg:grid-cols-12">
          <div class="lg:col-span-5">
            <p class="eyebrow text-accent">A closer look · settings in a spreadsheet</p>
            <h2 class="font-display mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Change the rules by editing a spreadsheet, not the code</h2>
            <p class="mt-5 max-w-prose text-muted">The first version had prompts, keyword lists, price ranges and API keys written directly into the workflow, so every change needed a developer. In a follow-up milestone I moved every one of those settings into a single Google Sheet.</p>
            <ul class="mt-6 space-y-3 text-sm">
              <li class="flex gap-3"><span class="mt-2 h-1.5 w-1.5 shrink-0 bg-accent"></span>Settings are read once at the start of each run and apply from the next run on.</li>
              <li class="flex gap-3"><span class="mt-2 h-1.5 w-1.5 shrink-0 bg-accent"></span>Keyword lists grow as you add rows, with no changes in n8n.</li>
              <li class="flex gap-3"><span class="mt-2 h-1.5 w-1.5 shrink-0 bg-accent"></span>Blank cells fall back to safe defaults, and the workflow never writes to the sheet.</li>
              <li class="flex gap-3"><span class="mt-2 h-1.5 w-1.5 shrink-0 bg-accent"></span>Switching to another catalog account means changing one cell, and the review queue follows.</li>
            </ul>
          </div>

          <div class="lg:col-span-7">
            <div class="overflow-hidden rounded-xl border border-line bg-ground">
              <div class="flex items-center gap-2 border-b border-line bg-surface px-4 py-2.5">
                <span class="h-3 w-3 rounded-sm bg-ok"></span>
                <span class="text-sm font-semibold">n8n workflow</span>
                <span class="text-xs text-muted">· Google Sheets</span>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full min-w-[460px] border-collapse font-mono text-[12.5px]">
                  <thead>
                    <tr class="bg-sunken text-muted">
                      <th class="w-10 border-b border-r border-line py-1.5 font-normal"></th>
                      <th class="border-b border-r border-line px-3 py-1.5 text-left font-normal">A · setting</th>
                      <th class="border-b border-line px-3 py-1.5 text-left font-normal">B · value</th>
                    </tr>
                  </thead>
                  <tbody id="sheetBody"></tbody>
                </table>
              </div>
              <div class="flex gap-1 overflow-x-auto border-t border-line bg-sunken px-2 pt-1" role="tablist" aria-label="Config tabs" id="sheetTabs"></div>
            </div>
            <p class="mt-3 text-xs text-muted">Keys are hidden. Values are shown as they were at hand-over.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ ENGINEERING ============ -->
    <section id="engineering" class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
      <div class="max-w-3xl">
        <p class="eyebrow text-accent">Production engineering</p>
        <h2 class="font-display mt-3 text-4xl font-extrabold tracking-tight sm:text-5xl">Runs on its own and reports every failure</h2>
        <p class="mt-5 text-muted">What makes a demo into something a business can depend on:</p>
      </div>
      <dl class="mt-12 grid gap-x-10 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
        <div class="border-t-2 border-ink pt-4">
          <dt class="font-semibold">Traceable by item and run</dt>
          <dd class="mt-2 text-sm text-muted">Each step logs its input, output and any error by equipment ID and run ID.</dd>
        </div>
        <div class="border-t-2 border-ink pt-4">
          <dt class="font-semibold">Re-run one item, not the whole batch</dt>
          <dd class="mt-2 text-sm text-muted">A replay endpoint re-processes a single QR code. When a step fails, it saves a checkpoint so work can resume from there.</dd>
        </div>
        <div class="border-t-2 border-ink pt-4">
          <dt class="font-semibold">Review work is never overwritten</dt>
          <dd class="mt-2 text-sm text-muted">Records are updated in place, not duplicated. Only rejected items are sent back through annotation.</dd>
        </div>
        <div class="border-t-2 border-ink pt-4">
          <dt class="font-semibold">Careful handling of AI output</dt>
          <dd class="mt-2 text-sm text-muted">Malformed JSON is flagged, prices are cleaned up and categories are checked against a fixed list.</dd>
        </div>
        <div class="border-t-2 border-ink pt-4">
          <dt class="font-semibold">Several accounts supported</dt>
          <dd class="mt-2 text-sm text-muted">The active catalog account is picked in the settings sheet, and proposals, lookups and approvals only cover that account.</dd>
        </div>
        <div class="border-t-2 border-ink pt-4">
          <dt class="font-semibold">Scheduled or on demand</dt>
          <dd class="mt-2 text-sm text-muted">Runs on a schedule, from a webhook with batch size and starting point, or for a chosen list of items. A safety cutoff stops runaway paging.</dd>
        </div>
        <div class="border-t-2 border-ink pt-4">
          <dt class="font-semibold">Excel in, Excel out</dt>
          <dd class="mt-2 text-sm text-muted">A separate job accepts an XLSX file, enriches each row in the background and sends the finished file back to a callback URL.</dd>
        </div>
        <div class="border-t-2 border-ink pt-4">
          <dt class="font-semibold">Documentation for the ops team</dt>
          <dd class="mt-2 text-sm text-muted">Includes a user guide for non-developers, an operations runbook, a troubleshooting table and a recorded demo walkthrough.</dd>
        </div>
      </dl>
    </section>

    <!-- ============ DELIVERY ============ -->
    <section id="delivery" class="border-y border-line bg-surface">
      <div class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-4">
          <div>
            <p class="eyebrow text-accent">Delivery</p>
            <h2 class="font-display mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Delivered in milestones, each one tested</h2>
          </div>
          <p class="max-w-sm text-sm text-muted">Each milestone had written deliverables and pass conditions, so the client could accept work step by step.</p>
        </div>
        <ol class="mt-10 grid gap-px overflow-hidden rounded-xl border border-line bg-line md:grid-cols-4">
          <li class="bg-surface p-6">
            <span class="font-mono text-xs text-muted">M1</span>
            <h3 class="mt-2 font-semibold">Contracts &amp; skeleton</h3>
            <p class="mt-2 text-sm text-muted">Defined inputs and outputs for each step, an n8n skeleton with visible outputs and error states, catalog import, and the proposal and review-status data model.</p>
          </li>
          <li class="bg-surface p-6">
            <span class="font-mono text-xs text-muted">M2</span>
            <h3 class="mt-2 font-semibold">Enrichment &amp; review</h3>
            <p class="mt-2 text-sm text-muted">Built annotation, manual search, specs and pricing, plus review packages with evidence and approve-or-correct before anything is written.</p>
          </li>
          <li class="bg-surface p-6">
            <span class="font-mono text-xs text-muted">M3</span>
            <h3 class="mt-2 font-semibold">Write-back &amp; eBay handoff</h3>
            <p class="mt-2 text-sm text-muted">Added field-by-field writes to the catalog API, manual file uploads and an Excel export in the eBay import format.</p>
          </li>
          <li class="bg-surface p-6">
            <span class="font-mono text-xs text-muted">M4</span>
            <h3 class="mt-2 font-semibold">Settings sheet</h3>
            <p class="mt-2 text-sm text-muted">Moved every prompt, keyword, threshold and key into Google Sheets, added account switching and an allow-list for chosen items. Estimated at 16 hours.</p>
          </li>
        </ol>
      </div>
    </section>

    <!-- ============ RESULTS ============ -->
    <section id="results" class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
      <div class="max-w-3xl">
        <p class="eyebrow text-accent">Results</p>
        <h2 class="font-display mt-3 text-4xl font-extrabold tracking-tight sm:text-5xl">What changed for the team</h2>
        <p class="mt-5 text-muted">Instead of starting every item from a blank form, reviewers now check a finished draft with the evidence attached.</p>
      </div>

      <div class="mt-12 overflow-x-auto rounded-xl border border-line bg-surface">
        <table class="w-full min-w-[640px] border-collapse text-sm">
          <thead>
            <tr class="border-b border-line text-left">
              <th scope="col" class="eyebrow w-1/4 px-5 py-3 font-normal text-muted">Task</th>
              <th scope="col" class="eyebrow w-1/3 px-5 py-3 font-normal text-muted">Before</th>
              <th scope="col" class="eyebrow px-5 py-3 font-normal text-accent">With Quick Annotation</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-line">
            <tr><th scope="row" class="px-5 py-4 text-left font-semibold">Identify brand &amp; model</th><td class="px-5 py-4 text-muted">Zooming into photos</td><td class="px-5 py-4">The vision model reads nameplates, quotes the text it saw and gives a confidence score</td></tr>
            <tr><th scope="row" class="px-5 py-4 text-left font-semibold">Find the manual</th><td class="px-5 py-4 text-muted">Searching Google by hand</td><td class="px-5 py-4">Filtered search results, and the file is checked (PDF, at least 10 pages) before it's attached</td></tr>
            <tr><th scope="row" class="px-5 py-4 text-left font-semibold">Set a price</th><td class="px-5 py-4 text-muted">Guessing from a few listings</td><td class="px-5 py-4">New, refurbished and used ranges based on filtered euro market offers</td></tr>
            <tr><th scope="row" class="px-5 py-4 text-left font-semibold">Descriptions &amp; specs</th><td class="px-5 py-4 text-muted">Typed item by item</td><td class="px-5 py-4">Drafted from the catalog entry, photos and manual</td></tr>
            <tr><th scope="row" class="px-5 py-4 text-left font-semibold">Update the catalog</th><td class="px-5 py-4 text-muted">Copy and paste</td><td class="px-5 py-4">One click per approved field, sent through the API</td></tr>
            <tr><th scope="row" class="px-5 py-4 text-left font-semibold">Change the rules</th><td class="px-5 py-4 text-muted">Ask a developer</td><td class="px-5 py-4">Edit a cell in the settings sheet</td></tr>
            <tr><th scope="row" class="px-5 py-4 text-left font-semibold">Audit trail</th><td class="px-5 py-4 text-muted">None</td><td class="px-5 py-4">Evidence, decisions and errors saved for every item and run</td></tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- ============ SERVICES ============ -->
    <section id="services" class="bg-ink text-ground">
      <div class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
        <div class="grid gap-12 lg:grid-cols-12">
          <div class="lg:col-span-5">
            <p class="eyebrow text-signal">For your business</p>
            <h2 class="font-display mt-3 text-4xl font-extrabold tracking-tight sm:text-5xl">Does your team have a process like this?</h2>
            <p class="mt-5 max-w-prose opacity-75">The same approach works for much more than equipment catalogs: collect the evidence, let AI write a draft, have a person approve it, then write it back safely. If your team researches, copies or re-types information all day, it can probably be automated.</p>
          </div>

          <div class="lg:col-span-7">
            <ul class="grid gap-x-8 gap-y-8 sm:grid-cols-2">
              <li class="border-t border-ground/20 pt-4">
                <h3 class="font-semibold">Product &amp; catalog enrichment</h3>
                <p class="mt-1.5 text-sm opacity-70">Turn photos, SKUs or supplier feeds into complete, consistent product records and marketplace listings.</p>
              </li>
              <li class="border-t border-ground/20 pt-4">
                <h3 class="font-semibold">Document intelligence</h3>
                <p class="mt-1.5 text-sm opacity-70">Extract structured data from invoices, contracts, datasheets and forms, with confidence scores and a person checking the result.</p>
              </li>
              <li class="border-t border-ground/20 pt-4">
                <h3 class="font-semibold">Research agents</h3>
                <p class="mt-1.5 text-sm opacity-70">Research leads, suppliers or competitor prices with agents that search, filter and cite their sources.</p>
              </li>
              <li class="border-t border-ground/20 pt-4">
                <h3 class="font-semibold">Approval workflows</h3>
                <p class="mt-1.5 text-sm opacity-70">Review queues, field-by-field approvals and audit trails, so AI can safely work with live systems.</p>
              </li>
              <li class="border-t border-ground/20 pt-4">
                <h3 class="font-semibold">n8n &amp; API integrations</h3>
                <p class="mt-1.5 text-sm opacity-70">Connect your CRM, ERP, online store and spreadsheets with workflows your ops team can manage.</p>
              </li>
              <li class="border-t border-ground/20 pt-4">
                <h3 class="font-semibold">LLM features in your app</h3>
                <p class="mt-1.5 text-sm opacity-70">Add AI features to the web apps you already run, with prompts and rules your team can change without a developer.</p>
              </li>
            </ul>

            <div class="mt-12">
              <p class="eyebrow opacity-60">How I work</p>
              <ol class="mt-4 grid gap-6 sm:grid-cols-4">
                <li><span class="font-mono text-xs text-signal">1 · Map</span><p class="mt-1 text-sm opacity-80">Break the manual process into clear steps, each with defined inputs and outputs.</p></li>
                <li><span class="font-mono text-xs text-signal">2 · Pilot</span><p class="mt-1 text-sm opacity-80">Test on real records. This project started with 20.</p></li>
                <li><span class="font-mono text-xs text-signal">3 · Harden</span><p class="mt-1 text-sm opacity-80">Add logging, replay, safeguards and approval steps.</p></li>
                <li><span class="font-mono text-xs text-signal">4 · Hand over</span><p class="mt-1 text-sm opacity-80">Deliver settings your team can edit, a runbook and training.</p></li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

  <footer class="border-t border-line">
    <div class="mx-auto flex max-w-6xl flex-col gap-3 px-4 py-8 text-xs text-muted sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
      <p>&copy; <span id="year">2026</span> Shakil Anwar · Automation Expert</p>
      <p>Client details are anonymized. Example records and prices on this page are illustrative.</p>
    </div>
  </footer>

  <script>
    document.getElementById('year').textContent = new Date().getFullYear();

    /* ---------- Theme toggle ---------- */
    const root = document.documentElement;
    const mq = window.matchMedia('(prefers-color-scheme: dark)');
    const currentTheme = () => root.dataset.theme || (mq.matches ? 'dark' : 'light');
    document.getElementById('themeToggle').addEventListener('click', () => {
      const next = currentTheme() === 'dark' ? 'light' : 'dark';
      root.dataset.theme = next;
      try { localStorage.setItem('qa-theme', next); } catch (e) {}
    });

    const esc = (s) => String(s).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));

    /* ---------- Hero record: before / after ---------- */
    const RECORD = [
      { label: 'Brand', before: '—', after: 'GEA', src: 'Vision' },
      { label: 'Model', before: '—', after: 'Ariete NS2006H', src: 'Vision + manual' },
      { label: 'Year', before: '—', after: '2016', src: 'Vision' },
      { label: 'Condition', before: '—', after: 'Used', src: 'Vision' },
      { label: 'Manual', before: 'None attached', after: 'Operating manual · PDF', src: 'Search' },
      { label: 'Description', before: 'Homogenizer (Brand: GEA) with Feed Pump', after: 'High-pressure homogenizer with feed pump for pilot-scale food, dairy and bioprocess work', src: 'LLM' },
      { label: 'Price', before: '—', after: '€10,500 used · €8.9k–12.4k', src: 'Market' },
    ];
    const recordEl = document.getElementById('record');
    const recordState = document.getElementById('recordState');
    function renderRecord(view) {
      recordEl.innerHTML = RECORD.map((f) => {
        const empty = view === 'before' && (f.before === '—' || f.before === 'None attached');
        const value = view === 'before' ? f.before : f.after;
        const changed = view === 'after';
        return `<div class="field-row ${changed ? 'is-changed' : ''} grid grid-cols-[6.5rem_1fr] gap-3 px-4 py-2.5">
          <dt class="text-muted">${f.label}</dt>
          <dd class="flex min-w-0 flex-wrap items-baseline justify-between gap-x-3 gap-y-1">
            <span class="${empty ? 'text-muted' : 'font-medium'}">${esc(value)}</span>
            ${changed ? `<span class="font-mono text-[10px] uppercase tracking-wider text-accent">${f.src}</span>` : ''}
          </dd>
        </div>`;
      }).join('');
      document.querySelectorAll('[data-view]').forEach((b) => {
        const on = b.dataset.view === view;
        b.setAttribute('aria-pressed', on);
        b.className = 'rounded px-3 py-1.5 ' + (on ? 'bg-surface text-ink shadow-sm' : 'text-muted hover:text-ink');
      });
      recordState.innerHTML = view === 'after'
        ? '<span class="h-1.5 w-1.5 rounded-full bg-signal"></span>Pending review'
        : '<span class="h-1.5 w-1.5 rounded-full bg-muted"></span>Incomplete';
      recordState.className = 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 font-semibold ' +
        (view === 'after' ? 'bg-signal/20 text-warn' : 'bg-sunken text-muted');
    }
    document.querySelectorAll('[data-view]').forEach((b) => b.addEventListener('click', () => renderRecord(b.dataset.view)));
    renderRecord('after');

    /* ---------- Review demo ---------- */
    const REVIEW = [
      { key: 'brand', label: 'Brand', value: 'GEA', evidence: 'Nameplate text "GEA" · confidence 0.94', push: 'update-equipment { brand: "GEA" }' },
      { key: 'model', label: 'Model', value: 'Ariete NS2006H', evidence: 'Dataplate "TYPE Ariete NS2006H" · matches manual title', push: 'update-equipment { model: "Ariete NS2006H" }' },
      { key: 'description', label: 'Description', value: 'High-pressure homogenizer with feed pump for pilot-scale work', evidence: 'Drafted from catalog entry + manual', push: 'update-equipment { description: "…" }' },
      { key: 'specifications', label: 'Specifications', value: 'Two-stage homogenizing valve · feed pump · 400 V / 50 Hz', evidence: 'Extracted from operating manual', push: 'specifications approved · recorded only, not pushed by design' },
      { key: 'price', label: 'Price', value: '€10,500 · used', evidence: '5 of 7 offers kept after outlier removal', push: 'update-equipment { asking_price: 10500, price_currency: "EUR" }' },
      { key: 'manual', label: 'Manual', value: 'Operating manual (PDF)', evidence: 'Manufacturer support site · match 1.00', push: 'download → check PDF ≥ 10 pages ✓ → upload-files' },
    ];
    const reviewState = {};
    const fieldsEl = document.getElementById('reviewFields');
    const logEl = document.getElementById('reviewLog');

    function overall() {
      const vals = REVIEW.map((f) => reviewState[f.key]);
      if (vals.every((v) => v === 'approved')) return 'approved';
      if (vals.some((v) => v === 'approved')) return 'partial';
      return 'pending';
    }
    function renderTrack() {
      const s = overall();
      document.querySelectorAll('#stateTrack [data-state]').forEach((li) => {
        const on = li.dataset.state === s;
        li.className = 'rounded-full border px-3 py-1 ' + (on
          ? (s === 'approved' ? 'border-ok bg-ok text-surface' : 'border-signal bg-signal text-signal-ink')
          : 'border-line text-muted');
      });
    }
    function renderReview() {
      fieldsEl.innerHTML = REVIEW.map((f) => {
        const st = reviewState[f.key];
        const badge = st === 'approved'
          ? '<span class="rounded-full bg-ok/15 px-2 py-0.5 text-[11px] font-semibold text-ok">Approved</span>'
          : st === 'rejected'
            ? '<span class="rounded-full bg-bad/10 px-2 py-0.5 text-[11px] font-semibold text-bad">Rejected</span>'
            : '<span class="rounded-full bg-sunken px-2 py-0.5 text-[11px] font-semibold text-muted">Pending</span>';
        return `<li class="grid gap-3 px-4 py-3.5 sm:grid-cols-[1fr_auto] sm:items-center">
          <div class="min-w-0">
            <div class="flex items-center gap-2"><span class="font-mono text-[11px] uppercase tracking-wider text-muted">${f.label}</span>${badge}</div>
            <p class="mt-0.5 text-sm font-medium">${esc(f.value)}</p>
            <p class="text-xs text-muted">${esc(f.evidence)}</p>
          </div>
          <div class="flex gap-2">
            <button type="button" data-act="approved" data-key="${f.key}" ${st === 'approved' ? 'disabled' : ''}
              class="rounded-md border px-3 py-1.5 text-xs font-semibold ${st === 'approved' ? 'border-ok/40 text-ok opacity-60' : 'border-line hover:border-ok hover:text-ok'}">Approve</button>
            <button type="button" data-act="rejected" data-key="${f.key}" ${st ? 'disabled' : ''}
              class="rounded-md border px-3 py-1.5 text-xs font-semibold ${st ? 'border-line text-muted opacity-50' : 'border-line hover:border-bad hover:text-bad'}">Reject</button>
          </div>
        </li>`;
      }).join('');
      renderTrack();
    }
    function log(line, cls) {
      const first = logEl.querySelector('.text-muted:only-child');
      if (first) first.remove();
      const li = document.createElement('li');
      li.className = cls || '';
      li.textContent = line;
      logEl.prepend(li);
    }
    fieldsEl.addEventListener('click', (e) => {
      const btn = e.target.closest('button[data-act]');
      if (!btn) return;
      const f = REVIEW.find((x) => x.key === btn.dataset.key);
      const act = btn.dataset.act;
      reviewState[f.key] = act;
      if (act === 'approved') {
        log(`POST /approval-decision { field: "${f.key}", status: "approved" } → ${f.push}`, 'text-ok');
      } else {
        log(`${f.key} rejected · stays in queue, nothing sent · item can be re-annotated`, 'text-bad');
      }
      renderReview();
      if (overall() === 'approved') log('All 6 fields approved · record status = approved', 'font-semibold');
    });
    document.getElementById('reviewReset').addEventListener('click', () => {
      Object.keys(reviewState).forEach((k) => delete reviewState[k]);
      logEl.innerHTML = '<li class="text-muted">Nothing sent yet. Proposals stay in the review table until someone approves them.</li>';
      renderReview();
    });
    renderReview();

    /* ---------- Config sheet ---------- */
    const SHEET = {
      Workflow: [
        ['tenant_name', 'Tenant A'],
        ['tenant_catalog_token', '••••••••••••'],
        ['tenant_active', 'TRUE'],
        ['openai_api_key', '••••••••••••'],
        ['serper_api_key', '••••••••••••'],
      ],
      Annotation: [
        ['model', 'gpt-4o'],
        ['temperature', '0.1'],
        ['max_images', '8'],
        ['image_detail', 'high'],
        ['system_prompt', 'You are an expert visual identifier of any physical asset…'],
      ],
      Manual: [
        ['serp_query_suffix', 'user manual'],
        ['serp_num', '20'],
        ['token_match_threshold', '0.5'],
        ['manual_keywords', 'manual · datasheet · operator · handbook · …'],
        ['marketplace_exclusion_hosts', 'ebay · amazon · aliexpress · labx · mascus · …'],
      ],
      Specification: [
        ['model', 'gpt-4o-mini'],
        ['temperature', '0.2'],
        ['max_tokens', '8192'],
        ['system_prompt', 'You perform THREE related tasks in ONE JSON response…'],
      ],
      Pricing: [
        ['serp_gl / serp_hl', 'at / de'],
        ['band_new_low / high', '0.8 / 1.1'],
        ['band_refurb_low / high', '0.3 / 0.8'],
        ['band_used_low / high', '0.1 / 0.3'],
        ['outlier_method', 'iqr × 1.5 · min 4 samples'],
      ],
      Batch: [
        ['default_limit', '100'],
        ['default_offset', '0'],
        ['safety_cutoff_offset', '100000'],
        ['selected_items', 'QR-001, QR-014, INV-2231'],
      ],
    };
    const tabsEl = document.getElementById('sheetTabs');
    const bodyEl = document.getElementById('sheetBody');
    let activeTab = 'Pricing';
    function renderSheet() {
      tabsEl.innerHTML = Object.keys(SHEET).map((name) => {
        const on = name === activeTab;
        return `<button type="button" role="tab" aria-selected="${on}" data-tab="${name}"
          class="shrink-0 rounded-t-md px-3 py-1.5 text-xs font-semibold ${on ? 'bg-ground text-ok shadow-[inset_0_2px_0_rgb(var(--ok))]' : 'text-muted hover:text-ink'}">${name}</button>`;
      }).join('');
      bodyEl.innerHTML = SHEET[activeTab].map((r, i) => `<tr>
          <td class="border-b border-r border-line bg-sunken py-2 text-center text-muted tnum">${i + 2}</td>
          <td class="border-b border-r border-line px-3 py-2">${esc(r[0])}</td>
          <td class="border-b border-line px-3 py-2 text-accent">${esc(r[1])}</td>
        </tr>`).join('');
    }
    tabsEl.addEventListener('click', (e) => {
      const b = e.target.closest('[data-tab]');
      if (!b) return;
      activeTab = b.dataset.tab;
      renderSheet();
    });
    renderSheet();
  </script>
</body>
</html>
@endverbatim
