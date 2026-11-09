<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex, nofollow" />
    <title inertia>Finance</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%234f46e5'/><text x='16' y='23' font-size='19' text-anchor='middle' fill='white' font-family='Arial'>৳</text></svg>">
    <script>
        // Apply the saved theme before first paint to avoid a flash.
        try {
            const t = localStorage.getItem('theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        } catch (e) {}
    </script>
    @vite(['resources/css/admin.css', 'resources/js/admin/app.js'])
    @inertiaHead
</head>
<body class="h-full">
    @inertia
</body>
</html>
