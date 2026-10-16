

<div class="basis-full sm:basis-1/2 lg:basis-1/3 shrink-0">
    <article>
        <div class="w-full h-[150px]">
            <a href="{{route('casestudy.details', $casestudy->id)}}">
                <img
                    class="case-study-case-card-image h-[150px] w-full object-cover"
                    src="{{$casestudy->image('cover_image')}}"
                    loading="lazy"
                    alt="{{$casestudy->title}}"
                />
            </a>
        </div>

        <div class="casestudy-card-content w-full">
            <div class="content">
                <p class="title text-center casestudy-title h-[50px]">
                    <a href="{{route('casestudy.details', $casestudy->id)}}" class="text-white text-[18px]">
                        {{ \Illuminate\Support\Str::limit($casestudy->title, 100) }}
                    </a>
                </p>

                <p class="casestudy-readmore flex justify-between items-center">
                    <span class="text-grey flex items-center gap-1">
                        <img src="/images/icons/clock.svg" class="w-4" />
                        {{$casestudy->publication_year}}
                    </span>

                    <a href="{{route('casestudy.details', $casestudy->id)}}" class="flex items-center gap-1">
                        <img src="/images/icons/read-more.svg" class="w-4" />
                        View Case Study
                    </a>
                </p>
            </div>
        </div>
    </article>
</div>
