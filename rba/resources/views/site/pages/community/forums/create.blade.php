@extends('layouts.app')

@section('content')

    <div class="my-32 px-4">
        <div class="max-w-3xl mx-auto">
            
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-8">
                <h2 class="text-2xl font-bold blue-text mb-6">Create a New Forum</h2>

                <form id="create-forum-form">
                    @csrf

                    {{-- Forum Title --}}
                    <div class="mb-6">
                        <label for="title" class="block text-lg font-medium blue-text mb-2">
                            Forum Title *
                        </label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            required
                            maxlength="255"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#1f3b67] focus:border-transparent"
                            placeholder="e.g., Financial Planning Best Practices"
                        >
                        <p class="mt-1 text-lg text-gray-500">Choose a clear, descriptive title for your forum topic</p>
                    </div>

                    {{-- Forum Description --}}
                    <div class="mb-6">
                        <label for="description" class="block text-lg font-medium blue-text mb-2">
                            Description *
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            required
                            maxlength="1000"
                            rows="5"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#1f3b67] focus:border-transparent"
                            placeholder="Describe the purpose and topics to be discussed in this forum..."
                        ></textarea>
                        <p class="mt-1 text-lg text-gray-500">Explain what members will discuss in this forum (max 200 characters)</p>
                    </div>

                    {{-- Info Box --}}
                    <div class="mb-6 p-4 bg-blue-50 border-l-4 border-[#1f3b67] rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-[#1f3b67]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-bold text-[#1f3b67]">As Forum Creator, You Will:</h3>
                                <ul class="mt-2 text-lg text-[#1f3b67] list-disc list-inside space-y-1">
                                    <li>Approve or reject join requests from other users</li>
                                    <li>Moderate discussions and maintain forum quality</li>
                                    <li>Block users who post inappropriate content</li>
                                    <li>Be automatically added as a member</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('forums') }}" class="px-4 py-2 border border-gray-300 rounded text-lg font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button 
                            type="submit" 
                            id="submit-btn"
                            class="px-4 py-2 blue-bg text-white rounded text-lg font-medium hover:opacity-90 transition-opacity"
                        >
                            Create Forum
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('create-forum-form');
    const submitBtn = document.getElementById('submit-btn');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Disable button
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating...';

        const formData = new FormData(form);
        const data = {
            title: formData.get('title'),
            description: formData.get('description'),
        };

        try {
            const response = await fetch("{{ route('community.forums.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        type: 'success',
                        title: result.message
                    }
                }));

                // Redirect to forum
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 500);
            } else {
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        type: 'error',
                        title: result.error || 'Failed to create forum'
                    }
                }));

                submitBtn.disabled = false;
                submitBtn.textContent = 'Create Forum';
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
            submitBtn.textContent = 'Create Forum';
        }
    });
});
</script>
@endpush