<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Asset Details') }}: {{ $asset->name }}
            </h2>
            <a href="{{ route('assets.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                ← Back to Assets
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-medium">Asset Name</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $asset->name }}</p>
                        </div>

                        <div>
                            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-medium">Category</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $asset->category }}</p>
                        </div>

                        <div>
                            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-medium">Value</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($asset->value, 2) }}</p>
                        </div>

                        <div>
                            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-medium">Assigned Project</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $asset->assigned_project ?? 'Unassigned' }}</p>
                        </div>

                        <div>
                            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-medium">Status</h3>
                            <span class="mt-1 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $asset->status }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-medium">Recorded Date</h3>
                            <p class="mt-1 text-sm text-gray-700">{{ $asset->created_at->format('M d, Y H:i A') }}</p>
                        </div>

                        <div>
                            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-medium">Created By</h3>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $asset->creator?->name ?? 'System' }}</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                        @can('update', $asset)
                            <a href="{{ route('assets.edit', $asset) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 transition ease-in-out duration-150">
                                Edit Asset
                            </a>
                        @endcan

                        @can('delete_asset')
                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this asset?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition ease-in-out duration-150">
                                    Delete Asset
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
