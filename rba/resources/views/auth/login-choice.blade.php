@php
    $agent = new \Jenssegers\Agent\Agent();
@endphp
@extends('layouts.app')

@section('title', 'Sign In - RBA')

@section('content')
    <div class="min-h-screen flex">
        <!-- Left Side - Image -->
        <div class="hidden lg:block lg:w-1/2 relative overflow-hidden">
            <img src="{{ asset($logoImage) }}" alt="Students learning"
                class="absolute inset-0 w-full h-full object-cover" />
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>

            <!-- Content Overlay -->
            <div class="absolute inset-0 flex flex-col justify-center items-center p-8 text-white">
                <!-- Bottom Text -->
                <div class="text-spacing">
                    <h3 class="text-7xl text-white py-4">
                        <b>Empowering</b> you with the <b>skills</b> to drive <b>sustainable change</b>.
                    </h3>
                    <p class="text-xl text-[#FFB901]">
                        Advancing a responsible and inclusive financial future.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Options -->
        @if($agent->isDesktop())
        <div class="w-full lg:w-1/2 flex p-8 bg-white">

                <!-- Avartar -->
                <div class="options-center mt-24 ">
                    <span><i class="signin-icon login-graphic mb-4"></i></span>
                    <span><i class="signin-icon signin-avatar"></i></span>

                    <h1 class="mt-2">Sign In</h1>
                    {{-- <p class="mt-3"><small>For single sign on, select your company.</small></p> --}}

                    <div class="form-group row justify-content-center">
                        <!-- Google -->
                        <div class="col-12 flex justify-center">


                            <a href="{{ url('auth/google') }}" class="btn btn-signin-google btn-block mt-4">
                                <i class="signin-button-icon signin-google-icon"></i>
                                <p>Log in with Google </p>
                            </a>

                        </div>

                        <!-- Microsoft -->
                        <div class="col-12 flex justify-center">
                            <a href="{{ url('/auth/microsoft') }}" class="btn btn-signin-google btn-block mt-3">
                                <i class="signin-button-icon signin-microsoft-icon"></i>
                                <p>Log in with Microsoft </p>
                            </a>
                        </div>


                    </div>
                </div>

        </div>

     @else
      <div class="w-full lg:w-1/2 flex pt-6 bg-white">

                <!-- Avartar -->
                <div class="options-center mt-24 ">
                    <span><i class="signin-icon login-graphic"></i></span>
                    <span><i class="signin-icon signin-avatar"></i></span>

                    <h1 class="mt-2">Sign In</h1>
                    {{-- <p class="mt-3"><small>For single sign on, select your company.</small></p> --}}

                    <div class="form-group row justify-content-center">
                        <!-- Google -->
                        <div class="col-12 flex justify-center">


                            <a href="{{ url('auth/google') }}" class="btn btn-signin-google btn-block mt-4">
                                <i class="signin-button-icon signin-google-icon"></i>
                                <p>Log in with Google </p>
                            </a>

                        </div>

                        <!-- Microsoft -->
                        <div class="col-12 flex justify-center">
                            <a href="{{ url('/auth/microsoft') }}" class="btn btn-signin-google btn-block mt-3">
                                <i class="signin-button-icon signin-microsoft-icon"></i>
                                <p>Log in with Microsoft </p>
                            </a>
                        </div>


                    </div>
                </div>

        </div>

     @endif
    </div>
@endsection
