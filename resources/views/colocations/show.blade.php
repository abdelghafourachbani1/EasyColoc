<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- HEADER --}}
            <div class="bg-gradient-to-br from-indigo-700 via-indigo-600 to-purple-700 rounded-3xl shadow-2xl p-8 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h1 class="text-5xl font-extrabold mb-3 tracking-tight">{{ $colocation->title }}</h1>
                            <div class="flex items-center space-x-4 text-indigo-100">
                                <div class="flex items-center bg-white/10 px-3 py-1 rounded-full backdrop-blur-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                    <span class="text-sm font-medium">Owner: {{ $colocation->owner->name }}</span>
                                </div>
                                @if($month)
                                    <div class="flex items-center bg-white/10 px-3 py-1 rounded-full backdrop-blur-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-sm font-medium">{{ \Carbon\Carbon::parse($month)->format('F Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('expenses.create', $colocation) }}" class="bg-white text-indigo-600 px-6 py-3 rounded-2xl font-bold shadow-lg hover:bg-indigo-50 transition transform hover:-translate-y-1 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Add Expense
                            </a>
                            @if(auth()->id() === $colocation->owner_id)
                                <a href="{{ route('categories.index', $colocation) }}" class="bg-indigo-500/20 hover:bg-indigo-500/40 border border-indigo-400/30 text-white px-6 py-3 rounded-2xl font-bold backdrop-blur-sm transition flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                    Categories
                                </a>
                            @endif
                            @if(auth()->id() === $colocation->owner_id)
                                <form action="{{ route('colocations.cancel', $colocation) }}" method="POST" onsubmit="return confirm('Cancel this colocation?')">
                                    @csrf
                                    <button class="bg-red-500/20 hover:bg-red-500/40 border border-red-400/30 text-white px-6 py-3 rounded-2xl font-bold backdrop-blur-sm transition flex items-center justify-center w-full md:w-auto">
                                        Cancel Colocation
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('memberships.leave') }}" method="POST" onsubmit="return confirm('Leave this colocation?')">
                                    @csrf
                                    <button class="bg-red-500/20 hover:bg-red-500/40 border border-red-400/30 text-white px-6 py-3 rounded-2xl font-bold backdrop-blur-sm transition flex items-center justify-center w-full md:w-auto">
                                        Leave Colocation
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Decorative circles --}}
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-purple-500/20 rounded-full blur-3xl"></div>
            </div>

            {{-- STATS GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Volume -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border-b-4 border-indigo-500">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Volume</span>
                        <div class="p-2 bg-indigo-50 rounded-xl text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-gray-900">{{ number_format($totalVolume, 2) }} €</p>
                    <p class="text-xs text-gray-500 mt-1">Total expenses for this period</p>
                </div>

                <!-- Your Balance -->
                @php
                    $myBalance = collect($balances)->firstWhere('user.id', auth()->id())['balance'] ?? 0;
                @endphp
                <div class="bg-white p-6 rounded-3xl shadow-sm border-b-4 {{ $myBalance >= 0 ? 'border-green-500' : 'border-red-500' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">Your Balance</span>
                        <div class="p-2 {{ $myBalance >= 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black {{ $myBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($myBalance, 2) }} €
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Total standing in this colocation</p>
                </div>

                <!-- Members Count -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border-b-4 border-purple-500">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">Active Members</span>
                        <div class="p-2 bg-purple-50 rounded-xl text-purple-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-gray-900">{{ count($balances) }}</p>
                    <p class="text-xs text-gray-500 mt-1">People sharing the costs</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- LEFT COLUMN: MEMBERS & INVITE --}}
                <div class="space-y-8">
                    <!-- Members Section -->
                    <div class="bg-white shadow-xl rounded-3xl overflow-hidden">
                        <div class="bg-gray-50/50 p-6 border-b flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900">Roommates</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach($colocation->memberships as $membership)
                                @if(is_null($membership->left_at))
                                    <div class="flex items-center justify-between group">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-indigo-100 text-indigo-700 rounded-2xl flex items-center justify-center font-bold">
                                                {{ substr($membership->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">{{ $membership->user->name }}</p>
                                                @if($membership->user_id === $colocation->owner_id)
                                                    <span class="text-[10px] uppercase tracking-wider font-black text-amber-600">Owner</span>
                                                @else
                                                    <span class="text-[10px] uppercase tracking-wider font-medium text-gray-400">Member</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if(auth()->id() === $colocation->owner_id && $membership->user_id !== $colocation->owner_id)
                                            <form method="POST" action="{{ route('memberships.remove', $membership->id) }}" onsubmit="return confirm('Remove this member?')">
                                                @csrf
                                                <button class="text-gray-300 hover:text-red-500 transition opacity-0 group-hover:opacity-100">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Invite Section -->
                    @if(auth()->id() === $colocation->owner_id)
                        <div class="bg-indigo-600 shadow-xl rounded-3xl p-6 text-white overflow-hidden relative">
                            <div class="relative z-10">
                                <h3 class="text-xl font-bold mb-4">Invite Others</h3>
                                <form method="POST" action="{{ route('invitations.store', $colocation) }}" class="space-y-4">
                                    @csrf
                                    <input type="email" name="email" placeholder="Email address" required
                                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-indigo-200 focus:ring-2 focus:ring-white/50 focus:border-transparent transition">
                                    <button type="submit" class="w-full bg-white text-indigo-600 font-bold py-3 rounded-xl shadow-lg hover:bg-indigo-50 transition">
                                        Send Invitation
                                    </button>
                                </form>
                            </div>
                            <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-white/10 rounded-full"></div>
                        </div>
                    @endif
                </div>

                {{-- MAIN COLUMN: ACTIVITY & BALANCES --}}
                <div class="lg:col-span-2 space-y-8">
                    <!-- Expenses Section -->
                    <div class="bg-white shadow-xl rounded-3xl overflow-hidden">
                        <div class="bg-gray-50/50 p-6 border-b flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <h2 class="text-xl font-bold text-gray-900">Expense Report</h2>
                            <form method="GET" class="flex gap-2">
                                <input type="month" name="month" value="{{ $month }}"
                                       class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                <button class="px-4 py-2 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-black transition">Filter</button>
                                <a href="{{ route('colocations.show', $colocation) }}" class="px-4 py-2 text-gray-400 hover:text-gray-900 text-sm font-medium flex items-center">Reset</a>
                            </form>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Expense</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Paid By</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($expenses as $expense)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4">
                                                <p class="font-bold text-gray-900">{{ $expense->title }}</p>
                                                <p class="text-xs text-gray-400">{{ $expense->date }} • {{ $expense->category->name }}</p>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $expense->payeur->name }}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span class="font-black text-gray-900 text-lg">{{ number_format($expense->amount, 2) }} €</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">No expenses recorded for this period.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Balances Table -->
                        <div class="bg-white shadow-xl rounded-3xl overflow-hidden">
                            <div class="bg-gray-50/50 p-6 border-b">
                                <h2 class="text-xl font-bold text-gray-900 text-center">Balances</h2>
                            </div>
                            <div class="p-0">
                                <table class="w-full">
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($balances as $b)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-6 py-4">
                                                    <p class="text-sm font-bold text-gray-900">{{ $b['user']->name }}</p>
                                                    <p class="text-[10px] text-gray-400">Paid: {{ number_format($b['paid'], 2) }} €</p>
                                                </td>
                                                <td class="px-6 py-4 text-right font-black {{ $b['balance'] >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                                    {{ $b['balance'] > 0 ? '+' : '' }}{{ number_format($b['balance'], 2) }} €
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Who Owes Who -->
                        <div class="bg-white shadow-xl rounded-3xl overflow-hidden">
                            <div class="bg-gray-50/50 p-6 border-b">
                                <h2 class="text-xl font-bold text-gray-900 text-center">Settlements</h2>
                            </div>
                            <div class="p-6">
                                @forelse($settlements as $s)
                                    <div class="mb-4 last:mb-0 p-4 bg-gray-50 rounded-2xl border border-gray-100 space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <span class="font-bold text-gray-900 text-xs">{{ $s['from']->name }}</span>
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                <span class="font-bold text-gray-900 text-xs">{{ $s['to']->name }}</span>
                                            </div>
                                            <span class="font-black text-indigo-600">{{ number_format($s['amount'], 2) }} €</span>
                                        </div>
                                        <form method="POST" action="{{ route('payments.store') }}">
                                            @csrf
                                            <input type="hidden" name="colocation_id" value="{{ $colocation->id }}">
                                            <input type="hidden" name="from_user_id" value="{{ $s['from']->id }}">
                                            <input type="hidden" name="to_user_id" value="{{ $s['to']->id }}">
                                            <input type="hidden" name="amount" value="{{ $s['amount'] }}">
                                            <button class="w-full bg-indigo-600 text-white text-xs font-bold py-2 rounded-xl hover:bg-indigo-700 transition">
                                                Mark as Received
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <div class="w-12 h-12 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">All Clear</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>