<div class="jarallax">
    <div class="relative text-center">
        <!-- Image -->
        <img src="{{ $image }}" alt="Descriptive alt text" class="jarallax-img  w-full h-auto" />

        <!-- Text Overlay -->
        <div class="absolute top-0 left-0 flex flex-col items-start justify-center w-full h-full px-10">
            <h2 class="text-4xl font-bold text-white">
                {{ $title }}
            </h2>
            <p class="break-normal sm:text-[18px] text-xl text-white mt-2">
                {{ $text }}
            </p>
        </div>

    </div>

</div>
