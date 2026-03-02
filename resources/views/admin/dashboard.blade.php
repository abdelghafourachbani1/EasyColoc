<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- HEADER --}}
            <div
                class="bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900 rounded-3xl shadow-2xl p-8 text-white relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h1 class="text-4xl font-black mb-2 tracking-tight">Admin Control Center</h1>
                        <p class="text-slate-400 font-medium">Platform-wide statistics and user management</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/10 px-4 py-2 rounded-2xl backdrop-blur-md border border-white/10">
                            <p class="text-[10px] uppercase tracking-widest font-bold text-slate-400">System Status</p>
                            <p class="text-sm font-bold flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                Operational
                            </p>
                        </div>
                    </div>
                </div>
                {{-- Decorative elements --}}
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
            </div>

            {{-- STATS GRID --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Users -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-3 bg-indigo-50 rounded-2xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-3.812M13.978 9.846a4 4 0 11-5.956 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Users</span>
                    </div>
                    <p class="text-3xl font-black text-gray-900">{{ number_format($stats['total_users']) }}</p>
                    <p class="text-xs text-green-500 mt-1 font-bold">Total registered accounts</p>
                </div>

                <!-- Colocations -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-3 bg-emerald-50 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011-1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Spaces</span>
                    </div>
                    <p class="text-3xl font-black text-gray-900">{{ number_format($stats['total_colocations']) }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-bold">Active sharing groups</p>
                </div>

                <!-- Total Expenses -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-3 bg-amber-50 rounded-2xl text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Activity</span>
                    </div>
                    <p class="text-3xl font-black text-gray-900">{{ number_format($stats['total_expenses']) }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-bold">Total expenses logged</p>
                </div>

                <!-- Total Volume -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-3 bg-purple-50 rounded-2xl text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Economy</span>
                    </div>
                    <p class="text-3xl font-black text-gray-900">{{ number_format($stats['total_volume'], 2) }} €</p>
                    <p class="text-xs text-purple-500 mt-1 font-bold">Total platform transaction volume</p>
                </div>
            </div>

            {{-- MESSAGES --}}
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-2xl shadow-sm animate-fade-in">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-emerald-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 font-bold text-emerald-800">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            {{-- USER MANAGEMENT TABLE --}}
            <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-100">
                <div
                    class="p-8 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50/50">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900">User Management</h2>
                        <p class="text-sm text-gray-500 font-medium">Monitoring and controlling all {{ count($users) }}
                            accounts</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span
                            class="px-3 py-1 bg-red-50 text-red-600 text-xs font-black rounded-full uppercase tracking-tighter">
                            {{ $stats['banned_users'] }} Banned
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th
                                    class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-widest">
                                    Information</th>
                                <th
                                    class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-widest">
                                    Status</th>
                                <th
                                    class="px-8 py-5 text-right text-xs font-black text-gray-400 uppercase tracking-widest">
                                    Management</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50/80 transition group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-12 h-12 bg-indigo-100 text-indigo-700 rounded-2xl flex items-center justify-center font-black text-lg shadow-sm group-hover:scale-110 transition transition-transform">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-black text-gray-900 leading-tight">{{ $user->name }}</p>
                                                <p class="text-sm text-gray-400 font-medium">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-wrap gap-2">
                                            @if($user->is_global_admin)
                                                <span
                                                    class="px-3 py-1 bg-indigo-600 text-white text-[10px] font-black rounded-lg uppercase tracking-wider shadow-sm">Global
                                                    Admin</span>
                                            @else
                                                <span
                                                    class="px-3 py-1 bg-gray-100 text-gray-400 text-[10px] font-black rounded-lg uppercase tracking-wider">User</span>
                                            @endif

                                            @if($user->is_banned)
                                                <span
                                                    class="px-3 py-1 bg-red-100 text-red-600 text-[10px] font-black rounded-lg uppercase tracking-wider">Restricted</span>
                                            @else
                                                <span
                                                    class="px-3 py-1 bg-emerald-100 text-emerald-600 text-[10px] font-black rounded-lg uppercase tracking-wider">Active</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        @if(!$user->is_global_admin)
                                            <form action="{{ route('admin.toggleBan', $user->id) }}" method="POST"
                                                onsubmit="return confirm('{{ $user->is_banned ? 'Restore access for this user?' : 'Are you sure you want to ban this user?' }}')">
                                                @csrf
                                                <button
                                                    class="inline-flex items-center space-x-2 {{ $user->is_banned ? 'bg-emerald-50 text-emerald-600 border-emerald-200 hover:bg-emerald-600 hover:text-white' : 'bg-red-50 text-red-600 border-red-200 hover:bg-red-600 hover:text-white' }} px-4 py-2 rounded-xl border font-bold text-sm transition transition-all shadow-sm">
                                                    @if($user->is_banned)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span>Restore Access</span>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>
                                                        <span>Restrict Account</span>
                                                    @endif
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-300 font-bold italic text-xs">System Protected</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>