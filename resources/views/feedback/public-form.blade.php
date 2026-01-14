<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Project Feedback</h2>
        <p class="text-gray-600">Project: <strong>{{ $project->name }}</strong></p>
        <p class="text-sm text-gray-500">Client: {{ $project->client->name }}</p>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('feedback.submit', $feedbackLink->token) }}" id="feedback-form">
        @csrf

        <!-- Rating Statements Section -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">⭐ Rating Statements</h3>
            
            <!-- Statement 1 -->
            <div class="mb-6">
                <x-input-label for="statement_1_rating" value="Statement 1: Service quality met expectations" />
                <input type="number" name="statement_1_rating" id="statement_1_rating" min="1" max="5" step="1" value="{{ old('statement_1_rating') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <x-input-error :messages="$errors->get('statement_1_rating')" class="mt-2" />
            </div>

            <!-- Statement 2 -->
            <div class="mb-6">
                <x-input-label for="statement_2_rating" value="Statement 2: Communication was clear and professional" />
                <input type="number" name="statement_2_rating" id="statement_2_rating" min="1" max="5" step="1" value="{{ old('statement_2_rating') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <x-input-error :messages="$errors->get('statement_2_rating')" class="mt-2" />
            </div>

            <!-- Statement 3 -->
            <div class="mb-6">
                <x-input-label for="statement_3_rating" value="Statement 3: Delivery time was satisfactory" />
                <input type="number" name="statement_3_rating" id="statement_3_rating" min="1" max="5" step="1" value="{{ old('statement_3_rating') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <x-input-error :messages="$errors->get('statement_3_rating')" class="mt-2" />
            </div>
        </div>

        <!-- Text Feedback Section -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">✍️ Text Feedback</h3>
            
            <div class="mb-6">
                <x-input-label for="likes_text" value="What did you like about the service?" />
                <textarea id="likes_text" name="likes_text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="4" placeholder="Tell us what you liked about the service...">{{ old('likes_text') }}</textarea>
                <x-input-error :messages="$errors->get('likes_text')" class="mt-2" />
            </div>

            <div class="mb-6">
                <x-input-label for="dislikes_text" value="What did you dislike or think could improve?" />
                <textarea id="dislikes_text" name="dislikes_text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="4" placeholder="Tell us what could be improved...">{{ old('dislikes_text') }}</textarea>
                <x-input-error :messages="$errors->get('dislikes_text')" class="mt-2" />
            </div>
        </div>

        <!-- Final Rating Section -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">⭐ Final Rating</h3>
            
            <div class="mb-6">
                <x-input-label for="overall_rating" value="Overall satisfaction (1–5)" />
                <input type="number" name="overall_rating" id="overall_rating" min="1" max="5" step="1" value="{{ old('overall_rating') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <x-input-error :messages="$errors->get('overall_rating')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end">
            <x-primary-button>
                Submit Feedback
            </x-primary-button>
        </div>
    </form>

</x-guest-layout>
