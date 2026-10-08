<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight">
                    {{ __('GCTU Executive Dashboard') }}
                </h2>
                <p class="text-xs text-slate-500 font-medium">TRACE — Asset & Procurement Management System</p>
            </div>
            <div class="flex items-center space-x-3">
                @hasrole('admin')
                    <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-950 border border-blue-900 rounded font-semibold text-xs text-amber-400 uppercase tracking-wider hover:bg-blue-900 focus:outline-none shadow-sm transition">
                        <svg class="w-4 h-4 mr-1.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        System Audit Log
                    </a>
                @endhasanyrole
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Institutional Welcome Banner -->
            <div class="bg-blue-950 rounded-xl p-6 text-white border border-blue-900 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="space-y-2">
                    <div class="inline-flex items-center space-x-2 px-2.5 py-0.5 rounded bg-amber-500/20 text-amber-300 text-xs font-semibold">
                        <span>Institutional Asset Control</span>
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        Welcome back, {{ Auth::user()->name }}
                    </h1>
                    <p class="text-sm text-slate-300 max-w-2xl leading-relaxed">
                        Ghana Communication Technology University asset tracking and procurement lifecycle portal. Manage inventory, monitor project expenditure, and maintain institutional accountability.
                    </p>
                </div>
                <div class="shrink-0 bg-blue-900/80 border border-blue-800 rounded-lg p-4 text-right hidden lg:block">
                    <div class="text-xs text-slate-300 font-medium">Logged in Role</div>
                    <div class="text-sm font-bold text-amber-400 uppercase tracking-wide mt-0.5">
                        {{ Auth::user()->roles->pluck('name')->first() ?? 'Staff Member' }}
                    </div>
                </div>
            </div>

            <!-- Primary Metrics Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Total Assets Card -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 hover:border-slate-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Managed Assets</span>
                            <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalAssets) }}</h3>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-500">Tracked Institutional Inventory</span>
                        <a href="{{ route('assets.index') }}" class="inline-flex items-center text-xs font-bold text-blue-900 hover:text-blue-700 transition">
                            Explore Assets &rarr;
                        </a>
                    </div>
                </div>

                <!-- Total Procurements Card -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 hover:border-slate-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Procurement Requests</span>
                            <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalProcurements) }}</h3>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-500">Project Expenditure Records</span>
                        <a href="{{ route('procurements.index') }}" class="inline-flex items-center text-xs font-bold text-amber-700 hover:text-amber-900 transition">
                            View Procurements &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Procurement Status Breakdown -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Procurements Status Breakdown</h3>
                    <p class="text-xs text-slate-500">Distribution of project procurement requests across fulfillment stages</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Pending -->
                    <div class="bg-amber-50/80 border border-amber-200 rounded-lg p-5">
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 text-xs font-bold uppercase rounded bg-amber-200 text-amber-900">
                                Pending
                            </span>
                        </div>
                        <div class="mt-3">
                            <p class="text-3xl font-extrabold text-amber-950">{{ number_format($procurementsByStatus['pending']) }}</p>
                            <p class="text-xs font-medium text-amber-800 mt-1">Awaiting Review & Approval</p>
                        </div>
                    </div>

                    <!-- Approved -->
                    <div class="bg-emerald-50/80 border border-emerald-200 rounded-lg p-5">
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 text-xs font-bold uppercase rounded bg-emerald-200 text-emerald-900">
                                Approved
                            </span>
                        </div>
                        <div class="mt-3">
                            <p class="text-3xl font-extrabold text-emerald-950">{{ number_format($procurementsByStatus['approved']) }}</p>
                            <p class="text-xs font-medium text-emerald-800 mt-1">Authorized for Purchasing</p>
                        </div>
                    </div>

                    <!-- Completed -->
                    <div class="bg-blue-50/80 border border-blue-200 rounded-lg p-5">
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 text-xs font-bold uppercase rounded bg-blue-200 text-blue-900">
                                Completed
                            </span>
                        </div>
                        <div class="mt-3">
                            <p class="text-3xl font-extrabold text-blue-950">{{ number_format($procurementsByStatus['completed']) }}</p>
                            <p class="text-xs font-medium text-blue-800 mt-1">Fulfilled & Received Assets</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Navigation Banner -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('assets.index') }}" class="bg-slate-900 hover:bg-slate-800 p-6 rounded-xl text-white shadow-sm border border-slate-800 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Assets Module</span>
                    </div>
                    <h4 class="text-base font-bold mt-2">Manage Asset Inventory</h4>
                    <p class="text-xs text-slate-300 mt-1">Record, track, and update physical hardware and campus assets.</p>
                </a>

                <a href="{{ route('procurements.index') }}" class="bg-slate-900 hover:bg-slate-800 p-6 rounded-xl text-white shadow-sm border border-slate-800 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Procurements Module</span>
                    </div>
                    <h4 class="text-base font-bold mt-2">Track Expenditures</h4>
                    <p class="text-xs text-slate-300 mt-1">Log project procurement requests, costs, and approval statuses.</p>
                </a>

                @hasrole('admin')
                    <a href="{{ route('users.manage') }}" class="bg-blue-950 hover:bg-blue-900 p-6 rounded-xl text-white shadow-sm border border-blue-900 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Admin Control</span>
                        </div>
                        <h4 class="text-base font-bold mt-2">User & Role Management</h4>
                        <p class="text-xs text-slate-300 mt-1">Manage institutional account roles and access permissions.</p>
                    </a>
                @else
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Security</span>
                        <h4 class="text-base font-bold text-slate-800 mt-2">Role-Based Access</h4>
                        <p class="text-xs text-slate-500 mt-1">Your account is active under {{ Auth::user()->roles->pluck('name')->first() ?? 'Viewer' }} permissions.</p>
                    </div>
                @endhasanyrole
            </div>

        </div>
    </div>
</x-app-layout>
