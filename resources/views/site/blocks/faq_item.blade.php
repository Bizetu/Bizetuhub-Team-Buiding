<section class="mx-auto max-w-5xl px-6 py-5">
    <details class="group rounded-2xl border border-slate-200 bg-white p-6">
        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-lg font-bold">
            {{ $block->translatedInput('question') }}
            <span class="text-emerald-700 transition group-open:rotate-45" aria-hidden="true">+</span>
        </summary>
        <div class="prose prose-slate mt-4 leading-7">{!! $block->translatedWysiwyg('answer') !!}</div>
    </details>
</section>
