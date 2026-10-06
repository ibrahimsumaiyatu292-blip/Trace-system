<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Procurement Request') }} #{{ $procurement->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('procurements.update', $procurement) }}">
                        @csrf
                        @method('PUT')

                        <!-- Project Name -->
                        <div class="mb-4">
                            <x-input-label for="project_name" :value="__('Project Name')" />
                            <x-text-input id="project_name" class="block mt-1 w-full" type="text" name="project_name" :value="old('project_name', $procurement->project_name)" required autofocus />
                            <x-input-error :messages="$errors->get('project_name')" class="mt-2" />
                        </div>

                        <!-- Item Description -->
                        <div class="mb-4">
                            <x-input-label for="item_description" :value="__('Item Description')" />
                            <textarea id="item_description" name="item_description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('item_description', $procurement->item_description) }}</textarea>
                            <x-input-error :messages="$errors->get('item_description')" class="mt-2" />
                        </div>

                        <!-- Amount -->
                        <div class="mb-4">
                            <x-input-label for="amount" :value="__('Estimated Amount ($)')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" min="0" name="amount" :value="old('amount', $procurement->amount)" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <!-- Linked Asset -->
                        <div class="mb-4">
                            <x-input-label for="linked_asset_id" :value="__('Linked Asset (Optional)')" />
                            <select id="linked_asset_id" name="linked_asset_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- Select Linked Asset (None) --</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}" {{ old('linked_asset_id', $procurement->linked_asset_id) == $asset->id ? 'selected' : '' }}>
                                        {{ $asset->name }} ({{ $asset->category }}) - ${{ number_format($asset->value, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('linked_asset_id')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="pending" {{ old('status', $procurement->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status', $procurement->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="completed" {{ old('status', $procurement->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('procurements.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Procurement') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
