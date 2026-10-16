@if($block->children && count($block->children) > 0)
    @php
        $sorted = collect($block->children)
            ->filter(fn($i) => (bool) $i->input('publish_status'))
            ->sortBy(fn($i) => $i->input('due_date'));
        $index = 0;
    @endphp

    <div class="my-8 border border-[#1F3B67] overflow-hidden">
        <table class="min-w-full bg-gray-100 border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-6 py-3 text-left text-base font-semibold md:font-bold text-[#1F3B67] uppercase tracking-wide w-1/2">Course</th>
                    <th class="border border-gray-300 px-6 py-3 text-left text-base font-semibold md:font-bold text-[#1F3B67] uppercase tracking-wide">Due Date</th>
                    <th class="border border-gray-300 px-6 py-3 text-left text-base font-semibold md:font-bold text-[#1F3B67] uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                @forelse ($sorted as $item)
                    @php
                        $course  = App\Models\Course::where('id', $item->input('course_id'))->first();
                        $dueDate = $item->input('due_date');
                        $isPast  = $dueDate && \Carbon\Carbon::parse($dueDate)->isPast();
                        $index++;
                    @endphp
                    <tr class="hover:bg-gray-50/60 transition-colors duration-100">

                        <td class="border border-gray-300 px-6 py-2">
                            {{ $index . '. ' . $course->display_name ?? '—' }}
                        </td>

                        <td class="border border-gray-300 px-6 py-2">
                            {{ $dueDate ? \Carbon\Carbon::parse($dueDate)->format('d M Y') : '—' }}
                        </td>

                        <td class="border border-gray-300 px-6 py-2">
                            @if ($isPast)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-base font-medium bg-red-50 text-red-700 ring-1 ring-red-200">
                                    Past Due
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-base font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">
                                    Open
                                </span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-lg text-gray-400">
                            No upcoming deadlines at this time.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@else
    <div class="my-8 border border-[#1F3B67] overflow-hidden">
        <table class="min-w-full bg-gray-100 min-h-80">
            <tbody class="divide-y divide-gray-300">
                <tr>
                    <td colspan="3" class="px-6 py-10 text-center text-lg text-gray-400">
                        No upcoming deadlines at this time.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endif
