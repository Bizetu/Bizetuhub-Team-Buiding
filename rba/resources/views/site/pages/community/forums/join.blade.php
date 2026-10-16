@extends('layouts.app')

@section('content')

    <x-hero 
        title="{{ $forum->title }}"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Forums', 'url' => route('forums')],
            ['label' => $forum->title, 'url' => route('community.forums.show', $forum->id)],
        ]"
        :backgroundImage="asset('images/defaults/banner.webp')"
        height="h-80"
    />

    <div class="my-10 px-4">
        <div class="max-w-4xl mx-auto">
            
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-8">
                {{-- Forum Info --}}
                <div class="text-center mb-8">
                    <div class="w-20 h-20 blue-bg rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>

                    <h2 class="text-3xl font-bold blue-text mb-3">{{ $forum->title }}</h2>
                    <p class="text-xl subtle-gray-text mb-6">{{ $forum->description }}</p>

                    <div class="flex items-center justify-center gap-6 text-lg subtle-gray-text">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Created by {{ $forum->creator->name_value }}
                        </div>
                    </div>
                </div>

                {{-- Join Status --}}
                <div class="max-w-md mx-auto">
                    @if($memberStatus === null)
                        {{-- Not a member - show join button --}}
                        <div class="text-center p-6 bg-gray-50 rounded-lg">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold blue-text mb-2">Join to Participate</h3>
                            <p class="text-lg subtle-gray-text mb-6">Request to join this forum to view and participate in discussions.</p>
                            
                            <button 
                                id="join-btn"
                                class="flex mx-auto blue-button-rounded-full"
                            >
                                Request to Join
                            </button>
                        </div>

                    @elseif($memberStatus === 0)
                        {{-- Pending approval --}}
                        <div class="text-center p-6 yellow-bg rounded-lg">
                            <svg class="w-16 h-16 mx-auto mb-4 blue-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold blue-text mb-2">Request Pending</h3>
                            <p class="text-lg blue-text">Your join request is awaiting approval from the forum creator.</p>
                        </div>

                    @elseif($memberStatus === 2)
                        {{-- Rejected - Allow resubmission --}}
                        <div class="text-center p-6 bg-orange-50 border border-orange-200 rounded-lg">
                            <svg class="w-16 h-16 mx-auto mb-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-orange-800 mb-2">Previous Request Declined</h3>
                            <p class="text-lg text-orange-700 mb-6">Your previous request to join this forum was declined. You may submit a new request.</p>
                            
                            <button 
                                id="join-btn"
                                class="w-full px-6 py-3 blue-bg text-white rounded font-medium hover:opacity-90 transition-opacity"
                            >
                                Request to Join Again
                            </button>
                        </div>
                    @endif

                    <div class="mt-6 text-center">
                        <a href="{{ route('forums') }}" class="text-lg blue-text hover:underline">
                            ← Back to Forums
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

@if($memberStatus === null || $memberStatus === 2)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const joinBtn = document.getElementById('join-btn');
    
    if (!joinBtn) return;

    joinBtn.addEventListener('click', async function() {
        joinBtn.disabled = true;
        joinBtn.textContent = 'Sending Request...';

        try {
            const response = await fetch("{{ route('community.forums.join', $forum->id) }}", {
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
                }, 1000);
            } else {
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        type: 'error',
                        title: data.error || 'Failed to send join request'
                    }
                }));

                joinBtn.disabled = false;
                joinBtn.textContent = '{{ $memberStatus === 2 ? "Request to Join Again" : "Request to Join" }}';
            }
        } catch (error) {
            console.error('Error:', error);
            
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    type: 'error',
                    title: 'An error occurred. Please try again.'
                }
            }));

            joinBtn.disabled = false;
            joinBtn.textContent = '{{ $memberStatus === 2 ? "Request to Join Again" : "Request to Join" }}';
        }
    });
});
</script>
@endpush
@endif