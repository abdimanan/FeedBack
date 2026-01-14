<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clients') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openModal: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <button @click="$dispatch('open-modal', 'create-client')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add New Client
                        </button>
                    </div>

                    @if($clients->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($clients as $client)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $client->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->email }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($client->description, 50) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <button @click="$dispatch('open-modal', 'edit-client-{{ $client->id }}')" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                                <button @click="$dispatch('open-modal', 'delete-client-{{ $client->id }}')" class="text-red-600 hover:text-red-900">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $clients->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No clients found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Create Client Modal -->
        <x-modal name="create-client" maxWidth="lg">
            <form method="POST" action="{{ route('clients.store') }}" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-gray-900 mb-4">Create New Client</h2>

                <div class="mb-4">
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="flex justify-end space-x-3">
                    <x-secondary-button @click="$dispatch('close-modal', 'create-client')">Cancel</x-secondary-button>
                    <x-primary-button>Create Client</x-primary-button>
                </div>
            </form>
        </x-modal>

        <!-- Edit Client Modals -->
        @foreach($clients as $client)
            <x-modal name="edit-client-{{ $client->id }}" maxWidth="lg">
                <form method="POST" action="{{ route('clients.update', $client) }}" class="p-6">
                    @csrf
                    @method('PUT')
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Edit Client</h2>

                    <div class="mb-4">
                        <x-input-label for="edit-name-{{ $client->id }}" value="Name" />
                        <x-text-input id="edit-name-{{ $client->id }}" name="name" type="text" class="mt-1 block w-full" :value="old('name', $client->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="edit-email-{{ $client->id }}" value="Email" />
                        <x-text-input id="edit-email-{{ $client->id }}" name="email" type="email" class="mt-1 block w-full" :value="old('email', $client->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="edit-description-{{ $client->id }}" value="Description" />
                        <textarea id="edit-description-{{ $client->id }}" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3">{{ old('description', $client->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <x-secondary-button @click="$dispatch('close-modal', 'edit-client-{{ $client->id }}')">Cancel</x-secondary-button>
                        <x-primary-button>Update Client</x-primary-button>
                    </div>
                </form>
            </x-modal>
        @endforeach

        <!-- Delete Client Modals -->
        @foreach($clients as $client)
            <x-modal name="delete-client-{{ $client->id }}" maxWidth="md">
                <form method="POST" action="{{ route('clients.destroy', $client) }}" class="p-6">
                    @csrf
                    @method('DELETE')
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Delete Client</h2>
                    <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete "{{ $client->name }}"? This action cannot be undone.</p>

                    <div class="flex justify-end space-x-3">
                        <x-secondary-button @click="$dispatch('close-modal', 'delete-client-{{ $client->id }}')">Cancel</x-secondary-button>
                        <x-danger-button>Delete Client</x-danger-button>
                    </div>
                </form>
            </x-modal>
        @endforeach
    </div>
</x-app-layout>
