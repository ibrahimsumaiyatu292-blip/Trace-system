<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight">
                    {{ __('User & Role Control Panel') }}
                </h2>
                <p class="text-xs text-slate-500 font-medium">Institutional accounts, role assignments, and permission controls</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 border border-slate-200 rounded font-semibold text-xs text-slate-700 uppercase tracking-wider hover:bg-slate-200 transition">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-300 text-emerald-900 px-5 py-4 rounded-lg shadow-sm flex items-center justify-between" role="alert">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-sm font-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-rose-50 border border-rose-300 text-rose-900 px-5 py-4 rounded-lg shadow-sm flex items-center justify-between" role="alert">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded bg-rose-100 flex items-center justify-center text-rose-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <span class="text-sm font-semibold">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-300 text-rose-900 px-5 py-4 rounded-lg shadow-sm">
                    <div class="font-bold text-sm mb-1">Please correct the following errors:</div>
                    <ul class="list-disc list-inside text-xs space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Admin Staff Account Creation Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-base font-bold text-slate-900 mb-1">Create New Staff Account</h3>
                <p class="text-xs text-slate-500 mb-4">Register new institutional users and assign their initial role. Public registration is disabled.</p>

                <form method="POST" action="{{ route('users.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name</label>
                        <input type="text" name="name" id="name" required placeholder="e.g. Samuel Mensah" class="w-full text-xs border-slate-300 rounded focus:border-blue-900 focus:ring-blue-900 py-2 px-3">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                        <input type="email" name="email" id="email" required placeholder="staff@gctu.edu.gh" class="w-full text-xs border-slate-300 rounded focus:border-blue-900 focus:ring-blue-900 py-2 px-3">
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Temporary Password</label>
                        <input type="password" name="password" id="password" required minlength="8" placeholder="••••••••" class="w-full text-xs border-slate-300 rounded focus:border-blue-900 focus:ring-blue-900 py-2 px-3">
                    </div>

                    <div>
                        <label for="create_role" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Assigned Role</label>
                        <div class="flex items-center space-x-2">
                            <select name="role" id="create_role" required class="w-full text-xs border-slate-300 rounded focus:border-blue-900 focus:ring-blue-900 py-2 px-3 font-semibold text-slate-800">
                                @foreach ($roles as $roleOption)
                                    <option value="{{ $roleOption->name }}">{{ $roleOption->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="shrink-0 px-4 py-2 bg-blue-950 hover:bg-blue-900 text-amber-400 font-bold text-xs rounded transition shadow-sm border border-blue-900">
                                Create Account
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Existing Users List -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-base font-bold text-slate-900 mb-4">Registered Accounts</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr class="bg-slate-50">
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Account Holder</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Email Address</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Current Role</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Modify Role</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Joined Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($users as $userItem)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-400">#{{ $userItem->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-3">
                                                <div class="h-8 w-8 rounded-full bg-blue-950 text-amber-400 flex items-center justify-center font-bold text-xs">
                                                    {{ strtoupper(substr($userItem->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-slate-900">
                                                        {{ $userItem->name }}
                                                        @if ($userItem->id === auth()->id())
                                                            <span class="ml-1 text-[10px] font-extrabold text-blue-900 bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded">(You)</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-slate-600">{{ $userItem->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs">
                                            @foreach ($userItem->roles as $role)
                                                <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded bg-slate-100 text-slate-800 border border-slate-200 mr-1">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                            @if ($userItem->roles->isEmpty())
                                                <span class="text-slate-400 text-xs italic">No role assigned</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs">
                                            <form method="POST" action="{{ route('users.update-role', $userItem) }}" class="flex items-center space-x-2">
                                                @csrf
                                                @method('PUT')

                                                <select name="role" class="text-xs border-slate-300 focus:border-blue-900 focus:ring-blue-900 rounded shadow-sm py-1.5 px-3 bg-slate-50 font-semibold text-slate-800">
                                                    @foreach ($roles as $roleOption)
                                                        <option value="{{ $roleOption->name }}" {{ $userItem->hasRole($roleOption->name) ? 'selected' : '' }}>
                                                            {{ $roleOption->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-blue-950 border border-blue-900 rounded text-xs font-semibold text-amber-400 hover:bg-blue-900 transition shadow-sm">
                                                    Save
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">{{ $userItem->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
