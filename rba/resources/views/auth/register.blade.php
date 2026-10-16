{{-- @extends('layouts.auth')

@section('title', 'Register - RBA')

@section('content')
    <div class="min-h-screen flex">
        <!-- Left Side - Image -->
        <div class="hidden lg:block lg:w-1/2 relative overflow-hidden">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            
            <!-- Content Overlay -->
            <div class="absolute inset-0 flex flex-col justify-between p-12 text-white">
                <!-- Main Content -->
                <div class="py-8">
                    <h3 class="text-5xl font-black mb-4 leading-tight">
                        Start Your Journey Today
                    </h3>
                    <p class="text-xl text-gray-200 max-w-md mb-8">
                        Join a community of learners and unlock your full potential.
                    </p>
                    
                    <!-- Quick Stats -->
                    <div class="flex gap-8">
                        <div>
                            <div class="text-3xl font-black mb-1">50K+</div>
                            <div class="text-md text-gray-300">Students</div>
                        </div>
                        <div>
                            <div class="text-3xl font-black mb-1">1,000+</div>
                            <div class="text-md text-gray-300">Courses</div>
                        </div>
                        <div>
                            <div class="text-3xl font-black mb-1">95%</div>
                            <div class="text-md text-gray-300">Success</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white overflow-y-auto">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8">
                    <h2 class="text-3xl font-black">RBA</h2>
                </div>

                <div class="mb-12">
                    <h1 class="text-5xl font-black mb-4">Become a Member</h1>
                    <p class="text-gray-600">Create your RBA account to unlock exclusive benefits</p>
                </div>

                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <p class="text-red-700 text-md">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name') }}"
                            required
                            class="w-full px-0 py-4 border-0 border-b-2 border-gray-300 focus:border-black focus:ring-0 text-lg"
                            placeholder="Full Name"
                        >
                        @error('name')
                            <p class="mt-2 text-md text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}"
                            required
                            class="w-full px-0 py-4 border-0 border-b-2 border-gray-300 focus:border-black focus:ring-0 text-lg"
                            placeholder="Email address"
                        >
                        @error('email')
                            <p class="mt-2 text-md text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required
                            class="w-full px-0 py-4 border-0 border-b-2 border-gray-300 focus:border-black focus:ring-0 text-lg"
                            placeholder="Password"
                        >
                        @error('password')
                            <p class="mt-2 text-md text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Minimum 8 characters</p>
                    </div>
                    
                    <div>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            required
                            class="w-full px-0 py-4 border-0 border-b-2 border-gray-300 focus:border-black focus:ring-0 text-lg"
                            placeholder="Confirm Password"
                        >
                    </div>
                    
                    <div class="space-y-4 text-md text-gray-600">
                        <label class="flex items-start">
                            <input 
                                type="checkbox" name="terms" id="terms" required
                                class="w-5 h-5 mt-0.5 border-2 border-gray-300 rounded focus:ring-0 focus:ring-offset-0 text-black flex-shrink-0"
                            >
                            <span class="ml-3">
                                I agree to RBA's <a href="/privacy-policy" class="underline font-semibold text-black hover:text-gray-600">Privacy Policy</a> and <a href="/terms-conditions" class="underline font-semibold text-black hover:text-gray-600">Terms of Use</a>
                            </span>
                        </label>
                        @error('terms')
                            <p class="text-md text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button 
                        type="submit" 
                        class="w-full bg-black text-white py-4 rounded-full hover:bg-gray-800 transition duration-300 font-semibold text-lg"
                    >
                        Register
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-gray-600 mb-4">Already a member?</p>
                    <a href="{{ route('login.choice') }}" class="font-semibold hover:text-gray-600 underline">Sign In</a>
                </div>
            </div>
        </div>
    </div>
@endsection --}}