<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 tracking-tight">
                    {{ __('System Audit Trail') }}
                </h2>
                <p class="text-xs text-slate-500 font-medium">Real-time record of system actions, asset operations, and user changes</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 border border-slate-200 rounded-xl font-bold text-xs text-slate-700 uppercase tracking-wider hover:bg-slate-200 transition ease-in-out duration-150">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="p-6">
                    @if ($logs->isEmpty())
                        <div class="text-center py-12">
                            <div class="h-16 w-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">No Audit Logs</h3>
                            <p class="text-xs text-slate-500 mt-1">There are no audit events recorded in the system log yet.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead>
                                    <tr class="bg-slate-50/80">
                                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Log ID</th>
                                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">User Account</th>
                                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Action Performed</th>
                                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Target Module</th>
                                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Record ID</th>
                                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">IP Address</th>
                                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach ($logs as $log)
                                        <tr class="hover:bg-slate-50/60 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-400">#{{ $log->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-xs font-extrabold text-slate-900">{{ $log->user ? $log->user->name : 'System' }}</div>
                                                <div class="text-[11px] text-slate-400">{{ $log->user ? $log->user->email : '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                @if ($log->action === 'created')
                                                    <span class="px-2.5 py-1 inline-flex text-[10px] font-black tracking-wider uppercase rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        CREATED
                                                    </span>
                                                @elseif ($log->action === 'updated')
                                                    <span class="px-2.5 py-1 inline-flex text-[10px] font-black tracking-wider uppercase rounded-md bg-amber-50 text-amber-700 border border-amber-200">
                                                        UPDATED
                                                    </span>
                                                @elseif ($log->action === 'deleted')
                                                    <span class="px-2.5 py-1 inline-flex text-[10px] font-black tracking-wider uppercase rounded-md bg-red-50 text-red-700 border border-red-200">
                                                        DELETED
                                                    </span>
                                                @else
                                                    <span class="px-2.5 py-1 inline-flex text-[10px] font-black tracking-wider uppercase rounded-md bg-purple-50 text-purple-700 border border-purple-200">
                                                        {{ strtoupper($log->action) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-700 capitalize">{{ $log->target_table ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-mono">#{{ $log->target_id ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-mono">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $logs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
