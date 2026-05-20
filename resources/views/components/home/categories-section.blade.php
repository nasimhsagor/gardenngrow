@props(['categories'])

<section class="py-16 max-w-7xl mx-auto px-4">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-gray-800">{{ __('general.browse_categories') }}</h2>
        <p class="text-gray-500 mt-2">{{ __('general.find_exactly_what_you_need') }}</p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-4">
        @foreach($categories as $category)
        <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
            class="bg-white rounded-2xl p-4 text-center hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            @if($category->image)
                <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}"
                    class="w-16 h-16 object-cover rounded-xl mx-auto mb-3">
            @else
                <div class="w-16 h-16 bg-[#F8FAF5] rounded-xl mx-auto mb-3 flex items-center justify-center text-3xl">
                    {{ $category->icon ?? '🌿' }}
                </div>
            @endif
            <span class="text-sm font-medium text-gray-700 group-hover:text-[#2D6A4F] transition">{{ $category->name }}</span>
            @if(isset($category->products_count))
                <span class="block text-xs text-gray-400">{{ $category->products_count }} {{ __('general.items') }}</span>
            @endif
        </a>
        @endforeach
    </div>
</section>
