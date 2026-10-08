<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>GCTU TRACE — Ghana Communication Technology University</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="bg-slate-100 text-slate-800 font-sans min-h-screen flex flex-col antialiased">
        
        <!-- Top Institutional Header -->
        <header class="bg-blue-950 border-b border-blue-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <!-- Branding -->
                <div class="flex items-center space-x-4">
                    <div class="w-11 h-11 bg-amber-500 rounded-lg flex items-center justify-center font-bold text-blue-950 text-xl shadow">
                        GCTU
                    </div>
                    <div>
                        <div class="text-lg font-bold text-white tracking-wide">GHANA COMMUNICATION TECHNOLOGY UNIVERSITY</div>
                        <div class="text-xs text-amber-400 font-medium tracking-wider">TRACE — Asset & Procurement Management Portal</div>
                    </div>
                </div>

                <!-- Auth Navigation -->
                <nav class="flex items-center space-x-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-blue-950 font-semibold text-sm rounded transition shadow-sm">
                            Go to Dashboard &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-blue-950 font-semibold text-sm rounded transition shadow-sm">
                            Staff Login &rarr;
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                
                <!-- Left Column: Portal Summary & Modules -->
                <div class="lg:col-span-7 bg-white border border-slate-200 rounded-xl p-8 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center space-x-2 px-3 py-1 rounded bg-blue-50 border border-blue-100 text-blue-900 text-xs font-semibold mb-6">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            <span>Official Administrative System</span>
                        </div>

                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight leading-snug mb-4">
                            University Asset Tracking & Procurement System
                        </h1>

                        <p class="text-slate-600 text-base leading-relaxed mb-8">
                            TRACE provides a centralized database for managing ICT hardware, office assets, procurement requests, and administrative audit trails across all GCTU departments. Public account self-registration is restricted.
                        </p>

                        <!-- Key Features List -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-100 pt-6">
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-amber-50 rounded border border-amber-100 text-amber-700 mt-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">Asset Management</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Register, classify, and track equipment assignments and locations.</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-blue-50 rounded border border-blue-100 text-blue-700 mt-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">Procurement Workflow</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Submit requisitions and monitor pending, approved, or completed requests.</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-emerald-50 rounded border border-emerald-100 text-emerald-700 mt-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">Admin Account Provisioning</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Accounts are created exclusively by Administrators with assigned roles.</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-purple-50 rounded border border-purple-100 text-purple-700 mt-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">Comprehensive Audit Logging</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Logs all user actions, successful logins, failed login attempts (with email & IP), logouts, and role changes.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-4 border-t border-slate-100 text-xs text-slate-500 flex items-center justify-between">
                        <span>Version 1.0.0 &bull; Internal Network</span>
                        <span>Direct Support: <a href="mailto:it-support@gctu.edu.gh" class="text-blue-900 font-semibold hover:underline">it-support@gctu.edu.gh</a></span>
                    </div>
                </div>

                <!-- Right Column: Direct Staff Portal Login Box -->
                <div class="lg:col-span-5 bg-white border border-slate-200 rounded-xl p-8 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="border-b border-slate-100 pb-4 mb-6">
                            <h2 class="text-xl font-bold text-slate-900">Staff Access Portal</h2>
                            <p class="text-xs text-slate-500 mt-1">Sign in with your administrator-issued staff credentials.</p>
                        </div>

                        @auth
                            <div class="bg-blue-50 border border-blue-100 rounded-lg p-5 mb-6 text-center">
                                <div class="w-12 h-12 rounded-full bg-blue-950 text-amber-400 font-bold text-lg flex items-center justify-center mx-auto mb-3">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <h3 class="font-bold text-slate-900 text-base">{{ Auth::user()->name }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5">{{ Auth::user()->email }}</p>
                                <span class="inline-block mt-2 px-2.5 py-0.5 bg-amber-100 text-amber-800 border border-amber-200 rounded text-xs font-semibold">
                                    {{ Auth::user()->roles->pluck('name')->first() ?? 'User' }}
                                </span>

                                <div class="mt-6">
                                    <a href="{{ url('/dashboard') }}" class="block w-full py-2.5 bg-blue-950 hover:bg-blue-900 text-white font-semibold text-sm rounded shadow transition">
                                        Open Dashboard
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="space-y-4">
                                <a href="{{ route('login') }}" class="flex items-center justify-center space-x-2 w-full py-3 bg-blue-950 hover:bg-blue-900 text-white font-semibold text-sm rounded shadow transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                    <span>Log in to TRACE</span>
                                </a>
                            </div>

                            <!-- Detailed Security & Access Notice -->
                            <div class="mt-8 bg-slate-50 border border-slate-200/80 rounded-lg p-4">
                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">Security & Access Notice</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Unauthorized access is strictly prohibited. All user interactions are continuously logged for institutional compliance and security monitoring—including successful logins, failed login attempts (capturing the attempted email and client IP address), logouts, record modifications, and administrative role updates.
                                </p>
                            </div>
                        @endauth
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                        <span class="text-xs text-slate-400">&copy; {{ date('Y') }} Ghana Communication Technology University</span>
                    </div>
                </div>

            </div>
        </main>

        <!-- Institutional Footer -->
        <footer class="bg-slate-900 text-slate-400 py-4 border-t border-slate-800 text-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>
                    <strong>Ghana Communication Technology University</strong> &bull; PMB 100, Accra North, Ghana
                </div>
                <div>
                    TRACE Asset & Procurement Management System
                </div>
            </div>
        </footer>
    </body>
</html>
