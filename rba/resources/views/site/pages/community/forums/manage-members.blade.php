@extends('layouts.app')

@section('content')

    <x-hero 
        title="Manage Forum Members"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Forums', 'url' => route('forums')],
            ['label' => $forum->title, 'url' => route('community.forums.show', $forum->id)],
            ['label' => 'Manage Members', 'url' => route('community.forums.members', $forum->id)],
        ]"
        :backgroundImage="asset('images/defaults/banner.webp')"
        height="h-80"
    />

    <div class="my-10 px-4">
        <div class="max-w-7xl mx-auto">
            
            {{-- Header --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold blue-text">{{ $forum->title }}</h2>
                    <p class="subtle-gray-text mt-1 text-lg sm:text-lg">Manage members, requests, and blocked users</p>
                </div>
                <a href="{{ route('community.forums.show', $forum->id) }}" class="inline-flex items-center justify-center sm:justify-start px-4 py-2 border border-gray-300 rounded text-lg font-medium text-gray-700 hover:bg-gray-50">
                    ← Back to Forum
                </a>
            </div>

            {{-- Tabs --}}
            <div class="mb-6 border-b border-gray-200" x-data="{ activeTab: 'members' }">
                <nav class="flex space-x-4 sm:space-x-8 overflow-x-auto">
                    <button 
                        @click="activeTab = 'members'"
                        :class="activeTab === 'members' ? 'border-black blue-text' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="border-b-2 pb-4 px-1 text-lg font-medium whitespace-nowrap transition-colors"
                    >
                        Members ({{ $members->total() }})
                    </button>
                    <button 
                        @click="activeTab = 'pending'"
                        :class="activeTab === 'pending' ? 'border-black blue-text' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="border-b-2 pb-4 px-1 text-lg font-medium whitespace-nowrap transition-colors"
                    >
                        Pending Requests ({{ $pendingRequests->total() }})
                    </button>
                    {{-- <button 
                        @click="activeTab = 'blocked'"
                        :class="activeTab === 'blocked' ? 'border-black blue-text' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="border-b-2 pb-4 px-1 text-lg font-medium whitespace-nowrap transition-colors"
                    >
                        Blocked Users ({{ $blockedUsers->total() }})
                    </button> --}}
                </nav>

                {{-- Members Tab --}}
                <div x-show="activeTab === 'members'" class="mt-6">
                    @if($members->count() > 0)
                        <div class="space-y-4">
                            @foreach($members as $member)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 sm:p-6">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        {{-- User Info --}}
                                        <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
                                            <img 
                                                src="{{ $member->user->profile_pic_url }}" 
                                                alt="{{ $member->user->name_value }}"
                                                class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover border-2 border-gray-100 flex-shrink-0"
                                            >
                                            <div class="min-w-0 flex-1">
                                                <h3 class="text-lg sm:text-xl font-semibold blue-text truncate">{{ $member->user->name_value }}</h3>
                                                @if($member->user->company)
                                                    <p class="text-base sm:text-lg subtle-gray-text truncate">{{ $member->user->company->title }}</p>
                                                @endif
                                                @if($member->user->jobRole)
                                                    <p class="text-base subtle-gray-text truncate">{{ $member->user->jobRole->title }}</p>
                                                @endif
                                                <p class="text-base subtle-gray-text mt-1">Joined {{ $member->joined_at->diffForHumans() }}</p>
                                            </div>
                                        </div>

                                        {{-- Actions - Stack on mobile --}}
                                        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                            @if($member->user_id !== $user->id)
                                                <button 
                                                    data-member-id="{{ $member->id }}"
                                                    data-action="remove"
                                                    class="member-action-btn w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded text-lg font-medium hover:bg-red-700 transition-colors"
                                                >
                                                    Remove
                                                </button>
                                                {{-- <button 
                                                    data-user-id="{{ $member->user_id }}"
                                                    data-action="block"
                                                    class="block-action-btn w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded text-lg font-medium hover:bg-red-700 transition-colors"
                                                >
                                                    Block
                                                </button> --}}
                                            @else
                                                <span class="px-4 py-2 yellow-bg blue-text rounded text-lg font-semibold text-center">
                                                    You (Creator)
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $members->links() }}
                        </div>
                    @else
                        <div class="text-center py-12 bg-white border border-gray-200 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No members yet</h3>
                            <p class="mt-1 text-lg text-gray-500">Members will appear here once join requests are approved.</p>
                        </div>
                    @endif
                </div>

                {{-- Pending Requests Tab --}}
                <div x-show="activeTab === 'pending'" class="mt-6" style="display: none;">
                    @if($pendingRequests->count() > 0)
                        <div class="space-y-4">
                            @foreach($pendingRequests as $request)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 sm:p-6">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        {{-- User Info --}}
                                        <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
                                            <img 
                                                src="{{ $request->user->profile_pic_url }}" 
                                                alt="{{ $request->user->name_value }}"
                                                class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover border-2 border-gray-100 flex-shrink-0"
                                            >
                                            <div class="min-w-0 flex-1">
                                                <h3 class="text-lg sm:text-xl font-semibold blue-text truncate">{{ $request->user->name_value }}</h3>
                                                @if($request->user->company)
                                                    <p class="text-base sm:text-lg subtle-gray-text truncate">{{ $request->user->company->title }}</p>
                                                @endif
                                                @if($request->user->jobRole)
                                                    <p class="text-base subtle-gray-text truncate">{{ $request->user->jobRole->title }}</p>
                                                @endif
                                                <p class="text-base subtle-gray-text mt-1">Requested {{ $request->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>

                                        {{-- Actions - Stack on mobile --}}
                                        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                            <button 
                                                data-request-id="{{ $request->id }}"
                                                data-action="approve"
                                                class="request-action-btn w-full sm:w-auto px-5 py-2 blue-bg text-white rounded text-lg font-medium hover:opacity-90 transition-opacity"
                                            >
                                                Approve
                                            </button>
                                            <button 
                                                data-request-id="{{ $request->id }}"
                                                data-action="reject"
                                                class="request-action-btn w-full sm:w-auto px-5 py-2 border border-gray-300 rounded text-lg font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                                            >
                                                Reject
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $pendingRequests->links() }}
                        </div>
                    @else
                        <div class="text-center py-12 bg-white border border-gray-200 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No pending requests</h3>
                            <p class="mt-1 text-lg text-gray-500">You don't have any pending join requests.</p>
                        </div>
                    @endif
                </div>

                {{-- <div x-show="activeTab === 'blocked'" class="mt-6" style="display: none;">
                    @if($blockedUsers->count() > 0)
                        <div class="space-y-4">
                            @foreach($blockedUsers as $block)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 sm:p-6">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
                                            <img 
                                                src="{{ $block->user->profile_pic_url }}" 
                                                alt="{{ $block->user->name_value }}"
                                                class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover border-2 border-gray-100 flex-shrink-0"
                                            >
                                            <div class="min-w-0 flex-1">
                                                <h3 class="text-lg sm:text-xl font-semibold blue-text truncate">{{ $block->user->name_value }}</h3>
                                                @if($block->user->company)
                                                    <p class="text-base sm:text-lg subtle-gray-text truncate">{{ $block->user->company->title }}</p>
                                                @endif
                                                @if($block->user->jobRole)
                                                    <p class="text-base subtle-gray-text truncate">{{ $block->user->jobRole->title }}</p>
                                                @endif
                                                <p class="text-base subtle-gray-text mt-1">Blocked {{ $block->created_at->diffForHumans() }}</p>
                                                @if($block->reason)
                                                    <p class="text-base text-red-600 mt-1">Reason: {{ $block->reason }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="w-full sm:w-auto">
                                            <button 
                                                data-user-id="{{ $block->user_id }}"
                                                data-action="unblock"
                                                class="unblock-action-btn w-full sm:w-auto px-5 py-2 bg-green-600 text-white rounded text-lg font-medium hover:bg-green-700 transition-colors"
                                            >
                                                Unblock
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $blockedUsers->links() }}
                        </div>
                    @else
                        <div class="text-center py-12 bg-white border border-gray-200 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No blocked users</h3>
                            <p class="mt-1 text-lg text-gray-500">Users you block from this forum will appear here.</p>
                        </div>
                    @endif
                </div> --}}
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve/reject pending requests
    document.querySelectorAll('.request-action-btn').forEach(button => {
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

                    setTimeout(() => window.location.reload(), 500);
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

    // Handle remove member
    document.querySelectorAll('.member-action-btn').forEach(button => {
        button.addEventListener('click', async function() {
            if (!confirm(`Are you sure you want to remove this member? This will also delete their posts from this forum`)) return;

            const memberId = this.dataset.memberId;
            const button = this;

            button.disabled = true;
            button.textContent = 'Removing...';

            try {
                const response = await fetch("{{ route('community.forums.members.remove', ['forum' => $forum->id, 'member' => ':memberId']) }}".replace(':memberId', memberId), {
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

                    setTimeout(() => window.location.reload(), 500);
                } else {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'error',
                            title: data.error || 'Failed to remove member'
                        }
                    }));

                    button.disabled = false;
                    button.textContent = 'Remove';
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
                button.textContent = 'Remove';
            }
        });
    });

    // Handle block user
    // document.querySelectorAll('.block-action-btn').forEach(button => {
    //     button.addEventListener('click', async function() {
    //         const reason = prompt('Enter reason for blocking (optional):');
    //         if (reason === null) return;

    //         const userId = this.dataset.userId;
    //         const button = this;

    //         button.disabled = true;
    //         button.textContent = 'Blocking...';

    //         try {
    //             const response = await fetch("{{ route('community.forums.block', ['forum' => $forum->id, 'targetUser' => ':userId']) }}".replace(':userId', userId), {
    //                 method: 'POST',
    //                 headers: {
    //                     'Content-Type': 'application/json',
    //                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    //                     'Accept': 'application/json'
    //                 },
    //                 body: JSON.stringify({ reason: reason })
    //             });

    //             const data = await response.json();

    //             if (data.success) {
    //                 window.dispatchEvent(new CustomEvent('toast', {
    //                     detail: {
    //                         type: 'success',
    //                         title: data.message
    //                     }
    //                 }));

    //                 setTimeout(() => window.location.reload(), 500);
    //             } else {
    //                 window.dispatchEvent(new CustomEvent('toast', {
    //                     detail: {
    //                         type: 'error',
    //                         title: data.error || 'Failed to block user'
    //                     }
    //                 }));

    //                 button.disabled = false;
    //                 button.textContent = 'Block';
    //             }
    //         } catch (error) {
    //             console.error('Error:', error);
    //             window.dispatchEvent(new CustomEvent('toast', {
    //                 detail: {
    //                     type: 'error',
    //                     title: 'An error occurred. Please try again.'
    //                 }
    //             }));

    //             button.disabled = false;
    //             button.textContent = 'Block';
    //         }
    //     });
    // });

    // Handle unblock user
    document.querySelectorAll('.unblock-action-btn').forEach(button => {
        button.addEventListener('click', async function() {
            if (!confirm('Are you sure you want to unblock this user?')) return;

            const userId = this.dataset.userId;
            const button = this;

            button.disabled = true;
            button.textContent = 'Unblocking...';

            try {
                const response = await fetch("{{ route('community.forums.unblock', ['forum' => $forum->id, 'targetUser' => ':userId']) }}".replace(':userId', userId), {
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

                    setTimeout(() => window.location.reload(), 500);
                } else {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'error',
                            title: data.error || 'Failed to unblock user'
                        }
                    }));

                    button.disabled = false;
                    button.textContent = 'Unblock';
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
                button.textContent = 'Unblock';
            }
        });
    });
});
</script>
@endpush