@extends('layouts.app')

@section('title', __('general.about_us') . ' - ' . config('app.name'))
@section('meta_description', 'Learn about GardenNGrow — Bangladesh\'s premier online plant nursery. Our story, mission, and the team behind your green paradise.')

@section('content')
<div class="bg-gray-50 min-h-screen">

    <!-- Hero -->
    <div class="bg-primary-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('general.about_us') }}</h1>
            <p class="text-primary-200 text-lg max-w-2xl mx-auto">
                Bringing Bangladesh closer to nature — one plant at a time.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-16">

        <!-- Dynamic Content -->
        <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed">
            {!! $page->content !!}
        </div>

    </div>
</div>
@endsection
