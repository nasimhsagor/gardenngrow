@extends('layouts.app')

@section('title', 'Reset Password - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Reset Password</h1>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', request('email')) }}" required autofocus
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 @error('password') border-red-400 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <button type="submit"
                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl transition">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection
