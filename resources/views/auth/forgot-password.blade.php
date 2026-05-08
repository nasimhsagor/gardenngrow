@extends('layouts.app')

@section('title', 'Forgot Password - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Forgot Password</h1>
            <p class="text-gray-500 mt-2 text-sm">Enter your email and we'll send you a reset link.</p>
        </div>

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-6">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl transition">
                Send Reset Link
            </button>
        </form>

        <p class="text-center mt-6 text-sm text-gray-500">
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium">Back to Login</a>
        </p>
    </div>
</div>
@endsection
