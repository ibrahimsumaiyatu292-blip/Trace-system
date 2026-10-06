<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('TRACE Dashboard') }}
            </h2>
            @hasanyrole('admin|Administrator')
                <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    📋 View Audit Log
                </a>
            @endhasanyrole
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Primary Metrics Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Total Assets Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Assets</p>
                            <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ number_format($totalAssets) }}</p>
                        </div>
                        <div class="p-3 bg-indigo-50 rounded-full text-indigo-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('assets.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                            Manage Assets →
                        </a>
                    </div>
                </div>

                <!-- Total Procurements Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Procurement Records</p>
                            <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ number_format($totalProcurements) }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('procurements.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-900">
                            View Procurements →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Procurement Status Breakdown -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Procurements Status Breakdown</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Pending -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                        <span class="px-3 py-1 text-xs font-semibold uppercase tracking-wider rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                        <p class="text-3xl font-bold text-yellow-900 mt-3">{{ number_format($procurementsByStatus['pending']) }}</p>
                        <p class="text-xs text-yellow-700 mt-1">Awaiting Approval</p>
                    </div>

                    <!-- Approved -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <span class="px-3 py-1 text-xs font-semibold uppercase tracking-wider rounded-full bg-green-100 text-green-800">
                            Approved
                        </span>
                        <p class="text-3xl font-bold text-green-900 mt-3">{{ number_format($procurementsByStatus['approved']) }}</p>
                        <p class="text-xs text-green-700 mt-1">Ready for Fulfillment</p>
                    </div>

                    <!-- Completed -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                        <span class="px-3 py-1 text-xs font-semibold uppercase tracking-wider rounded-full bg-blue-100 text-blue-800">
                            Completed
                        </span>
                        <p class="text-3xl font-bold text-blue-900 mt-3">{{ number_format($procurementsByStatus['completed']) }}</p>
                        <p class="text-xs text-blue-700 mt-1">Fulfilled & Received</p>
                    </div>
                </div>
            </div>

            <!-- Admin Banner Quick Link -->
            @hasanyrole('admin|Administrator')
                <div class="bg-indigo-900 text-white rounded-lg p-6 flex flex-col md:flex-row items-center justify-between shadow-md">
                    <div>
                        <h4 class="text-lg font-bold">Admin Audit Logging & Compliance</h4>
                        <p class="text-indigo-200 text-sm mt-1">Review system activity logs, record modifications, and user audit trails.</p>
                    </div>
                    <a href="{{ route('audit-logs.index') }}" class="mt-4 md:mt-0 inline-flex items-center px-5 py-2.5 bg-white text-indigo-900 font-semibold text-xs uppercase tracking-wider rounded-md hover:bg-indigo-50 transition ease-in-out duration-150">
                        Open Audit Logs
                    </a>
                </div>
            @endhasanyrole

        </div>
    </div>
</x-app-layout>
