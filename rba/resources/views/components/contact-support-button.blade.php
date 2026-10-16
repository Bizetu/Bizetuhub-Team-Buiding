<div id="contact-support-wrapper" x-data="{ url: 'https://farwell-consultants.com/farwellticket/farwellticket/index.php?a=add&catid=22' }">
    <button
        x-data
        @click.prevent="window.open(url, '_blank')"
        class="!fixed cursor-pointer bottom-3 start-16 p-2 rounded-xl z-50 transition-all duration-300 contact-support"
        title="Contact Support"
        id="contact-support-btn">
        <svg viewBox="0 0 30 30" fill="white" xmlns="http://www.w3.org/2000/svg" class="size-6">
            <g clip-path="url(#clip0_support)">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.3849 17.7605C10.0069 17.1604 9.68552 16.527 9.4247 15.8685C7.35905 14.7161 7.02913 9.5916 8.30201 9.97978C8.30201 9.78691 8.26754 9.24736 8.26754 8.87627C8.26754 0.558394 21.7448 0.553511 21.7448 8.87627C21.7448 9.25712 21.7054 9.78691 21.7103 9.97978C22.9807 9.58427 22.6508 14.7088 20.5876 15.8685C20.0632 17.3089 19.1276 18.2757 19.2039 18.908C19.2039 21.0784 16.7985 21.8645 15.0185 21.8841C14.856 21.8841 14.691 21.8841 14.5261 21.8597C14.9168 21.7567 15.2913 21.6006 15.6389 21.3958C16.4046 20.9319 16.9216 20.2459 16.9216 19.4231C16.9216 18.6004 16.4071 17.9095 15.6414 17.448C14.9395 17.0395 14.1409 16.823 13.327 16.8206C12.6949 16.8089 12.0665 16.9209 11.478 17.1502C11.1129 17.2971 10.7788 17.5108 10.4932 17.7801H10.4021L10.3849 17.7605ZM24.7263 7.7166H24.6352C23.7907 4.16923 21.6192 1.7083 19.0316 0.69023C17.5168 0.12313 15.8932 -0.100438 14.2799 0.0359329C12.6908 0.158933 11.1448 0.607867 9.73984 1.35429C8.56622 1.99986 7.565 2.91397 6.81945 4.02058C6.07391 5.1272 5.60558 6.39433 5.45343 7.7166H5.28355C4.47107 7.7166 3.64875 8.37822 3.64875 9.18144V14.472C3.64875 15.2776 4.47107 15.9368 5.28355 15.9368H5.76364C6.28806 18.0486 8.4128 19.9822 10.5868 19.9822H11.0127C11.3845 20.5242 12.1059 20.9075 13.3073 20.9075C14.5088 20.9075 15.7891 20.2361 15.7891 19.4109C15.7891 18.5857 14.5261 17.9168 13.3073 17.9168C12.0886 17.9168 11.35 18.3099 10.9906 18.8763H10.5868C8.73041 18.8763 6.89372 16.9231 6.76323 15.1604V7.81181C6.90186 6.70617 7.30078 5.64835 7.92777 4.72381C8.55476 3.79926 9.39225 3.0339 10.3726 2.48955C11.6134 1.83528 12.9772 1.44215 14.3783 1.33476C15.7929 1.21405 17.2169 1.40754 18.5466 1.90117C20.691 2.74101 22.5006 4.83086 23.2811 7.7166H23.2491V15.9368H24.7263C25.5388 15.9368 26.3611 15.2776 26.3611 14.472V9.18388C26.3611 8.37578 25.5388 7.71904 24.7263 7.71904V7.7166ZM15.9023 25.4974H16.3603C16.5562 25.4974 16.744 25.4202 16.8826 25.2828C17.0211 25.1455 17.0989 24.9592 17.0989 24.7649V23.5442C17.0989 23.35 17.0211 23.1637 16.8826 23.0263C16.744 22.889 16.5562 22.8118 16.3603 22.8118H13.652C13.4571 22.815 13.2711 22.8931 13.1333 23.0298C12.9954 23.1665 12.9166 23.351 12.9134 23.5442V24.7649C12.9172 24.958 12.9962 25.1421 13.1339 25.2787C13.2716 25.4152 13.4573 25.4936 13.652 25.4974H14.1075L13.2113 29.9969H16.7591L15.9023 25.4949V25.4974ZM0 29.9993C0.352072 25.4729 -0.544112 25.656 3.27944 24.2376C5.23337 23.5273 7.11196 22.6277 8.88798 21.552L12.1551 30.0066L0 29.9993ZM21.2721 21.159C22.8938 22.1804 24.6361 23.0004 26.4596 23.6004C30.0295 24.7771 30.0295 24.9432 29.9951 29.9895H17.8867L21.2721 21.159Z"/>
            </g>
            <defs>
                <clipPath id="clip0_support">
                    <rect width="30" height="30"/>
                </clipPath>
            </defs>
        </svg>
    </button>

    {{-- Custom context menu --}}
    <div id="support-context-menu">
        <button id="support-hide-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
            </svg>
            Hide button
            <span class="context-menu-shortcut">Ctrl+H</span>
        </button>
    </div>
</div>

<style>
    .contact-support {
        color: #FFFFFF;
        background: #1F3B67;
        border: none;
        font-weight: 700;
        box-shadow: 1px 2px 33px -4px rgba(0,0,0,0.91);
        -webkit-box-shadow: 1px 2px 33px -4px rgba(0,0,0,0.91);
        -moz-box-shadow: 1px 2px 33px -4px rgba(0,0,0,0.91);
        transition: all .3s ease-in-out;
    }

    .contact-support:hover {
        background: #FFB901;
        transform: translateY(-2px);
    }

    #support-context-menu {
        display: none;
        position: fixed;
        z-index: 9999;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 1px 2px 33px -4px rgba(0,0,0,0.91);
        -webkit-box-shadow: 1px 2px 33px -4px rgba(0,0,0,0.91);
        -moz-box-shadow: 1px 2px 33px -4px rgba(0,0,0,0.91);
        padding: 4px;
        min-width: 180px;
        animation: contextFadeIn .12s ease-out;
    }

    @keyframes contextFadeIn {
        from {
            opacity: 0;
            transform: scale(.95)
        }

        to {
            opacity: 1;
            transform: scale(1)
        }
    }

    #support-context-menu button {
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
        transition: background .15s;
    }

    #support-context-menu button:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .context-menu-shortcut {
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
    (function () {
        const btn = document.getElementById('contact-support-btn');
        const contextMenu = document.getElementById('support-context-menu');

        function hideButton() {
            btn.style.opacity    = '0';
            btn.style.transform  = 'scale(0.8)';
            btn.style.pointerEvents = 'none';
            setTimeout(() => btn.style.display = 'none', 300);
        }

        function closeContextMenu() {
            contextMenu.style.display = 'none';
        }

        // Right-click on the button - contextmenu captures the right click action
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
        document.getElementById('support-hide-btn').addEventListener('click', function () {
            closeContextMenu();
            hideButton();
        });

        // Close menu on outside click
        document.addEventListener('click', function (e) {
            if (!contextMenu.contains(e.target)) {
                closeContextMenu();
            }
        });

        // Close menu on scroll or Escape
        document.addEventListener('scroll', closeContextMenu);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeContextMenu();
            }

            // Ctrl+H (or Cmd+H on Mac) to hide
            if ((e.ctrlKey || e.metaKey) && e.key === 'h') {
                e.preventDefault();
                closeContextMenu();
                hideButton();
            }
        });
    })();
</script>
