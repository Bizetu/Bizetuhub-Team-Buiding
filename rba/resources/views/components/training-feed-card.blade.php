@props(['license'])

<div class="bg-white border border-gray-200 rounded-lg overflow-hidden mb-4 hover:shadow-md transition-shadow duration-200">
    <div class="grid grid-cols-1 lg:grid-cols-12">

        {{-- Course Image --}}
        <div class="lg:col-span-4">
            <div class="aspect-video w-full">
                <img src="{{ $license->course->image_with_fallback }}"
                     alt="{{ $license->course->display_name }}"
                     class="w-full h-full object-cover">
            </div>
        </div>

        {{-- Content Section --}}
        <div class="lg:col-span-8 p-4 lg:p-6 space-y-4">

            {{-- Header: User Info --}}
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $license->user->name }} - {{ $license->course->display_name }}</h3>
                    <p class="text-lg text-gray-600">{{ $license->user->jobRole?->title ?? 'Operations (Branch) - Management' }}</p>
                </div>

                {{-- Status Badges --}}
                <div class="flex-shrink-0">
                    @if($license->score < 20)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-50 border border-yellow-200 text-yellow-700 text-base font-semibold rounded">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/>
                            </svg>
                            Started
                        </span>
                    @elseif($license->score >= 80)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 border border-green-200 text-green-700 text-base font-semibold rounded">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2L3 7v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-7-5z"/>
                            </svg>
                            Finished
                        </span>
                    @endif
                </div>
            </div>

            {{-- Dates Row --}}
            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-gray-600">Enrollment Date</span>
                    <span class="font-semibold text-gray-900">{{ $license->created_at->format('m.d.Y') }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-gray-600">Completion Date</span>
                    <span class="font-semibold text-gray-900">
                        {{ $license->completion?->completion_date ? $license->completion->completion_date->format('m.d.Y') : '-' }}
                    </span>
                </div>
            </div>

            <div class="border-t border-gray-200"></div>

            <div>
                <div class="flex items-center gap-8">
                    {{-- Follows --}}
                    <button class="followers-button group"
                            data-user-id="{{ $license->user->id }}"
                            data-user-name="{{ $license->user->name }}">
                        <div class="flex items-center gap-2 text-gray-600 transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <div class="text-left">
                                <p class="text-base font-medium">Follows</p>
                                <p class="text-xl font-bold text-gray-900 followers-count">
                                    {{ $license->user->followers()->accepted()->count() }}
                                </p>
                            </div>
                        </div>
                    </button>

                    {{-- Likes --}}
                    <button class="like-button group"
                            data-license-id="{{ $license->id }}"
                            data-course-id="{{ $license->course_id }}"
                            data-liked="{{ $license->isLikedByUser(Auth::id()) ? 'true' : 'false' }}">
                        <div class="flex items-center gap-2 text-gray-600 group-hover:text-red-500 transition-colors">
                            <svg class="w-8 h-8 {{ $license->isLikedByUser(Auth::id()) ? 'fill-red-500 text-red-500' : '' }}"
                                 fill="{{ $license->isLikedByUser(Auth::id()) ? 'currentColor' : 'none' }}"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <div class="text-left">
                                <p class="text-base font-medium">Likes</p>
                                <p class="text-xl font-bold text-gray-900 like-count">{{ $license->like_count }}</p>
                            </div>
                        </div>
                    </button>

                    {{-- Comments --}}
                    <button class="comment-toggle group" data-license-id="{{ $license->id }}">
                        <div class="flex items-center gap-2 text-gray-600 transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <div class="text-left">
                                <p class="text-base font-medium">Comments</p>
                                <p class="text-xl font-bold text-gray-900 comment-count">{{ $license->comment_count }}</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Comments Section (Collapsible) --}}
            <div class="comments-section hidden border-t border-gray-200 pt-4 mt-4" data-license-id="{{ $license->id }}">
                <div class="comments-list space-y-4 mb-4">
                    @foreach($license->comments as $comment)
                        <x-training-comment :comment="$comment" :license="$license" />
                    @endforeach
                </div>

                {{-- Add Comment Form --}}
                <form class="comment-form" data-license-id="{{ $license->id }}" data-course-id="{{ $license->course_id }}">
                    @csrf
                    <div class="flex gap-3">
                        <img src="{{ Auth::user()->profile_pic_avatar }}"
                             alt="{{ Auth::user()->name }}"
                             class="w-10 h-10 rounded-full object-cover flex-shrink-0" />

                        <div class="flex-1">
                            <textarea class="comment-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none text-lg"
                                      name="comment"
                                      placeholder="Add a comment..."
                                      maxlength="500"
                                      rows="2"
                                      required></textarea>
                            <div class="mt-2 flex justify-end">
                                <button type="submit"
                                        class="comment-submit-btn px-4 py-2 bg-[#1f3b67] hover:bg-[#233e69] text-white text-lg font-medium rounded-lg transition-colors">
                                    Post Comment
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- Followers Context Menu --}}
<div id="followers-context-menu" style="display: none;">
    <div class="followers-menu-header">
        <span id="followers-menu-title">Followers</span>
        <button class="followers-menu-close" id="close-followers-menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
    <div class="followers-menu-body" id="followers-menu-list">
        <div class="followers-menu-loading">
            <svg class="followers-menu-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Loading...</span>
        </div>
    </div>
</div>

<style>
#followers-context-menu {
    position: fixed;
    z-index: 9999;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    min-width: 280px;
    max-width: 320px;
    max-height: 400px;
    display: flex;
    flex-direction: column;
    animation: contextFadeIn 0.15s ease-out;
}

@keyframes contextFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.followers-menu-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
    border-radius: 8px 8px 0 0;
}

.followers-menu-header span {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
}

.followers-menu-close {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    color: #6b7280;
    transition: all 0.2s;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.followers-menu-close:hover {
    color: #111827;
    background-color: #e5e7eb;
}

.followers-menu-body {
    overflow-y: auto;
    max-height: 340px;
    padding: 4px;
}

.followers-menu-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 32px 16px;
    color: #6b7280;
    font-size: 13px;
}

.followers-menu-spinner {
    width: 24px;
    height: 24px;
    animation: spin 1s linear infinite;
    margin-bottom: 8px;
}

.follower-menu-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 6px;
    transition: background-color 0.15s;
    cursor: default;
}

.follower-menu-item:hover {
    background-color: #f3f4f6;
}

.follower-menu-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e5e7eb;
    flex-shrink: 0;
}

.follower-menu-info {
    flex: 1;
    min-width: 0;
}

.follower-menu-name {
    font-weight: 600;
    color: #111827;
    font-size: 13px;
    margin: 0 0 2px 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.follower-menu-role {
    color: #6b7280;
    font-size: 12px;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.followers-menu-empty {
    text-align: center;
    padding: 32px 16px;
    color: #6b7280;
    font-size: 13px;
}

.followers-menu-empty svg {
    width: 32px;
    height: 32px;
    margin: 0 auto 8px;
    color: #d1d5db;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

@once
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const followersMenu = document.getElementById('followers-context-menu');
            const closeMenuBtn = document.getElementById('close-followers-menu');
            const menuList = document.getElementById('followers-menu-list');
            const menuTitle = document.getElementById('followers-menu-title');

            document.querySelectorAll('.comment-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    const licenseId = this.dataset.licenseId;
                    const commentsSection = document.querySelector(`.comments-section[data-license-id="${licenseId}"]`);
                    commentsSection.classList.toggle('hidden');
                });
            });

            document.querySelectorAll('.like-button').forEach(button => {
                button.addEventListener('click', async function(e) {
                    e.preventDefault();

                    const licenseId = this.dataset.licenseId;
                    const courseId = this.dataset.courseId;
                    const svg = this.querySelector('svg');
                    const countElement = this.querySelector('.like-count');

                    try {
                        const response = await fetch('{{ route("profile.trainingFeed.like") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                license_id: licenseId,
                                course_id: courseId
                            })
                        });

                        const data = await response.json();

                        if (data.success || data.action) {
                            const action = data.action;

                            if (action === 'liked') {
                                svg.classList.add('fill-red-500', 'text-red-500');
                                svg.setAttribute('fill', 'currentColor');
                                countElement.textContent = parseInt(countElement.textContent) + 1;
                                this.dataset.liked = 'true';

                                window.dispatchEvent(new CustomEvent('toast', {
                                    detail: {
                                        type: 'success',
                                        title: 'Post liked!'
                                    }
                                }));
                            } else {
                                svg.classList.remove('fill-red-500', 'text-red-500');
                                svg.setAttribute('fill', 'none');
                                countElement.textContent = parseInt(countElement.textContent) - 1;
                                this.dataset.liked = 'false';

                                window.dispatchEvent(new CustomEvent('toast', {
                                    detail: {
                                        type: 'info',
                                        title: 'Like removed'
                                    }
                                }));
                            }
                        } else {
                            throw new Error(data.error || 'Failed to update like');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'error',
                                title: error.message || 'Failed to update like. Please try again.'
                            }
                        }));
                    }
                });
            });

            document.querySelectorAll('.comment-form').forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const licenseId = this.dataset.licenseId;
                    const courseId = this.dataset.courseId;
                    const textarea = this.querySelector('.comment-input');
                    const submitBtn = this.querySelector('.comment-submit-btn');
                    const comment = textarea.value.trim();

                    if (!comment) return;

                    const originalBtnText = submitBtn.textContent;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Posting...';

                    try {
                        const response = await fetch('{{ route("profile.trainingFeed.comment") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                license_id: licenseId,
                                course_id: courseId,
                                comment: comment
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            textarea.value = '';

                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: {
                                    type: 'success',
                                    title: data.message || 'Comment posted successfully!'
                                }
                            }));

                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        } else {
                            throw new Error(data.error || 'Failed to post comment');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'error',
                                title: error.message || 'Failed to post comment. Please try again.'
                            }
                        }));

                        submitBtn.disabled = false;
                        submitBtn.textContent = originalBtnText;
                    }
                });
            });

            document.querySelectorAll('.followers-button').forEach(button => {
                button.addEventListener('click', async function(e) {
                    e.stopPropagation();

                    const userId = this.dataset.userId;
                    const userName = this.dataset.userName;
                    const rect = this.getBoundingClientRect();

                    const menuWidth = 300;
                    const menuHeight = 400;

                    let x = rect.left;
                    let y = rect.bottom + 5;

                    if (x + menuWidth > window.innerWidth) {
                        x = window.innerWidth - menuWidth - 10;
                    }
                    if (y + menuHeight > window.innerHeight) {
                        y = rect.top - menuHeight - 5;
                    }

                    followersMenu.style.left = x + 'px';
                    followersMenu.style.top = y + 'px';
                    followersMenu.style.display = 'flex';

                    menuTitle.textContent = `${userName}'s Followers`;

                    menuList.innerHTML = `
                        <div class="followers-menu-loading">
                            <svg class="followers-menu-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Loading...</span>
                        </div>
                    `;

                    try {
                        const response = await fetch(`/profile/trainingFeed/followers/${userId}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Failed to fetch followers');
                        }

                        const data = await response.json();

                        if (data.followers && data.followers.length > 0) {
                            menuList.innerHTML = data.followers.map(follower => `
                                <div class="follower-menu-item">
                                    <img src="${follower.profile_pic_avatar || '/default-avatar.png'}"
                                         alt="${follower.name}"
                                         class="follower-menu-avatar">
                                    <div class="follower-menu-info">
                                        <p class="follower-menu-name">${follower.name}</p>
                                        <p class="follower-menu-role">${follower.job_role || 'No role specified'}</p>
                                    </div>
                                </div>
                            `).join('');
                        } else {
                            menuList.innerHTML = `
                                <div class="followers-menu-empty">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p>No followers yet</p>
                                </div>
                            `;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        menuList.innerHTML = `
                            <div class="followers-menu-empty">
                                <p style="color: #ef4444;">Failed to load followers</p>
                            </div>
                        `;
                    }
                });
            });

            function closeFollowersMenu() {
                followersMenu.style.display = 'none';
            }

            closeMenuBtn.addEventListener('click', closeFollowersMenu);

            document.addEventListener('click', function(e) {
                if (!followersMenu.contains(e.target) && !e.target.closest('.followers-button')) {
                    closeFollowersMenu();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeFollowersMenu();
                }
            });

            followersMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
    @endpush
@endonce
