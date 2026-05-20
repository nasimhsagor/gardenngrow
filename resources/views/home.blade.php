@extends('layouts.app')

@section('title', config('app.name') . ' - ' . __('general.online_plant_paradise'))
@section('meta_description', __('general.meta_description_default'))

@section('content')

<x-hero-slider :banners="$banners" />

<x-home.features-strip />

<x-home.categories-section :categories="$categories" />

<x-home.product-section
    :products="$featured"
    :title="__('general.featured_plants')"
    :subtitle="__('general.featured_subtitle')"
    bg-class="bg-[#F8FAF5]"
/>

<x-home.product-section
    :products="$newArrivals"
    :title="__('general.new_arrivals')"
    :subtitle="__('general.new_arrivals_subtitle')"
/>

<x-home.reviews-section :reviews="$reviews" />

<x-home.blog-section :blogs="$blogs" />

<x-home.newsletter-section />

@endsection
