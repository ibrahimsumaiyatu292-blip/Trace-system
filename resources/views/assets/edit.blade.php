<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Asset: ') }} {{ $asset->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('assets.update', $asset) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Asset Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $asset->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <x-input-label for="category" :value="__('Category')" />
                            <x-text-input id="category" class="block mt-1 w-full" type="text" name="category" :value="old('category', $asset->category)" required />
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <!-- Value -->
                        <div class="mb-4">
                            <x-input-label for="value" :value="__('Value ($)')" />
                            <x-text-input id="value" class="block mt-1 w-full" type="number" step="0.01" min="0" name="value" :value="old('value', $asset->value)" required />
                            <x-input-error :messages="$errors->get('value')" class="mt-2" />
                        </div>

                        <!-- Assigned Project -->
                        <div class="mb-4">
                            <x-input-label for="assigned_project" :value="__('Assigned Project (Optional)')" />
                            <x-text-input id="assigned_project" class="block mt-1 w-full" type="text" name="assigned_project" :value="old('assigned_project', $asset->assigned_project)" />
                            <x-input-error :messages="$errors->get('assigned_project')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="Available" {{ old('status', $asset->status) == 'Available' ? 'selected' : '' }}>Available</option>
                                <option value="In Use" {{ old('status', $asset->status) == 'In Use' ? 'selected' : '' }}>In Use</option>
                                <option value="Under Maintenance" {{ old('status', $asset->status) == 'Under Maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                <option value="Decommissioned" {{ old('status', $asset->status) == 'Decommissioned' ? 'selected' : '' }}>Decommissioned</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('assets.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Asset') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
