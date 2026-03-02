<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- HEADER STATS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Reputation Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border-b-4 border-indigo-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Reputation Score</h3>
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-4xl font-extrabold text-gray-900">{{ number_format($reputation) }}</span>
                        <span class="text-sm font-medium text-gray-500">Points</span>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">Based on your activity and payments</p>
                </div>

                <!-- Expense Score Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border-b-4 border-green-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Expense Score</h3>
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-4xl font-extrabold text-gray-900">{{ number_format($expenseScore, 2) }}
                            €</span>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">Total amount you've spent</p>
                </div>

                <!-- Active status / Quick Link Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border-b-4 border-purple-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Colocation Status</h3>
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                        </div>
                    </div>
                    @if($colocation)
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Currently in:</p>
                                <p class="text-xl font-bold text-gray-900">{{ $colocation->title }}</p>
                            </div>
                            <a href="{{ route('colocations.show', $colocation) }}"
                                class="inline-flex items-center text-purple-600 font-bold hover:text-purple-800 transition">
                                Go to Dashboard
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            <p class="text-sm text-gray-600">You are not in a colocation</p>
                            <button onclick="document.getElementById('createColocationModal').classList.remove('hidden')"
                                class="bg-purple-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-purple-700 transition w-full shadow-md">
                                Start a New One
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- RECENT EXPENSES --}}
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                <div class="bg-gray-50 border-b p-6 flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        My Recent Activity
                    </h2>
                </div>
                <div class="p-0">
                    @if($recentExpenses->isEmpty())
                        <div class="p-12 text-center">
                            <div class="bg-indigo-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg">No recent expenses found.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Date</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Title</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Colocation</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Category</th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($recentExpenses as $expense)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $expense->date }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                                {{ $expense->title }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                @if($expense->colocation)
                                                    <a href="{{ route('colocations.show', $expense->colocation) }}"
                                                        class="text-indigo-600 hover:underline">
                                                        {{ $expense->colocation->title }}
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">Deleted Colocation</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-3 py-1 text-xs font-bold rounded-full bg-indigo-100 text-indigo-800">
                                                    {{ $expense->category->name }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-gray-900 text-right">
                                                {{ number_format($expense->amount, 2) }} €
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- CREATE MODAL (ONLY IF NO ACTIVE COLOCATION) --}}
            @if(!$colocation)
                <div id="createColocationModal"
                    class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 relative">
                        <button onclick="document.getElementById('createColocationModal').classList.add('hidden')"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <h2 class="text-2xl font-extrabold text-gray-900 mb-6 text-center">New Colocation</h2>
                        <form method="POST" action="{{ route('colocations.store') }}" class="space-y-6">
                            @csrf
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Colocation Name</label>
                                <input type="text" name="title" placeholder="e.g. Dream House, Our Apartment" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>
                            <button type="submit"
                                class="w-full px-6 py-4 bg-indigo-600 text-white font-extrabold rounded-xl shadow-lg hover:bg-indigo-700 transition duration-150 active:scale-95">
                                Create Now
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>