<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'GardenNGrow') | গার্ডেন এন গ্রো</title>
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

    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-4">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                @php $logo = \App\Models\Setting::get('site_logo'); @endphp
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}" alt="{{ config('app.name') }}"
                class="h-16 w-auto object-contain"> @else
                        <span class="text-2xl font-bold text-primary-700" style="font-family:'Playfair Display',serif">
                            GardenNGrow
                        </span>
                    @endif
            </a>
            <!-- Search (desktop) -->
            <form action="{{ route('search.index') }}" method="GET" class="hidden md:flex flex-1 max-w-md">
                <div
                    class="flex w-full border border-gray-200 rounded-xl overflow-hidden focus-within:border-primary-400 focus-within:ring-1 focus-within:ring-primary-400 transition">
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="{{ __('general.search_placeholder') }}"
                        class="flex-1 px-4 py-2 text-sm outline-none bg-transparent">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Desktop right section -->
            <div class="hidden md:flex items-center gap-5">
                <!-- Nav links -->
                <a href="{{ route('home') }}"
                    class="text-sm font-medium text-gray-700 hover:text-primary-600 transition">
                    {{ __('general.home') }}
                </a>
                <a href="{{ route('shop.index') }}"
                    class="text-sm font-medium text-gray-700 hover:text-primary-600 transition">
                    {{ __('general.shop') }}
                </a>
                <a href="{{ route('blog.index') }}"
                    class="text-sm font-medium text-gray-700 hover:text-primary-600 transition">
                    {{ __('general.blog') }}
                </a>

                <!-- Language switcher -->
                <x-language-switcher />

                <!-- Divider -->
                <span class="w-px h-5 bg-gray-200"></span>

                <!-- Wishlist -->
                <a href="{{ auth()->check() ? route('wishlist.index') : route('login') }}"
                    class="relative text-gray-600 hover:text-primary-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </a>

                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-primary-600 transition"
                    id="cart-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span
                        class="cart-count absolute -top-2 -right-2 bg-primary-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center leading-none">0</span>
                </a>

                <!-- User menu -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-1.5 text-sm text-gray-700 hover:text-primary-600 transition font-medium">
                            <div
                                class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span>{{ auth()->user()->name }}</span>
                            <svg class="w-3 h-3" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-cloak @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                            <a href="{{ route('customer.dashboard') }}"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                {{ __('general.dashboard') }}
                            </a>
                            <a href="{{ route('customer.orders') }}"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                {{ __('general.my_orders') }}
                            </a>
                            <a href="{{ route('customer.profile') }}"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('general.profile') }}
                            </a>
                            <hr class="my-1 border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('general.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
                        {{ __('general.login') }}
                    </a>
                @endauth
            </div>

            <!-- Mobile: cart + hamburger -->
            <div class="flex md:hidden items-center gap-3">
                <a href="{{ route('cart.index') }}" class="relative text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span
                        class="cart-count absolute -top-2 -right-2 bg-primary-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center leading-none">0</span>
                </a>
                <button @click="mobileOpen = !mobileOpen" class="text-gray-700 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileOpen" x-cloak x-collapse class="md:hidden border-t border-gray-100 bg-white">
            <div class="px-4 py-3 space-y-1">
                <!-- Mobile search -->
                <form action="{{ route('search.index') }}" method="GET" class="mb-3">
                    <div class="flex border border-gray-200 rounded-xl overflow-hidden">
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="{{ __('general.search_placeholder') }}"
                            class="flex-1 px-4 py-2 text-sm outline-none">
                        <button type="submit" class="bg-primary-600 text-white px-4 py-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>

                <a href="{{ route('home') }}"
                    class="flex items-center gap-2 px-2 py-2.5 text-gray-700 hover:text-primary-600 text-sm font-medium">{{ __('general.home') }}</a>
                <a href="{{ route('shop.index') }}"
                    class="flex items-center gap-2 px-2 py-2.5 text-gray-700 hover:text-primary-600 text-sm font-medium">{{ __('general.shop') }}</a>
                <a href="{{ route('blog.index') }}"
                    class="flex items-center gap-2 px-2 py-2.5 text-gray-700 hover:text-primary-600 text-sm font-medium">{{ __('general.blog') }}</a>

                <div class="px-2 py-2">
                    <x-language-switcher />
                </div>

                <hr class="border-gray-100 my-1">

                @auth
                    <a href="{{ route('customer.dashboard') }}"
                        class="flex items-center gap-2 px-2 py-2.5 text-gray-700 hover:text-primary-600 text-sm">{{ __('general.my_account') }}</a>
                    <a href="{{ route('customer.orders') }}"
                        class="flex items-center gap-2 px-2 py-2.5 text-gray-700 hover:text-primary-600 text-sm">{{ __('general.my_orders') }}</a>
                    <form method="POST" action="{{ route('logout') }}" class="px-2 py-1">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 font-medium">{{ __('general.logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block mx-2 my-1 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl text-center transition">
                        {{ __('general.login') }}
                    </a>
                    <a href="{{ route('register') }}"
                        class="block mx-2 my-1 border border-primary-600 text-primary-600 text-sm font-medium px-4 py-2.5 rounded-xl text-center hover:bg-primary-50 transition">
                        {{ __('general.register') }}
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    @php
        $flashTypes = [
            'success' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'text' => 'text-green-800', 'btn' => 'text-green-400 hover:text-green-700'],
            'error' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-800', 'btn' => 'text-red-400 hover:text-red-700'],
            'warning' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'text' => 'text-yellow-800', 'btn' => 'text-yellow-400 hover:text-yellow-700'],
            'info' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-800', 'btn' => 'text-blue-400 hover:text-blue-700'],
        ];
    @endphp
    @foreach($flashTypes as $type => $classes)
        @if(session($type))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div
                    class="{{ $classes['bg'] }} {{ $classes['border'] }} {{ $classes['text'] }} border rounded-xl px-4 py-3 flex justify-between items-start gap-3">
                    <span>{{ session($type) }}</span>
                    <button onclick="this.parentElement.parentElement.remove()"
                        class="{{ $classes['btn'] }} text-xl leading-none mt-0.5">&times;</button>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>


    <!-- WhatsApp float -->
    <a href="https://wa.me/{{ config('gardenngrow.whatsapp_number', '8801700000000') }}?text={{ urlencode(__('general.whatsapp_message')) }}"
        target="_blank" rel="noopener"
        class="fixed bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white p-3.5 rounded-full shadow-lg hover:scale-110 transition z-50">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
        </svg>
    </a>
    <x-footer :logo="$logo" />
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