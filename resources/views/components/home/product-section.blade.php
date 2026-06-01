@props([
    'products',
    'title',
    'subtitle' => null,
    'bgClass'  => 'bg-transparent',
])

<section class="{{ $bgClass }} py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">{{ $title }}</h2>
                @if($subtitle)
                    <p class="text-gray-500 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            <a href="{{ route('shop.index') }}" class="text-[#2D6A4F] font-medium hover:text-[#52B788] transition">
                {{ __('general.view_all') }} →
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-5">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
