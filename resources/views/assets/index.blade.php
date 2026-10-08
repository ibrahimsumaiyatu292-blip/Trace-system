<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 tracking-tight">
                    {{ __('Asset Management') }}
                </h2>
                <p class="text-xs text-slate-500 font-medium">Institutional asset tracking and inventory records</p>
            </div>
            @can('create_asset')
                <a href="{{ route('assets.create') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-900 to-indigo-950 border border-indigo-800 rounded-xl font-bold text-xs text-amber-400 uppercase tracking-wider hover:from-indigo-950 hover:to-slate-900 focus:outline-none shadow-md shadow-indigo-950/20 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add New Asset
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-300 text-emerald-900 px-5 py-4 rounded-xl shadow-sm flex items-center justify-between" role="alert">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-sm font-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="p-6">
                    @if ($assets->isEmpty())
                        <div class="text-center py-12">
                            <div class="h-16 w-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">No Assets Recorded</h3>
                            <p class="text-xs text-slate-500 mt-1">There are no institutional assets in the repository yet.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead>
                                    <tr class="bg-slate-50/80">
                                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Asset Name</th>
                                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Value ($)</th>
                                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Project</th>
                                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3.5 text-right text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach ($assets as $asset)
                                        <tr class="hover:bg-slate-50/60 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-400">#{{ $asset->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-extrabold text-slate-900">{{ $asset->name }}</div>
                                                <div class="text-[11px] text-slate-400">Created by: {{ $asset->creator?->name ?? 'System' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700">
                                                    {{ $asset->category }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-slate-900">${{ number_format($asset->value, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-slate-600">{{ $asset->assigned_project ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                @if ($asset->status === 'Available')
                                                    <span class="px-2.5 py-1 inline-flex text-[11px] font-extrabold rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                        Available
                                                    </span>
                                                @elseif ($asset->status === 'In Use')
                                                    <span class="px-2.5 py-1 inline-flex text-[11px] font-extrabold rounded-md bg-blue-50 text-blue-700 border border-blue-200/60">
                                                        In Use
                                                    </span>
                                                @elseif ($asset->status === 'Under Maintenance')
                                                    <span class="px-2.5 py-1 inline-flex text-[11px] font-extrabold rounded-md bg-amber-50 text-amber-700 border border-amber-200/60">
                                                        Under Maintenance
                                                    </span>
                                                @else
                                                    <span class="px-2.5 py-1 inline-flex text-[11px] font-extrabold rounded-md bg-slate-100 text-slate-600 border border-slate-200">
                                                        {{ $asset->status }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-semibold space-x-2">
                                                <a href="{{ route('assets.show', $asset) }}" class="px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">View</a>

                                                @can('update', $asset)
                                                    <a href="{{ route('assets.edit', $asset) }}" class="px-2.5 py-1 rounded-md bg-amber-50 text-amber-700 hover:bg-amber-100 transition">Edit</a>
                                                @endcan

                                                @can('delete_asset')
                                                    <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this asset?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-2.5 py-1 rounded-md bg-red-50 text-red-600 hover:bg-red-100 transition border-0 cursor-pointer">Delete</button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $assets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
