<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feedback') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($feedbacks->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overall Rating</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($feedbacks as $feedback)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $feedback->project->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $feedback->project->client->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="flex items-center">
                                                    <span class="mr-2">{{ $feedback->overall_rating }}/5</span>
                                                    <div class="flex">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $feedback->overall_rating)
                                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                            @else
                                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $feedback->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="loadFeedback({{ $feedback->id }})" class="text-indigo-600 hover:text-indigo-900">View</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $feedbacks->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No feedback found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- View Feedback Modal -->
    <x-modal name="view-feedback" maxWidth="2xl">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Feedback Details</h2>
            <div id="feedback-details" class="space-y-4">
                <!-- Content will be loaded here -->
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button @click="$dispatch('close-modal', 'view-feedback')">Close</x-secondary-button>
            </div>
        </div>
    </x-modal>

    <script>
        window.loadFeedback = function(feedbackId) {
            fetch(`/feedback/${feedbackId}/view`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const details = document.getElementById('feedback-details');
                    details.innerHTML = `
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Project</p>
                                <p class="text-sm text-gray-900">${escapeHtml(data.project)}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Client</p>
                                <p class="text-sm text-gray-900">${escapeHtml(data.client)}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Date</p>
                                <p class="text-sm text-gray-900">${escapeHtml(data.created_at)}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Overall Rating</p>
                                <p class="text-sm text-gray-900">${data.overall_rating}/5</p>
                            </div>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500 mb-2">Statement 1: Service quality met expectations</p>
                            <p class="text-sm text-gray-900">${data.statement_1_rating}/5</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500 mb-2">Statement 2: Communication was clear and professional</p>
                            <p class="text-sm text-gray-900">${data.statement_2_rating}/5</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500 mb-2">Statement 3: Delivery time was satisfactory</p>
                            <p class="text-sm text-gray-900">${data.statement_3_rating}/5</p>
                        </div>
                        ${data.likes_text ? `
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500 mb-2">What did you like about the service?</p>
                            <p class="text-sm text-gray-900">${escapeHtml(data.likes_text)}</p>
                        </div>
                        ` : ''}
                        ${data.dislikes_text ? `
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500 mb-2">What did you dislike or think could improve?</p>
                            <p class="text-sm text-gray-900">${escapeHtml(data.dislikes_text)}</p>
                        </div>
                        ` : ''}
                    `;
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'view-feedback' }));
                })
                .catch(error => {
                    console.error('Error loading feedback:', error);
                    alert('Failed to load feedback details.');
                });
        };

        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return String(text).replace(/[&<>"']/g, m => map[m]);
        }
    </script>
</x-app-layout>
