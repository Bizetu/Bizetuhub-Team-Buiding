@extends('layouts.app')

@section('content')

    <x-hero 
        title="Messages"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'My Community', 'url' => route('community.index')],
            ['label' => 'Messages', 'url' => route('community.messages.index')],
            ['label' => $targetUser->name_value, 'url' => route('community.messages.show', $targetUser->id)],
        ]"
        :backgroundImage="$pageItem?->image('hero_image') ?? asset('images/defaults/banner.webp')"
        height="h-80"
    />

    <div class="my-2 px-4 min-h-[35vh] lg:min-h-[379px]">
        <div class="max-w-4xl mx-auto">
            
            {{-- Conversation Header --}}
            <div class="bg-white rounded-t-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center space-x-4">
                    <img 
                        src="{{ $targetUser->profile_pic_avatar }}" 
                        alt="{{ $targetUser->name_value }}"
                        class="w-12 h-12 rounded-full object-cover border-2 border-gray-100"
                    >
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $targetUser->name_value }}</h3>
                        @if($targetUser->company)
                            <p class="text-lg text-gray-600">{{ $targetUser->company_name_value }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Messages Container --}}
            <div id="messages-container" class="bg-white border-x border-gray-200 p-4 space-y-4 overflow-y-auto" style="height: 500px;">
                @forelse($messages as $message)
                    @if($message->sender_id === $user->id)
                        {{-- Sent Message --}}
                        <div class="flex justify-end">
                            <div class="max-w-xs lg:max-w-md">
                                <div class="blue-bg rounded-2xl px-4 py-2">
                                    <p class="text-lg text-white">{{ $message->message }}</p>
                                </div>
                                <p class="text-base text-gray-500 mt-1 text-right">
                                    {{ $message->created_at->format('M d, Y H:i') }}
                                </p>
                            </div>
                        </div>
                    @else
                        {{-- Received Message --}}
                        <div class="flex justify-start">
                            <div class="max-w-xs lg:max-w-md">
                                <div class="subtle-gray-bg rounded-2xl px-4 py-2">
                                    <p class="text-lg text-gray-800">{{ $message->message }}</p>
                                </div>
                                <p class="text-base text-gray-500 mt-1">
                                    {{ $message->created_at->format('M d, Y H:i') }}
                                </p>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">No messages yet. Start the conversation!</p>
                    </div>
                @endforelse
            </div>

            {{-- Message Input --}}
            <div class="bg-white rounded-b-lg shadow-sm border border-gray-200 p-4">
                <form id="message-form" class="flex space-x-3">
                    @csrf
                    <input 
                        type="text" 
                        id="message-input"
                        name="message"
                        placeholder="Type your message..."
                        class="flex-1 border border-gray-300 subtle-gray-bg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
                        required
                        maxlength="200"
                    >
                    <button 
                        type="submit"
                        class="px-6 py-2 blue-bg text-white hover:bg-[#FFB901] transition-colors font-medium"
                    >
                        Send
                    </button>
                </form>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messages-container');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    
    // Scroll to bottom of messages
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    scrollToBottom();

    // Handle message form submission
    messageForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Sending...';

        try {
            const response = await fetch('{{ route("community.messages.send", $targetUser->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();

            if (data.success) {
                // Add message to UI
                const messageHtml = `
                    <div class="flex justify-end">
                        <div class="max-w-xs lg:max-w-md">
                            <div class="bg-[#FFB901] text-white rounded-lg px-4 py-2">
                                <p class="text-lg  text-white">${data.data.message}</p>
                            </div>
                            <p class="text-base text-gray-500 mt-1 text-right">
                                ${data.data.created_at}
                            </p>
                        </div>
                    </div>
                `;
                
                messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                messageInput.value = '';
                scrollToBottom();
            } else {
                alert(data.error || 'Failed to send message');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'Send';
        }
    });
});
</script>
@endpush