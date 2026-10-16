<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
</script>

@hasSection('hero_image')
    <link rel="preload" as="image" href="@yield('hero_image')" fetchpriority="high">
@endif

<!-- Flash messages to toast -->
@if (session('success') || session('error') || session('warning') || session('info') || session('session_expired'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Small delay to ensure Alpine is ready
            setTimeout(() => {
                @if (session('success'))
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'success',
                            title: 'Success',
                            message: '{{ session('success') }}'
                        }
                    }));
                @endif

                @if (session('error'))
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'error',
                            title: 'Error',
                            message: '{{ session('error') }}'
                        }
                    }));
                @endif

                @if (session('warning'))
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'warning',
                            title: 'Warning',
                            message: '{{ session('warning') }}'
                        }
                    }));
                @endif

                @if (session('info'))
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'info',
                            title: 'Info',
                            message: '{{ session('info') }}'
                        }
                    }));
                @endif

                @if (session('session_expired'))
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'info',
                            title: 'Info',
                            message: '{{ session('session_expired') }}'
                        }
                    }));
                @endif
            }, 100);
        });
    </script>
@endif


@if (Session::has('site_success'))
    <script>
        jQuery(document).ready(function($) {
            $("#ModalMessage").modal('show');
        });
    </script>
@elseif(Session::has('site_errors'))
    <script>
        jQuery(document).ready(function($) {
            $("#ModalErrors").modal('show');
        });
    </script>
@elseif(Session::has('notification_success'))
    <script>
        $(document).ready(function() {
            $('#notificationModal').modal('show');
        });
    </script>
@endif


<script>
    function dismissModal() {
        if ($('#ModalMessage').hasClass('show')) {
            $('#ModalMessage').removeClass('show');
            $('#ModalMessage').modal('hide');
        }
    }
</script>
<script>
    function dismissErrorModal() {
        if ($('#ModalErrors').hasClass('show')) {
            $('#ModalErrors').removeClass('show');
            $('#ModalErrors').modal('hide');
        }
    }
</script>


