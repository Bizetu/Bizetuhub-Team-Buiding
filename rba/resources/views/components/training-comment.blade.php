@props(['comment', 'license'])

<div class="training-comment" data-comment-id="{{ $comment->id }}">
    <div class="flex gap-3">
        <img src="{{ $comment->user->profile_pic_avatar }}" 
             alt="{{ $comment->user->name }}"
             class="w-10 h-10 rounded-full object-cover flex-shrink-0" />
        
        <div class="flex-1 min-w-0">
            <div class="bg-gray-50 rounded-lg px-4 py-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-semibold text-lg text-gray-900">{{ $comment->user->name }}</span>
                    <span class="text-base text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                
                <p class="text-lg text-gray-700 break-words">{{ $comment->message }}</p>
            </div>
            
            {{-- Reply Button --}}
            <div class="mt-2 flex items-center gap-4 text-base">
                @if($comment->replies->count() > 0)
                    <button class="reply-toggle text-blue-600 hover:text-blue-700 font-medium" 
                            data-comment-id="{{ $comment->id }}">
                        {{ $comment->replies->count() }} {{ Str::plural('Reply', $comment->replies->count()) }}
                    </button>
                @endif
                
                <button class="show-reply-form text-gray-600 hover:text-gray-700 font-medium" 
                        data-comment-id="{{ $comment->id }}">
                    Reply
                </button>
            </div>
            
            {{-- Replies --}}
            @if($comment->replies->count() > 0)
                <div class="replies hidden mt-3 space-y-3 pl-4 border-l-2 border-gray-200" data-comment-id="{{ $comment->id }}">
                    @foreach($comment->replies as $reply)
                        <div class="flex gap-2">
                            <img src="{{ $reply->user->profile_pic_avatar }}" 
                                 alt="{{ $reply->user->name }}"
                                 class="w-8 h-8 rounded-full object-cover flex-shrink-0" />
                            
                            <div class="flex-1 min-w-0">
                                <div class="bg-gray-50 rounded-lg px-3 py-2">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-semibold text-base text-gray-900">{{ $reply->user->name }}</span>
                                        <span class="text-base text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-base text-gray-700 break-words">{{ $reply->message }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
            {{-- Reply Form --}}
            <div class="reply-form hidden mt-3" data-comment-id="{{ $comment->id }}">
                <form class="add-reply-form" 
                      data-license-id="{{ $license->id }}" 
                      data-course-id="{{ $license->course_id }}"
                      data-parent-id="{{ $comment->id }}">
                    @csrf
                    <div class="flex gap-2">
                        <img src="{{ Auth::user()->profile_pic_avatar }}" 
                             alt="{{ Auth::user()->name }}"
                             class="w-8 h-8 rounded-full object-cover flex-shrink-0" />
                        <div class="flex-1">
                            <textarea class="reply-input w-full px-3 py-2 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1f3b67] focus:border-transparent resize-none" 
                                      name="comment" 
                                      placeholder="Write a reply..." 
                                      maxlength="500"
                                      rows="2"
                                      required></textarea>
                            <div class="mt-2 flex justify-end gap-2">
                                <button type="button" class="cancel-reply px-3 py-1.5 text-lg text-gray-700 hover:text-gray-900">
                                    Cancel
                                </button>
                                <button type="submit" class="px-3 py-1.5 bg-[#1f3b67] text-white text-lg font-medium rounded-lg transition-colors">
                                    Reply
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
    <script>
        $(document).ready(function() {
            // Toggle replies visibility
            $('.reply-toggle').on('click', function() {
                const commentId = $(this).data('comment-id');
                $(`.replies[data-comment-id="${commentId}"]`).toggleClass('hidden');
            });
            
            // Show reply form
            $('.show-reply-form').on('click', function() {
                const commentId = $(this).data('comment-id');
                $(`.reply-form[data-comment-id="${commentId}"]`).toggleClass('hidden');
            });
            
            // Cancel reply
            $('.cancel-reply').on('click', function() {
                $(this).closest('.reply-form').addClass('hidden');
                $(this).closest('.reply-form').find('.reply-input').val('');
            });
            
            // Submit reply
            $('.add-reply-form').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const licenseId = $form.data('license-id');
                const courseId = $form.data('course-id');
                const parentId = $form.data('parent-id');
                const $textarea = $form.find('.reply-input');
                const comment = $textarea.val().trim();

                if (!comment) return;

                $.ajax({
                    url: '{{ route("profile.trainingFeed.comment") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        license_id: licenseId,
                        course_id: courseId,
                        parent_comment_id: parentId,
                        comment: comment
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Failed to post reply. Please try again.');
                    }
                });
            });
        });
    </script>
    @endpush
@endonce