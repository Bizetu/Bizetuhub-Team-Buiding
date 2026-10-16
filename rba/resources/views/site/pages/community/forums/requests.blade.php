@extends('layouts.app')

@section('content')

    <x-hero 
        title="Manage Join Requests"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Forums', 'url' => route('forums')],
            ['label' => $forum->title, 'url' => route('community.forums.show', $forum->id)],
            ['label' => 'Requests', 'url' => route('community.forums.requests', $forum->id)],
        ]"
        :backgroundImage="asset('images/defaults/banner.webp')"
        height="h-80"
    />

    <div class="my-10 px-4">
        <div class="max-w-5xl mx-auto">
            
            {{-- Header --}}
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold blue-text">Join Requests for "{{ $forum->title }}"</h2>
                    <p class="subtle-gray-text mt-1">Review and manage requests to join your forum</p>
                </div>
                <a href="{{ route('community.forums.show', $forum->id) }}" class="px-4 py-2 border border-gray-300 rounded text-lg font-medium text-gray-700 hover:bg-gray-50">
                    Back to Forum
                </a>
            </div>

            {{-- Pending Requests --}}
            @if($pendingRequests->count() > 0)
                <div class="space-y-4">
                    @foreach($pendingRequests as $request)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <img 
                                        src="{{ $request->user->profile_pic_url }}" 
                                        alt="{{ $request->user->name_value }}"
                                        class="w-14 h-14 rounded-full object-cover border-2 border-gray-100"
                                    >
                                    <div>
                                        <h3 class="text-xl font-semibold blue-text">{{ $request->user->name_value }}</h3>
                                        @if($request->user->company)
                                            <p class="text-lg subtle-gray-text">{{ $request->user->company->title }}</p>
                                        @endif
                                        @if($request->user->jobRole)
                                            <p class="text-base subtle-gray-text">{{ $request->user->jobRole->title }}</p>
                                        @endif
                                        <p class="text-base subtle-gray-text mt-1">Requested {{ $request->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <button 
                                        data-request-id="{{ $request->id }}"
                                        data-action="approve"
                                        class="request-btn px-5 py-2 blue-bg text-white rounded text-lg font-medium hover:opacity-90 transition-opacity"
                                    >
                                        Approve
                                    </button>
                                    <button 
                                        data-request-id="{{ $request->id }}"
                                        data-action="reject"
                                        class="request-btn px-5 py-2 border border-gray-300 rounded text-lg font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                                    >
                                        Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $pendingRequests->links() }}
                </div>
            @else
                <div class="text-center py-12 bg-white border border-gray-200 rounded-lg">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No pending requests</h3>
                    <p class="mt-1 text-lg text-gray-500">You don't have any pending join requests for this forum.</p>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.request-btn').forEach(button => {
        button.addEventListener('click', async function() {
            const requestId = this.dataset.requestId;
            const action = this.dataset.action;
            const button = this;

            button.disabled = true;
            button.textContent = action === 'approve' ? 'Approving...' : 'Rejecting...';

            try {
                const url = action === 'approve'
                    ? "{{ route('community.forums.members.approve', ['forum' => $forum->id, 'member' => ':requestId']) }}".replace(':requestId', requestId)
                    : "{{ route('community.forums.members.reject', ['forum' => $forum->id, 'member' => ':requestId']) }}".replace(':requestId', requestId);

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'success',
                            title: data.message
                        }
                    }));

                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'error',
                            title: data.error || 'An error occurred'
                        }
                    }));

                    button.disabled = false;
                    button.textContent = action === 'approve' ? 'Approve' : 'Reject';
                }
            } catch (error) {
                console.error('Error:', error);
                
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        type: 'error',
                        title: 'An error occurred. Please try again.'
                    }
                }));

                button.disabled = false;
                button.textContent = action === 'approve' ? 'Approve' : 'Reject';
            }
        });
    });
});
</script>
@endpush