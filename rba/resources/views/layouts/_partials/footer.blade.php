<footer class="bg-[#1F3B67] text-white">
    <div class="max-w-full sm:max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Bottom Bar -->
        <div class="vp-8 flex flex-col md:flex-row justify-between items-center text-sm text-white">
            <div class="flex flex-wrap gap-6 my-2 md:my-0 justify-center md:justify-start">
                @guest
                    @foreach (footerMenuGuest() as $menu)
                        <a href="{{ route($menu->key) }}" class="hover:text-white transition duration-200">{!! $menu->title !!}</a>
                    @endforeach
                @else
                    @foreach (footerMenu() as $menu)
                        <a href="{{ route($menu->key) }}" class="hover:text-white transition duration-200">{!! $menu->title !!}</a>
                    @endforeach
                @endguest
            </div>
            <div class="my-2 md:my-0">© {{ date('Y') }} RBA, Inc. All Rights Reserved | Powered by Farwell Innovations Limited</div>
        </div>
    </div>
</footer>
