@extends('layouts.app')

@section('content')

    <x-hero 
        title="Follow Requests"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'My Community', 'url' => route('community.index')],
            ['label' => 'Requests', 'url' => route('community.requests')],
        ]"
        :backgroundImage="$pageItem?->image('hero_image') ?? asset('images/defaults/banner.webp')"
        height="h-80"
    />

    <x-community-nav-tabs active="requests" />

    <div class="my-2 px-4 min-h-[35vh] lg:min-h-[379px]">
        <div class="max-w-7xl mx-auto">
            
            {{-- Follow Requests List --}}
            @if($followRequests->count() > 0)
                <div class="space-y-4">
                    @foreach($followRequests as $request)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <img 
                                        src="{{ $request->follower->profile_pic_url }}" 
                                        alt="{{ $request->follower->name_value }}"
                                        class="w-12 h-12 rounded-full object-cover border-2 border-gray-100"
                                    >
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $request->follower->name_value }}</h3>
                                        @if($request->follower->company)
                                            <p class="text-lg text-gray-600">{{ $request->follower->company->title }}</p>
                                        @endif
                                        @if($request->follower->jobRole)
                                            <p class="text-base text-gray-500">{{ $request->follower->jobRole->title }}</p>
                                        @endif
                                        <p class="text-base text-gray-400 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <div class="flex space-x-2">
                                    <button 
                                        data-request-id="{{ $request->id }}"
                                        data-action="accept"
                                        class="request-btn px-4 py-2 bg-black text-white rounded text-lg font-medium hover:bg-gray-800 transition-colors"
                                    >
                                        Accept
                                    </button>
                                    <button 
                                        data-request-id="{{ $request->id }}"
                                        data-action="decline"
                                        class="request-btn px-4 py-2 border border-gray-300 rounded text-lg font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                                    >
                                        Ignore
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $followRequests->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No follow requests</h3>
                    <p class="mt-1 text-lg text-gray-500">You don't have any pending follow requests.</p>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.request-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const requestId = this.dataset.requestId;
            const action = this.dataset.action;
            const button = this;
            
            button.disabled = true;
            const originalText = button.textContent;
            button.textContent = 'Processing...';
            
            try {
                const url = action === 'accept'
                    ? "{{ route('community.requests.accept', ':requestId') }}".replace(':requestId', requestId)
                    : "{{ route('community.requests.decline', ':requestId') }}".replace(':requestId', requestId);
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.error || 'An error occurred');
                    button.disabled = false;
                    button.textContent = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again. ' + error.message);
                button.disabled = false;
                button.textContent = originalText;
            }
        });
    });
});
</script>
@endpush