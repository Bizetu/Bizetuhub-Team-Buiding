@extends('layouts.app')

@section('content')

    <x-hero 
        title="{{ $pageItem?->header_title ?? 'My Community' }}"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'My Community', 'url' => route('community.index')],
        ]"
        :backgroundImage="$pageItem?->image('hero_image') ?? asset('images/defaults/banner.webp')"
        height="h-80"
    />

    <x-community-nav-tabs active="discover" />

    <div class="my-2 px-4 min-h-[35vh] lg:min-h-[379px]">
        <div class="max-w-7xl mx-auto">

            {{-- Community Members Grid --}}
            @if($communityUsers->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($communityUsers as $communityUser)
                        <x-community-user-card 
                            :user="$communityUser" 
                            :currentUser="$user"
                        />
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $communityUsers->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No community members found</h3>
                    <p class="mt-1 text-lg text-gray-500">Enroll in courses to connect with other learners.</p>
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
                const url = action === 'follow' 
                    ? "{{ route('community.follow', ':userId') }}".replace(':userId', userId)
                    : "{{ route('community.unfollow', ':userId') }}".replace(':userId', userId);
                
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
                            title: data.message,
                            duration: 5000
                        }
                    }));
                    
                    setTimeout(() => window.location.reload(), 6000);
                } else {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'error',
                            title: data.error || 'An error occurred'
                        }
                    }));
                    
                    button.disabled = false;
                    button.textContent = originalText;
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
                button.textContent = originalText;
            }
        });
    });
});
</script>
@endpush