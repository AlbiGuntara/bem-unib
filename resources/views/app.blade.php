<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="icon" type="image/png" href="{{ asset('images/logo/bem.png') }}">

    {{-- Inline script: set theme on <html> as early as possible --}}
    <script>
        (function() {
            try {
                const saved = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (saved === 'dark' || (saved === 'system' && prefersDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>

    {{-- Ziggy routes --}}
    @routes

    {{-- Vite (CSS & JS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Inertia Head (title, meta, dll dari Vue) --}}
    @inertiaHead

    @stack('style')
</head>

<body class="antialiased">
    {{-- Root mount Inertia --}}
    @inertia

    @stack('script')
</body>

</html>
