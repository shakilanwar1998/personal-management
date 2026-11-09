@php($home = rtrim(config('app.url'), '/') . '/')
@php($ogImage = rtrim(config('app.url'), '/') . '/og/shakil-anwar.png')
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />

    <title>Shakil Anwar · Software Engineer, Business Automation</title>
    <meta name="description" content="Shakil Anwar builds business automation and AI workflows: n8n pipelines, LLM agents with human review, and eBay, Shopify and marketplace integrations on Laravel, Node.js and Python." />
    <link rel="canonical" href="{{ request()->is('projects') || request()->is('case-studies/*') ? url()->current() : $home }}" />
    <meta name="theme-color" id="theme-color" content="#090b0e" />

    <meta property="og:type" content="profile" />
    <meta property="og:title" content="Shakil Anwar · Software Engineer, Business Automation" />
    <meta property="og:description" content="I turn manual business operations into workflows that run themselves. AI where it helps, a human where it matters." />
    <meta property="og:url" content="{{ $home }}" />
    <meta property="og:site_name" content="Shakil Anwar" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:image" content="{{ $ogImage }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="Shakil Anwar, Software Engineer, Business Automation" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:image" content="{{ $ogImage }}" />

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "Shakil Anwar",
        "jobTitle": "Software Engineer",
        "url": @json($home),
        "image": @json($ogImage),
        "description": "Software engineer focused on business automation: n8n pipelines, LLM agents with human review, and eBay, Shopify and marketplace integrations.",
        "email": "mailto:shakilanwar1998@gmail.com",
        "address": { "@type": "PostalAddress", "addressLocality": "Dinajpur", "addressCountry": "BD" },
        "worksFor": [{ "@type": "Organization", "name": "Dropmatix Systema SL" }, { "@type": "Organization", "name": "Okkhor", "url": "https://okkhor.com" }],
        "alumniOf": "Daffodil International University",
        "knowsAbout": ["Business automation", "n8n", "AI agents", "LLM integration", "Human-in-the-loop AI", "eBay API", "Shopify", "Laravel", "Node.js", "Python"],
        "sameAs": ["https://github.com/shakilanwar1998", "https://linkedin.com/in/shakilanwar1998", "https://facebook.com/shakilanwar1998", "https://www.upwork.com/freelancers/~01a0defc0f412196fa", "https://www.fiverr.com/shakilanwar/"]
    }
    </script>

    {{-- Apply the saved or system theme before first paint. --}}
    <script>
        (function () {
            var t = null;
            try { t = localStorage.getItem('sa-theme'); } catch (e) {}
            if (t !== 'light' && t !== 'dark') {
                t = window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
            }
            document.documentElement.setAttribute('data-theme', t);
            var m = document.getElementById('theme-color');
            if (m) m.setAttribute('content', t === 'light' ? '#f4f3ee' : '#090b0e');
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/portfolio/main.js'])
</head>
<body>
    <div id="portfolio">
        <noscript>
            <main style="max-width:640px;margin:48px auto;padding:0 16px;font-family:system-ui,sans-serif;line-height:1.6">
                <h1>Shakil Anwar</h1>
                <p><strong>Software Engineer · Business Automation.</strong> I turn manual business operations into workflows that run themselves: n8n pipelines, LLM agents with human review, and eBay, Shopify and marketplace integrations.</p>
                <p>Sr. Software Engineer at Dropmatix · CTO at <a href="https://okkhor.com">Okkhor</a> · Dinajpur, Bangladesh (GMT+6)</p>
                <p><a href="/projects">Projects</a> · <a href="/case-studies/quick-annotation">Case study: Quick Annotation, AI catalog enrichment</a></p>
                <p>Email: <a href="mailto:shakilanwar1998@gmail.com">shakilanwar1998@gmail.com</a> · <a href="https://github.com/shakilanwar1998">GitHub</a> · <a href="https://linkedin.com/in/shakilanwar1998">LinkedIn</a> · <a href="https://www.upwork.com/freelancers/~01a0defc0f412196fa">Upwork (Top Rated, 100% Job Success)</a> · <a href="https://www.fiverr.com/shakilanwar/">Fiverr</a></p>
            </main>
        </noscript>
    </div>
</body>
</html>
