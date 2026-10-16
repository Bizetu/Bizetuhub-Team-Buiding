@extends('layouts.app')

@section('title', 'About - Rwanda Bankers Association')

@section('content')

    @php
        $wysiwygStyling = "prose prose-slate max-w-none [&_h2]:text-4xl [&_h2]:mt-3 [&_h2]:mb-3 [&_h2]:text-[#1F3B67] [&_h2]:font-normal [&_h3]:text-2xl [&_h3]:mt-5 [&_h3]:mb-3 [&_h3]:text-[#1F3B67] [&_h3]:font-semibold [&_h4]:text-xl [&_h4]:mt-4 [&_h4]:mb-3 [&_h4]:text-[#1F3B67] [&_h4]:font-semibold [&_h5]:text-xl [&_h5]:mt-3 [&_h5]:mb-2 [&_h5]:text-[#1F3B67] [&_h5]:font-semibold [&_h6]:text-lg [&_h6]:mt-2 [&_h6]:mb-2 [&_h6]:text-[#1F3B67] [&_h6]:font-semibold [&_p]:text-[#1F3B67] [&_p]:leading-relaxed [&_p]:text-lg [&_p]:mb-3 [&_strong]:font-semibold [&_strong]:text-[#1F3B67] [&_em]:italic [&_em]:text-[#1F3B67] [&_u]:underline [&_u]:text-[#1F3B67] [&_s]:line-through [&_s]:text-[#1F3B67]/70 [&_blockquote]:border-l-4 [&_blockquote]:border-[#1F3B67]/30 [&_blockquote]:pl-6 [&_blockquote]:italic [&_blockquote]:text-[#1F3B67]/80 [&_blockquote]:my-8 [&_ul]:list-disc [&_ul]:ml-6 [&_ul]:text-[#1F3B67] [&_ul]:mb-3 [&_ol]:list-decimal [&_ol]:ml-6 [&_ol]:text-[#1F3B67] [&_ol]:mb-3 [&_li]:my-2 [&_li]:text-[#1F3B67] [&_li]:leading-relaxed [&_hr]:border-[#1F3B67]/20 [&_hr]:my-12 [&_a]:text-[#1F3B67] [&_a]:underline [&_a]:underline-offset-2 [&_a:hover]:text-[#ffb901] [&_a:hover]:text-[#1F3B67]/70 [&_a:hover]:transition-colors [&_table]:w-full [&_table]:border-collapse [&_table]:my-8 [&_table]:text-lg [&_table]:bg-[#1F3B67]/5 [&_th]:bg-[#1F3B67] [&_th]:text-white [&_th]:px-4 [&_th]:py-3 [&_th]:text-left [&_th]:font-semibold [&_th_h2]:text-white [&_th_h2]:mt-2 [&_th_h3]:text-white [&_th_h2]:mt-2 [&_th_h4]:text-white [&_th_h2]:mt-2 [&_th_h5]:text-white [&_th_h2]:mt-2 [&_th_h6]:text-white [&_th_h2]:mt-2 [&_th_p]:text-white [&_th_h2]:mt-2 [&_td]:px-4 [&_td]:py-3 [&_td]:border [&_td]:border-[#1F3B67]/20 [&_td]:text-[#1F3B67] [&_tr:nth-child(even)]:bg-[#1F3B67]/5 [&_.ql-align-center]:text-center [&_.ql-align-right]:text-right [&_.ql-align-justify]:text-justify";
    @endphp

    <x-hero
        title="{{ $pageItem->header_title }}"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'About', 'url' => 'about'],
        ]"
        :backgroundImage="$pageItem->image('hero_image')"
        height="h-80"
    />

    <section class="relative min-h-[272px] lg:min-h-[325px] overflow-hidden mx-auto my-4 container">
        <div class="mx-auto text-[#1F3B67]">
            <div class="{{ $wysiwygStyling }}">{!! $pageItem->description !!}</div>
            {!! $pageItem->renderBlocks() !!}
        </div>
    </section>

@endsection
