@props(['blogs'])

@if($blogs->isNotEmpty())
<section class="py-16 max-w-7xl mx-auto px-4">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-gray-800">{{ __('general.plant_care_tips') }}</h2>
        <p class="text-gray-500 mt-2">{{ __('general.learn_to_grow_nurture') }}</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($blogs as $blog)
        <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition group">
            @if($blog->featured_image)
                <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}"
                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-48 bg-gradient-to-br from-[#95D5B2] to-[#52B788] flex items-center justify-center text-5xl">🌿</div>
            @endif
            <div class="p-5">
                <span class="text-xs text-[#52B788] font-medium">{{ $blog->category?->name }}</span>
                <h3 class="font-bold text-gray-800 mt-1 group-hover:text-[#2D6A4F] transition">{{ $blog->title }}</h3>
                <p class="text-gray-500 text-sm mt-2 line-clamp-2">{{ $blog->excerpt }}</p>
                <a href="{{ route('blog.show', $blog->slug) }}"
                    class="inline-block mt-4 text-[#2D6A4F] text-sm font-medium hover:text-[#52B788] transition">
                    {{ __('general.read_more') }} →
                </a>
            </div>
        </article>
        @endforeach
    </div>
</section>
@endif
