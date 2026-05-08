@extends('layouts.app')
@section('title', __('My Dashboard') . ' | GardenNGrow')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ __('Welcome back') }}, {{ auth()->user()->name }}! 🌿</h1>
    <p class="text-gray-500 mb-8">{{ __('Here\'s your account overview') }}</p>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm text-center">
            <div class="text-3xl font-bold text-[#2D6A4F]">{{ auth()->user()->orders()->count() }}</div>
            <div class="text-sm text-gray-500 mt-1">{{ __('Total Orders') }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm text-center">
            <div class="text-3xl font-bold text-[#52B788]">৳{{ number_format(auth()->user()->total_spent, 0) }}</div>
            <div class="text-sm text-gray-500 mt-1">{{ __('Total Spent') }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm text-center">
            <div class="text-3xl font-bold text-orange-500">{{ auth()->user()->wishlists()->count() }}</div>
            <div class="text-sm text-gray-500 mt-1">{{ __('Wishlist Items') }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm text-center">
            <div class="text-3xl font-bold text-blue-500">{{ auth()->user()->reviews()->count() }}</div>
            <div class="text-sm text-gray-500 mt-1">{{ __('Reviews') }}</div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['route' => 'customer.orders', 'icon' => '📦', 'label' => __('My Orders')],
            ['route' => 'wishlist.index', 'icon' => '❤️', 'label' => __('Wishlist')],
            ['route' => 'customer.addresses', 'icon' => '📍', 'label' => __('Addresses')],
            ['route' => 'customer.profile', 'icon' => '👤', 'label' => __('Profile')],
        ] as $link)
        <a href="{{ route($link['route']) }}" class="bg-white rounded-2xl p-5 shadow-sm text-center hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="text-3xl mb-2">{{ $link['icon'] }}</div>
            <div class="text-sm font-medium text-gray-700">{{ $link['label'] }}</div>
        </a>
        @endforeach
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-5">
            <h2 class="font-bold text-gray-800 text-lg">{{ __('Recent Orders') }}</h2>
            <a href="{{ route('customer.orders') }}" class="text-[#2D6A4F] text-sm hover:text-[#52B788]">{{ __('View All') }} →</a>
        </div>
        @if($recentOrders->isEmpty())
        <div class="text-center py-10 text-gray-500">
            <div class="text-4xl mb-3">📦</div>
            <p>{{ __('No orders yet.') }} <a href="{{ route('shop.index') }}" class="text-[#2D6A4F]">{{ __('Start shopping!') }}</a></p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-3">{{ __('Order #') }}</th>
                        <th class="pb-3">{{ __('Date') }}</th>
                        <th class="pb-3">{{ __('Status') }}</th>
                        <th class="pb-3">{{ __('Total') }}</th>
                        <th class="pb-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 font-semibold text-[#2D6A4F]">{{ $order->order_number }}</td>
                        <td class="py-3 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="py-3">
                            @php
                                $statusColor = $order->status->color();
                                $badgeClasses = match($statusColor) {
                                    'success' => 'bg-green-100 text-green-700',
                                    'warning' => 'bg-yellow-100 text-yellow-700',
                                    'danger'  => 'bg-red-100 text-red-700',
                                    'info'    => 'bg-blue-100 text-blue-700',
                                    'primary' => 'bg-indigo-100 text-indigo-700',
                                    default   => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $badgeClasses }}">
                                {{ $order->status->label() }}
                            </span>
                        </td>
                        <td class="py-3 font-bold">৳{{ number_format($order->total, 0) }}</td>
                        <td class="py-3 text-right">
                            <a href="{{ route('customer.order.show', $order->order_number) }}" class="text-[#2D6A4F] hover:text-[#52B788]">{{ __('View') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
