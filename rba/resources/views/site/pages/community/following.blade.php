@extends('layouts.app')

@section('content')

    <x-hero 
        title="Following"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'My Community', 'url' => route('community.index')],
            ['label' => 'Following', 'url' => route('community.following')],
        ]"
        :backgroundImage="$pageItem?->image('hero_image') ?? asset('images/defaults/banner.webp')"
        height="h-80"
    />

    <x-community-nav-tabs active="following" />

    <div class="my-2 px-4 min-h-[35vh] lg:min-h-[379px]">
        <div class="max-w-7xl mx-auto">
            
            {{-- Following List --}}
            @if($following->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($following as $followedUser)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center space-x-4">
                                        <img 
                                            src="{{ $followedUser->profile_pic_url }}" 
                                            alt="{{ $followedUser->name_value }}"
                                            class="w-16 h-16 rounded-full object-cover border-2 border-gray-100"
                                        >
                                        <div>
                                            <h3 class="text-xl font-semibold text-gray-900">{{ $followedUser->name_value }}</h3>
                                            @if($followedUser->company)
                                                <p class="text-lg text-gray-600">{{ $followedUser->company->title }}</p>
                                            @endif
                                            @if($followedUser->jobRole)
                                                <p class="text-base text-gray-500">{{ $followedUser->jobRole->title }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex space-x-2 mt-4">
                                    <button 
                                        data-user-id="{{ $followedUser->id }}"
                                        data-action="unfollow"
                                        class="follow-btn flex-1 px-4 py-2 border border-gray-300 rounded text-lg font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                                    >
                                        Unfollow
                                    </button>

                                    <a 
                                        href="{{ route('community.messages.show', $followedUser->id) }}"
                                        class="flex-1 px-4 py-2 bg-black text-white rounded text-lg font-medium text-center hover:bg-gray-800 transition-colors"
                                    >
                                        Message
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $following->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Not following anyone yet</h3>
                    <p class="mt-1 text-lg text-gray-500">Start following community members to build your network.</p>
                    <div class="mt-6">
                        <a href="{{ route('community.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-[#1f3b67]">
                            Discover Community
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.follow-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const userId = this.dataset.userId;
            const action = this.dataset.action;
            const button = this;
            
            button.disabled = true;
            const originalText = button.textContent;
            button.textContent = 'Processing...';
            
            try {
                const url = "{{ route('community.unfollow', ':userId') }}".replace(':userId', userId);
                
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