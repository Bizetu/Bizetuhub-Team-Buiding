<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts._partials._header')
</head>
<body class="antialiased bg-gray-50">
    <!-- Top Bar -->
       @include('layouts._partials.navigation')
    
   <main>
        @yield('content')
        <x-toast />
        <x-contact-support-button />
        <x-learn-navigate-button />
        <x-back-to-top />
        <x-user-way />
        <x-profile-completion-modal />
    </main>

    @include('includes.modals.messages')
    @include('includes.modals.errors')
    
    @include('layouts._partials.footer')
    
    @stack('scripts')

    @include('layouts._partials._scripts')

</body>
</html>