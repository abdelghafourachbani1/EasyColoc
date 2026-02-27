<x-app-layout>

    <div class="max-w-5xl mx-auto py-8 space-y-6">

        {{-- TITLE --}}
        <div>
            <h1 class="text-2xl font-bold">{{ $colocation->title }}</h1>
            <p class="text-gray-500">Owner: {{ $colocation->owner->name }}</p>
        </div>

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-100 text-blue-700 p-3 rounded">
                {{ session('info') }}
            </div>
        @endif

        {{-- MEMBERS --}}
        <div class="bg-white shadow rounded p-5">
            <h2 class="text-lg font-semibold mb-4">Members</h2>

            @foreach($colocation->memberships as $membership)
                @if(is_null($membership->left_at))

                    <div class="flex justify-between items-center border-b py-2">

                        <div>
                            {{ $membership->user->name }}

                            @if($membership->user_id === $colocation->owner_id)
                                <span class="text-yellow-600 text-sm">Owner</span>
                            @else
                                <span class="text-gray-500 text-sm">(Member)</span>
                            @endif
                        </div>

                        @if(auth()->id() === $colocation->owner_id && $membership->role !== 'owner')
                            <form method="POST" action="{{ route('memberships.remove', $membership->id) }}">
                                @csrf
                                <button onclick="return confirm('Remove this member?')"
                                        class="text-red-600 hover:underline">
                                    Remove
                                </button>
                            </form>
                        @endif

                    </div>

                @endif
            @endforeach
        </div>

        {{-- INVITE --}}
        @if(auth()->id() === $colocation->owner_id)
            <div class="bg-white shadow rounded p-5">
                <h2 class="text-lg font-semibold mb-3">Invite a member</h2>

                <form method="POST"
                      action="{{ route('invitations.store', $colocation->id) }}"
                      class="flex gap-2">

                    @csrf

                    <input type="email"
                           name="email"
                           placeholder="Enter email"
                           required
                           class="border p-2 rounded w-full">

                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Send
                    </button>

                </form>

                @error('email')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        @endif

        {{-- LEAVE COLOCATION --}}
        @if(auth()->id() !== $colocation->owner_id)
            <div>
                <form method="POST" action="{{ route('memberships.leave') }}">
                    @csrf

                    <button onclick="return confirm('Are you sure you want to leave?')"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        Leave colocation
                    </button>
                </form>
            </div>
        @endif

        {{-- EXPENSES --}}
        <div class="bg-white shadow rounded p-5">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Expenses</h2>
                    <form method="GET" class="mb-4">
                        <input type="month"
                            name="month"
                            value="{{ $month }}"
                            class="border p-2 rounded">

                        <button class="bg-gray-800 text-white px-3 py-2 rounded">
                            Filter
                        </button>

                        <a href="{{ route('colocation.show') }}"
                        class="ml-2 text-sm text-gray-500 underline">
                            Reset
                        </a>
                    </form>

                <button onclick="openModal()"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    + Add Expense
                </button>
            </div>

            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2">Title</th>
                        <th class="p-2">Amount</th>
                        <th class="p-2">Date</th>
                        <th class="p-2">Payeur</th>
                        <th class="p-2">Category</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($colocation->expenses as $expense)
                        <tr class="border-b">
                            <td class="p-2">{{ $expense->title }}</td>
                            <td class="p-2">{{ number_format($expense->amount,2) }} €</td>
                            <td class="p-2">{{ $expense->date }}</td>
                            <td class="p-2">{{ $expense->payeur->name }}</td>
                            <td class="p-2">{{ $expense->category->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        {{-- BALANCES --}}
        <div class="bg-white shadow rounded p-5">

            <h2 class="text-lg font-semibold mb-4">Balances</h2>

            <table class="w-full border">

                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2">Member</th>
                        <th class="p-2">Paid</th>
                        <th class="p-2">Share</th>
                        <th class="p-2">Balance</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($balances as $b)
                        <tr class="border-b">

                            <td class="p-2">{{ $b['user']->name }}</td>

                            <td class="p-2">
                                {{ number_format($b['paid'],2) }} €
                            </td>

                            <td class="p-2">
                                {{ number_format($b['share'],2) }} €
                            </td>

                            <td class="p-2 font-semibold
                                {{ $b['balance'] < 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format($b['balance'],2) }} €
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

        {{-- WHO OWES WHO --}}
            <div class="bg-white shadow rounded p-5">
                <h2 class="text-lg font-semibold mb-4">Who Owes Who</h2>

                @php
                    $settlements = [];
                    $owedMembers = collect($balances)->filter(fn($b) => $b['balance'] < 0);
                    $owedByMembers = collect($balances)->filter(fn($b) => $b['balance'] > 0);
                @endphp

                @foreach($owedMembers as $debtor)
                    @php
                        $amountOwed = abs($debtor['balance']);
                    @endphp

                    @foreach($owedByMembers as $creditor)
                        @if($amountOwed <= 0) @break @endif

                        @php
                            $payAmount = min($amountOwed, $creditor['balance']);
                            $settlements[] = [
                                'from' => $debtor['user']->name,
                                'from_id' => $debtor['user']->id,
                                'to' => $creditor['user']->name,
                                'to_id' => $creditor['user']->id,
                                'amount' => $payAmount
                            ];
                            $amountOwed -= $payAmount;
                            $creditor['balance'] -= $payAmount;
                        @endphp
                    @endforeach
                @endforeach

                @if(count($settlements) === 0)
                    <p class="text-gray-500">All balances are settled.</p>
                @else
                    <ul class="space-y-2">
                        @foreach($settlements as $s)
                            <form method="POST" action="{{ route('payments.store') }}">
                                @csrf

                                <input type="hidden" name="colocation_id" value="{{ $colocation->id }}">
                                <input type="hidden" name="from_user_id" value="{{ $s['from_id'] }}">
                                <input type="hidden" name="to_user_id" value="{{ $s['to_id'] }}">
                                <input type="hidden" name="amount" value="{{ $s['amount'] }}">

                                <button class="text-sm bg-green-600 text-white px-2 py-1 rounded">
                                    Mark as paid
                                </button>
                            </form>
                        @endforeach
                    </ul>
                @endif
            </div>


    </div>

    {{-- MODAL ADD EXPENSE --}}
    <div id="expenseModal"
         class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center">

        <div class="bg-white w-full max-w-md p-6 rounded shadow-lg">

            <h2 class="text-xl font-bold mb-4 text-gray-900">Add Expense</h2>

            <form action="{{ route('expenses.store', $colocation->id) }}"
                  method="POST"
                  class="space-y-3">

                @csrf

                <input type="text" name="title" placeholder="Title"
                       required class="border p-2 w-full rounded">

                <input type="number" step="0.01" name="amount" placeholder="Amount"
                       required class="border p-2 w-full rounded">

                <input type="date" name="date"
                       required class="border p-2 w-full rounded">

                <select name="category_id"
                        required class="border p-2 w-full rounded">

                    @foreach($colocation->categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button"
                            onclick="closeModal()"
                            class="px-4 py-2 border rounded">
                        Cancel
                    </button>

                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded">
                        Save
                    </button>
                </div>

            </form>

        </div>
    </div>

    {{-- MODAL SCRIPT --}}
    <script>
        function openModal() {
            document.getElementById('expenseModal').classList.remove('hidden');
            document.getElementById('expenseModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('expenseModal').classList.add('hidden');
            document.getElementById('expenseModal').classList.remove('flex');
        }
    </script>

</x-app-layout>
