@extends('layouts.auth')

@section('title', 'SSO Sign In - RBA')

@section('content')
<div class="mb-12">
    <h1 class="text-5xl font-black mb-4">SSO Sign In</h1>
    <p class="text-gray-600">Use your organization credentials</p>
</div>

@if (session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <p class="text-red-700 text-md">{{ session('error') }}</p>
    </div>
@endif

<!-- SSO Providers -->
<div class="space-y-4 mb-8">
    <button type="button" class="w-full border-2 border-black hover:bg-black hover:text-white transition duration-300 rounded-full py-4 px-6 font-semibold flex items-center justify-center">
        <svg class="w-6 h-6 mr-3" viewBox="0 0 23 23">
            <path fill="currentColor" d="M0 0h10.93v10.93H0z"/>
            <path fill="currentColor" d="M12.07 0H23v10.93H12.07z"/>
            <path fill="currentColor" d="M0 12.07h10.93V23H0z"/>
            <path fill="currentColor" d="M12.07 12.07H23V23H12.07z"/>
        </svg>
        Continue with Microsoft
    </button>
    
    <button type="button" class="w-full border-2 border-black hover:bg-black hover:text-white transition duration-300 rounded-full py-4 px-6 font-semibold flex items-center justify-center">
        <svg class="w-6 h-6 mr-3" viewBox="0 0 24 24">
            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        Continue with Google
    </button>
    
    <button type="button" class="w-full border-2 border-black hover:bg-black hover:text-white transition duration-300 rounded-full py-4 px-6 font-semibold flex items-center justify-center">
        <div class="w-6 h-6 bg-current rounded mr-3 flex items-center justify-center">
            <span class="text-white font-bold text-md">O</span>
        </div>
        Continue with Okta
    </button>
</div>

<div class="relative my-8">
    <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-gray-300"></div>
    </div>
    <div class="relative flex justify-center text-md">
        <span class="px-4 bg-white text-gray-600">Or enter your domain</span>
    </div>
</div>

<!-- Domain Form -->
<form method="POST" action="{{ route('login.sso') }}" class="space-y-6">
    @csrf
    
    <div>
        <input 
            type="text" 
            name="domain" 
            id="domain" 
            value="{{ old('domain') }}"
            required
            class="w-full px-0 py-4 border-0 border-b-2 border-gray-300 focus:border-black focus:ring-0 text-lg"
            placeholder="company.com"
        >
        @error('domain')
            <p class="mt-2 text-md text-red-600">{{ $message }}</p>
        @enderror
    </div>
    
    <button 
        type="submit" 
        class="w-full bg-black text-white py-4 rounded-full hover:bg-gray-800 transition duration-300 font-semibold text-lg"
    >
        Continue
    </button>
</form>

<div class="mt-8 text-center">
    <p class="text-gray-600 mb-4">Having trouble?</p>
    <a href="{{ route('login') }}" class="font-semibold hover:text-gray-600 underline">Sign in with email</a>
</div>

<div class="mt-6 text-center">
    <a href="{{ route('login.choice') }}" class="text-md text-gray-600 hover:text-black">← Back to login options</a>
</div>
@endsection