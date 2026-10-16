@auth

<div>
    <button
        x-data
        @click="$dispatch('open-modal', 'learn-navigate-video')"
        @contextmenu.prevent="$dispatch('open-learn-context-menu', { x: $event.clientX, y: $event.clientY })"
        class="!fixed cursor-pointer bottom-3 start-28 p-2 rounded-xl z-40 transition-all duration-300 learn-navigate"
        title="Learn to Navigate This Site"
        id="learn-navigate-btn">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
            <path d="M8 5.14v14l11-7-11-7z"/>
        </svg>
    </button>

    {{-- Custom context menu --}}
    <div id="learn-navigate-context-menu">
        <button id="learn-navigate-hide-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
            </svg>
            Hide button
            <span class="learn-context-menu-shortcut">Ctrl+L</span>
        </button>
    </div>

    <x-modal name="learn-navigate-video" max-width="4xl" :closeable="true">
        <div class="p-6">
            <h2 class="text-2xl font-bold mb-4">Learn to Navigate This Site</h2>
            <div class="relative w-full bg-black rounded-lg overflow-hidden" style="padding-bottom: 56.25%;">
                @if(learnNavigateVideo())
                    <video
                        controls
                        class="absolute top-0 left-0 w-full h-full"
                        poster="{{ asset('images/defaults/banner.webp') }}"
                        preload="metadata">
                        <source src="{{ learnNavigateVideo() }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <iframe class="absolute top-0 left-0 w-full h-full rounded-lg" src="https://www.youtube-nocookie.com/embed/k2uXbUnozLM?si=MPi548U1CYZ7C_Y5" title="Learn to navigate this site" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                @endif
            </div>

            <div class="mt-4 text-lg text-center">
                <p>Watch this quick tutorial to learn how to navigate and make the most of our platform.</p>
            </div>
        </div>
    </x-modal>
</div>

<style>
    .learn-navigate {
        color: #FFFFFF;
        background: #1F3B67;
        border: none;
        font-weight: 700;
        box-shadow: 1px 2px 33px -4px rgba(0,0,0,0.91);
        -webkit-box-shadow: 1px 2px 33px -4px rgba(0,0,0,0.91);
        -moz-box-shadow: 1px 2px 33px -4px rgba(0,0,0,0.91);
        transition: all 0.3s ease-in-out;
    }

    .learn-navigate:hover {
        background: #FFB901;
        transform: translateY(-2px);
    }

    #learn-navigate-context-menu {
        display: none;
        position: fixed;
        z-index: 9999;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        padding: 4px;
        min-width: 180px;
        animation: learnContextFadeIn 0.12s ease-out;
    }

    @keyframes learnContextFadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to   { opacity: 1; transform: scale(1); }
    }

    #learn-navigate-context-menu button {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        padding: 8px 12px;
        background: none;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        color: #374151;
        cursor: pointer;
        text-align: left;
        transition: background 0.15s;
    }

    #learn-navigate-context-menu button:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .learn-context-menu-shortcut {
        margin-left: auto;
        font-size: 11px;
        color: #9ca3af;
        background: #f3f4f6;
        padding: 1px 5px;
        border-radius: 4px;
        font-family: monospace;
    }
</style>

<script>
    document.addEventListener('alpine:initialized', () => {

        // Stop iframe on modal close
        window.addEventListener('close-modal', (event) => {
            if (event.detail === 'learn-navigate-video') {
                const iframe = document.querySelector('#learn-navigate-video iframe');
                if (iframe) {
                    iframe.src = iframe.src;
                }
            }
        });

        // Context menu + Ctrl+L hide logic
        (function () {
            const btn         = document.getElementById('learn-navigate-btn');
            const contextMenu = document.getElementById('learn-navigate-context-menu');

            function hideButton() {
                btn.style.opacity       = '0';
                btn.style.transform     = 'scale(0.8)';
                btn.style.pointerEvents = 'none';
                setTimeout(() => btn.style.display = 'none', 300);
            }

            function closeContextMenu() {
                contextMenu.style.display = 'none';
            }

            // Right-click — Alpine already dispatches the event, but we also
            // need to position & show the menu here via native listener.
            btn.addEventListener('contextmenu', function (e) {
                e.preventDefault();

                const menuWidth  = 180;
                const menuHeight = 44;
                const x = Math.min(e.clientX, window.innerWidth  - menuWidth  - 8);
                const y = Math.min(e.clientY, window.innerHeight - menuHeight - 8);

                contextMenu.style.left    = x + 'px';
                contextMenu.style.top     = y + 'px';
                contextMenu.style.display = 'block';
            });

            // Hide via context menu option
            document.getElementById('learn-navigate-hide-btn').addEventListener('click', function () {
                closeContextMenu();
                hideButton();
            });

            // Close menu on outside click
            document.addEventListener('click', function (e) {
                if (!contextMenu.contains(e.target)) {
                    closeContextMenu();
                }
            });

            // Close on scroll or Escape; hide on Ctrl+L
            document.addEventListener('scroll', closeContextMenu);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeContextMenu();
                }

                // Ctrl+L (or Cmd+L on Mac) to hide
                if ((e.ctrlKey || e.metaKey) && e.key === 'l') {
                    e.preventDefault();
                    closeContextMenu();
                    hideButton();
                }
            });
        })();

    });
</script>

@endauth
