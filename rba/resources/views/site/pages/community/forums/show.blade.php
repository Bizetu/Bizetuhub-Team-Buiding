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

    <div class="my-10 hp-4">
        <div class="max-w-5xl mx-auto">
            
            {{-- Forum Header --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 sm:p-6 mb-6">
                {{-- Mobile: Stack vertically, Desktop: Side by side --}}
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between tw-gap-4">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-xl sm:text-2xl font-bold blue-text mb-2 break-words">{{ $forum->title }}</h2>
                        <p class="mb-4 text-lg sm:text-lg">{{ $forum->description }}</p>
                        
                        {{-- Creator info and badge - Stack on very small screens --}}
                        <div class="flex flex-wrap items-center tw-gap-2 text-base sm:text-lg text-gray-500">
                            <span>Created by {{ $forum->creator->name_value }}</span>
                            @if($isCreator)
                                <span class="hp-2 py-1 yellow-bg text-gray-100 text-base font-semibold rounded whitespace-nowrap">
                                    You're the Moderator
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Manage button - Full width on mobile, auto on desktop --}}
                    @if($isCreator)
                        <div class="w-full sm:w-auto sm:ml-4 flex-shrink-0">
                            <a href="{{ route('community.forums.requests', $forum->id) }}" class="inline-flex items-center justify-center w-full sm:w-auto hp-4 vp-2 blue-bg text-white rounded text-lg font-medium hover:opacity-90 transition-opacity whitespace-nowrap">
                                Manage Requests
                                @if($pendingRequestsCount)
                                    ({{ $pendingRequestsCount }})
                                @endif
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- New Post Form --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-xl font-semibold blue-text mb-4">Start a Discussion Topic</h3>
                <form id="new-post-form">
                    @csrf
                    <textarea 
                        id="post-content"
                        name="content"
                        rows="4"
                        required
                        maxlength="5000"
                        class="w-full hp-4 vp-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-sky-950 focus:border-transparent mb-3"
                        placeholder="Share your thoughts, ask a question, or start a discussion..."
                    ></textarea>
                    <div class="flex justify-end">
                        <button 
                            type="submit"
                            id="post-btn"
                            class="hp-6 vp-2 blue-bg text-white rounded text-lg font-medium hover:opacity-90 transition-opacity"
                        >
                            Post
                        </button>
                    </div>
                </form>
            </div>

            {{-- Posts List --}}
            <div id="posts-container" class="space-y-6">
                @forelse($posts as $post)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden" data-post-id="{{ $post->id }}">
                        {{-- Main Post --}}
                        <div class="p-6 bg-gray-200">
                            <div class="flex items-start tw-gap-4">
                                <img 
                                    src="{{ $post->user->profile_pic_avatar }}" 
                                    alt="{{ $post->user->name_value }}"
                                    class="w-12 h-12 rounded-full object-cover border-2 border-gray-100"
                                >
                                <div class="flex-1">
                                    <div class="flex items-center tw-gap-2 mb-2">
                                        <span class="font-semibold blue-text">{{ $post->user->name_value }}</span>
                                        @if($post->user_id === $forum->creator_id)
                                            <span class="ml-auto hp-2 py-0.5 yellow-bg text-gray-100 text-base font-semibold rounded">Moderator</span>
                                        @endif
                                    </div>
                                    <p class="blue-text whitespace-pre-wrap">{{ $post->content }}</p>

                                    <div class="mt-4 flex items-center tw-gap-4">
                                        <button 
                                            class="reply-btn text-lg blue-text hover:underline flex items-center"
                                            data-post-id="{{ $post->id }}"
                                        >
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                            </svg>
                                            Reply
                                        </button>

                                        @if($post->replies->count() > 0)
                                            <span class="text-lg">{{ $post->replies->count() }} {{ Str::plural('reply', $post->replies->count()) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Reply Form (hidden by default) --}}
                            <div id="reply-form-{{ $post->id }}" class="hidden mt-4 ml-16">
                                <form class="reply-form" data-parent-id="{{ $post->id }}">
                                    @csrf
                                    <textarea 
                                        name="content"
                                        rows="3"
                                        required
                                        maxlength="5000"
                                        class="w-full hp-4 vp-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-2"
                                        placeholder="Write your reply..."
                                    ></textarea>
                                    <div class="flex justify-end tw-gap-2">
                                        <button 
                                            type="button"
                                            class="cancel-reply-btn hp-4 vp-2 border border-gray-300 rounded text-lg font-medium text-gray-700 hover:bg-gray-50"
                                        >
                                            Cancel
                                        </button>
                                        <button 
                                            type="submit"
                                            class="hp-4 vp-2 blue-bg text-white rounded text-lg font-medium hover:opacity-90"
                                        >
                                            Reply
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Replies --}}
                        @if($post->replies->count() > 0)
                            <div class="bg-gray-50 border-t border-gray-200 hp-6 vp-4">
                                <div class="flex flex-col gap-4" id="replies-{{ $post->id }}">

                                    @foreach($post->replies as $reply)

                                        @if($reply->user_id === auth()->id())
                                            {{-- Current user: bubble right --}}
                                            <div class="flex justify-end">
                                                <div class="flex items-start gap-3 flex-row-reverse w-full sm:w-4/5 lg:w-1/2">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ $reply->user->profile_pic_avatar }}"
                                                            alt="{{ $reply->user->name_value }}"
                                                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover">
                                                    </div>
                                                    <div class="flex-1 min-w-0 blue-bg rounded-2xl hp-6 vp-4">
                                                        <div class="flex items-start justify-between gap-2 mb-2">
                                                            <p class="hidden md:block text-base text-white whitespace-nowrap opacity-80">
                                                                {{ $reply->created_at->format('d M Y, h:ia') }}
                                                            </p>
                                                            <div class="text-right ml-auto">
                                                                <p class="font-semibold text-white text-lg leading-tight">
                                                                    {{ $reply->user->name_value }}
                                                                </p>
                                                                <p class="hidden sm:block text-base text-white opacity-80 mt-0.5">
                                                                    {{ $reply->user->job_role_name_value ?? 'Operations (Branch) - Management' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <p class="text-white text-lg leading-relaxed text-right break-words">
                                                            {{ $reply->content }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                        @else
                                            {{-- Other user: bubble left --}}
                                            <div class="flex justify-start">
                                                <div class="flex items-start gap-3 w-full sm:w-4/5 lg:w-1/2">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ $reply->user->profile_pic_avatar }}"
                                                            alt="{{ $reply->user->name_value }}"
                                                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover">
                                                    </div>
                                                    <div class="flex-1 min-w-0 bg-gray-200 rounded-2xl hp-6 vp-4">
                                                        <div class="flex items-start justify-between gap-2 mb-2">
                                                            <div class="min-w-0">
                                                                <p class="font-semibold text-gray-900 text-lg leading-tight">
                                                                    {{ $reply->user->name_value }}
                                                                </p>
                                                                <p class="hidden sm:block text-base text-gray-600 mt-0.5">
                                                                    {{ $reply->user->job_role_name_value ?? 'Operations (Branch) - Management' }}
                                                                </p>
                                                            </div>
                                                            <p class="hidden md:block text-base text-gray-500 whitespace-nowrap flex-shrink-0">
                                                                {{ $reply->created_at->format('d M Y, h:ia') }}
                                                            </p>
                                                        </div>
                                                        <p class="text-gray-700 text-lg leading-relaxed break-words">
                                                            {{ $reply->content }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 bg-white border border-gray-200 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No discussions yet</h3>
                        <p class="mt-1 text-lg text-gray-500">Be the first to start a discussion topic in this forum.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($posts->hasPages())
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @endif

        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const newPostForm = document.getElementById('new-post-form');
        const postBtn = document.getElementById('post-btn');
        const postContent = document.getElementById('post-content');

        // New post form submission
        newPostForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const content = postContent.value.trim();
            if (!content) return;

            postBtn.disabled = true;
            postBtn.textContent = 'Posting...';

            try {
                const response = await fetch("{{ route('community.forums.posts.create', $forum->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: content })
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
                            title: data.error || 'Failed to post'
                        }
                    }));

                    postBtn.disabled = false;
                    postBtn.textContent = 'Post';
                }
            } catch (error) {
                console.error('Error:', error);
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        type: 'error',
                        title: 'An error occurred. Please try again.'
                    }
                }));

                postBtn.disabled = false;
                postBtn.textContent = 'Post';
            }
        });

        // Reply button clicks
        document.querySelectorAll('.reply-btn').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.dataset.postId;
                const replyForm = document.getElementById(`reply-form-${postId}`);
                replyForm.classList.toggle('hidden');
            });
        });

        // Cancel reply buttons
        document.querySelectorAll('.cancel-reply-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.reply-form');
                form.closest('[id^="reply-form-"]').classList.add('hidden');
                form.querySelector('textarea').value = '';
            });
        });

        // Reply form submissions
        document.querySelectorAll('.reply-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const parentId = this.dataset.parentId;
                const textarea = this.querySelector('textarea');
                const content = textarea.value.trim();
                const submitBtn = this.querySelector('button[type="submit"]');

                if (!content) return;

                submitBtn.disabled = true;
                submitBtn.textContent = 'Replying...';

                try {
                    const response = await fetch("{{ route('community.forums.posts.create', $forum->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ 
                            content: content,
                            parent_id: parentId
                        })
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
                                title: data.error || 'Failed to post reply'
                            }
                        }));

                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Reply';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'error',
                            title: 'An error occurred. Please try again.'
                        }
                    }));

                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Reply';
                }
            });
        });
    });
</script>
@endpush