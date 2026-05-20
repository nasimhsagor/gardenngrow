<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'GardenNGrow') | গার্ডেন এন গ্রো</title>
    @php $favicon = \App\Models\Setting::get('site_favicon'); @endphp
    <link rel="icon" href="{{ $favicon ? asset('storage/' . $favicon) : asset('images/favicon.ico') }}">
    <meta name="description" content="@yield('meta_description', __('general.meta_description_default'))">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title', 'GardenNGrow')">
    <meta property="og:description" content="@yield('og_description', __('general.og_description_default'))">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <link rel="alternate" hreflang="bn" href="{{ url()->current() }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            background-color: #F8FAF5;
            font-family: 'Inter', sans-serif;
            color: #1B1B1B;
        }

        h1,
        h2,
        h3 {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body class="antialiased">

    <x-navbar />


    <x-flash-messages />

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>


    <x-whatsapp-button />
    <x-footer />
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/cart/count', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    document.querySelectorAll('.cart-count').forEach(el => el.textContent = data.count ?? 0);
                }).catch(() => { });
        });
    </script>
</body>

</html>
