<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openModal: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('status') }}
                    @if (session('feedback_url'))
                        <div class="mt-2">
                            <strong>Feedback URL:</strong>
                            <div class="mt-1 flex items-center gap-2">
                                <input type="text" value="{{ session('feedback_url') }}" readonly class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-sm" id="feedback-url-{{ session('project_id') ?? '' }}">
                                <button onclick="copyToClipboard('feedback-url-{{ session('project_id') ?? '' }}')" class="px-3 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700">Copy</button>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <button @click="$dispatch('open-modal', 'create-project')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add New Project
                        </button>
                    </div>

                    @if($projects->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($projects as $project)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $project->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $project->client->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $project->start_date->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($project->description, 50) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <button @click="$dispatch('open-modal', 'edit-project-{{ $project->id }}')" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                                <button @click="$dispatch('open-modal', 'delete-project-{{ $project->id }}')" class="text-red-600 hover:text-red-900">Delete</button>
                                                <form method="POST" action="{{ route('projects.generate-feedback-link', $project) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900">Generate Feedback Link</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $projects->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No projects found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Create Project Modal -->
        <x-modal name="create-project" maxWidth="lg">
            <form method="POST" action="{{ route('projects.store') }}" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-gray-900 mb-4">Create New Project</h2>

                <div class="mb-4">
                    <x-input-label for="client_id" value="Client" />
                    <select id="client_id" name="client_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">Select a client</option>
                        @foreach(\App\Models\Client::all() as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="start_date" value="Start Date" />
                    <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date')" required />
                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="flex justify-end space-x-3">
                    <x-secondary-button @click="$dispatch('close-modal', 'create-project')">Cancel</x-secondary-button>
                    <x-primary-button>Create Project</x-primary-button>
                </div>
            </form>
        </x-modal>

        <!-- Edit Project Modals -->
        @foreach($projects as $project)
            <x-modal name="edit-project-{{ $project->id }}" maxWidth="lg">
                <form method="POST" action="{{ route('projects.update', $project) }}" class="p-6">
                    @csrf
                    @method('PUT')
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Edit Project</h2>

                    <div class="mb-4">
                        <x-input-label for="edit-client_id-{{ $project->id }}" value="Client" />
                        <select id="edit-client_id-{{ $project->id }}" name="client_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Select a client</option>
                            @foreach(\App\Models\Client::all() as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="edit-name-{{ $project->id }}" value="Name" />
                        <x-text-input id="edit-name-{{ $project->id }}" name="name" type="text" class="mt-1 block w-full" :value="old('name', $project->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="edit-start_date-{{ $project->id }}" value="Start Date" />
                        <x-text-input id="edit-start_date-{{ $project->id }}" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date', $project->start_date->format('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="edit-description-{{ $project->id }}" value="Description" />
                        <textarea id="edit-description-{{ $project->id }}" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3">{{ old('description', $project->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <x-secondary-button @click="$dispatch('close-modal', 'edit-project-{{ $project->id }}')">Cancel</x-secondary-button>
                        <x-primary-button>Update Project</x-primary-button>
                    </div>
                </form>
            </x-modal>
        @endforeach

        <!-- Delete Project Modals -->
        @foreach($projects as $project)
            <x-modal name="delete-project-{{ $project->id }}" maxWidth="md">
                <form method="POST" action="{{ route('projects.destroy', $project) }}" class="p-6">
                    @csrf
                    @method('DELETE')
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Delete Project</h2>
                    <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete "{{ $project->name }}"? This action cannot be undone.</p>

                    <div class="flex justify-end space-x-3">
                        <x-secondary-button @click="$dispatch('close-modal', 'delete-project-{{ $project->id }}')">Cancel</x-secondary-button>
                        <x-danger-button>Delete Project</x-danger-button>
                    </div>
                </form>
            </x-modal>
        @endforeach
    </div>

    <script>
        function copyToClipboard(elementId) {
            const input = document.getElementById(elementId);
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices
            navigator.clipboard.writeText(input.value).then(() => {
                alert('URL copied to clipboard!');
            });
        }
    </script>
</x-app-layout>
