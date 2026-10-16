@props(['user', 'currentUser'])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
    <div class="p-6">
        {{-- Profile Image --}}
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-4">
                <img 
                    src="{{ $user->profile_pic_url }}" 
                    alt="{{ $user->name_value }}"
                    class="w-16 h-16 rounded-full object-cover border-2 border-gray-100"
                >
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ $user->name_value }}</h3>
                    @if($user->company)
                        <p class="text-lg text-gray-600">{{ $user->company->title }}</p>
                    @endif
                    @if($user->jobRole)
                        <p class="text-base text-gray-500">{{ $user->jobRole->title }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex space-x-2 mt-4">
            @if($user->is_following)
                {{-- Unfollow Button --}}
                <button 
                    data-user-id="{{ $user->id }}"
                    data-action="unfollow"
                    class="follow-btn flex-1 px-4 py-2 border border-gray-300 rounded text-lg font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                >
                    Following
                </button>

                {{-- Message Button --}}
                <a 
                    href="{{ route('community.messages.show', $user->id) }}"
                    class="flex-1 px-4 py-2 bg-black text-white rounded text-lg font-medium text-center hover:bg-gray-800 transition-colors"
                >
                    Message
                </a>
            @elseif($user->follow_pending)
                {{-- Pending Button --}}
                <button 
                    disabled
                    class="flex-1 px-4 py-2 border border-gray-300 rounded text-lg font-medium text-gray-500 cursor-not-allowed"
                >
                    Pending
                </button>
            @else
                {{-- Follow Button --}}
                <button 
                    data-user-id="{{ $user->id }}"
                    data-action="follow"
                    class="follow-btn flex-1 px-4 py-2 bg-[#1F3B67] text-white rounded text-lg font-medium transition-colors"
                >
                    Follow
                </button>
            @endif
        </div>

        {{-- Mutual Follow Badge --}}
        @if($user->is_following && $user->follows_back)
            <div class="mt-3 flex items-center text-base text-gray-500">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                You follow each other
            </div>
        @endif
    </div>
</div>