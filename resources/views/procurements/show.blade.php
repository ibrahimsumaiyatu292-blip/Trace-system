<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Procurement Details') }} #{{ $procurement->id }}
            </h2>
            <a href="{{ route('procurements.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                ← Back to Procurements
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-medium">Project Name</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $procurement->project_name }}</p>
                        </div>

                        <div>
                            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-medium">Amount</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($procurement->amount, 2) }}</p>
                        </div>

                        <div>
                            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-medium">Status</h3>
                            <div class="mt-1">
                                @if ($procurement->status === 'approved')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Approved
                                    </span>
                                @elseif ($procurement->status === 'completed')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Completed
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-medium">Linked Asset</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                @if ($procurement->linkedAsset)
                                    <a href="{{ route('assets.show', $procurement->linkedAsset) }}" class="text-indigo-600 hover:underline">
                                        {{ $procurement->linkedAsset->name }}
                                    </a>
                                @else
                                    <span class="text-gray-400">None</span>
                                @endif
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-medium">Item Description</h3>
                            <p class="mt-1 text-base text-gray-800 bg-gray-50 p-4 rounded-md border border-gray-100 whitespace-pre-wrap">{{ $procurement->item_description }}</p>
                        </div>

                        <div>
                            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-medium">Recorded Date</h3>
                            <p class="mt-1 text-sm text-gray-700">{{ $procurement->created_at->format('M d, Y H:i A') }}</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                        @can('edit_asset')
                            <a href="{{ route('procurements.edit', $procurement) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 transition ease-in-out duration-150">
                                Edit Request
                            </a>
                        @endcan

                        @can('delete_asset')
                            <form action="{{ route('procurements.destroy', $procurement) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this procurement record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition ease-in-out duration-150">
                                    Delete Request
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
