<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">

        <h1 class="text-2xl font-bold mb-2">{{ $colocation->title }}</h1>
        <p class="text-gray-600 mb-6">Owner: {{ $colocation->owner->name }}</p>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-100 text-blue-700 p-3 rounded mb-4">
                {{ session('info') }}
            </div>
        @endif

        <div class="bg-white shadow rounded p-4 mb-6">
            <h2 class="text-lg font-semibold mb-4">Members</h2>
            @foreach($colocation->memberships as $membership)
                @if(is_null($membership->left_at))
                    <div class="flex justify-between items-center border-b py-2">
                        <div>
                            {{ $membership->user->name }}
                            @if($membership->user_id === $colocation->owner_id)
                                <span class="text-sm text-yellow-600">Owner</span>
                            @else
                                <span class="text-sm text-gray-500">(Member)</span>
                            @endif
                        </div>

                        @if(auth()->id() === $colocation->owner_id && $membership->role !== 'owner')
                            <form method="POST" action="{{ route('memberships.remove', $membership->id) }}">
                                @csrf
                                <button onclick="return confirm('Remove this member?')" class="text-red-600 hover:underline">
                                    Remove
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>

        @if(auth()->id() === $colocation->owner_id)
            <div class="bg-white shadow rounded p-4 mb-6">
                <h2 class="text-lg font-semibold mb-3">Invite a member</h2>
                <form method="POST" action="{{ route('invitations.store', $colocation->id) }}" class="flex gap-2">
                    @csrf
                    <input type="email" name="email" placeholder="Enter email" required class="border p-2 rounded w-full">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Send</button>
                </form>
                @error('email')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        @endif

        @if(auth()->id() !== $colocation->owner_id)
            <div class="mb-6">
                <form method="POST" action="{{ route('memberships.leave') }}">
                    @csrf
                    <button onclick="return confirm('Are you sure you want to leave?')" class="bg-red-600 text-white px-4 py-2 rounded">
                        Leave colocation
                    </button>
                </form>
            </div>
        @endif

        <div class="bg-white shadow rounded p-4 mb-6">
            <h2 class="text-lg font-semibold mb-3">Add Expense</h2>
            <form action="{{ route('expenses.store', $colocation->id) }}" method="POST" class="space-y-2">
                @csrf
                <input type="text" name="title" placeholder="Title" required class="border p-2 w-full rounded">
                <input type="number" step="0.01" name="amount" placeholder="Amount" required class="border p-2 w-full rounded">
                <input type="date" name="date" required class="border p-2 w-full rounded">
                <select name="category_id" required class="border p-2 w-full rounded">
                    @foreach($colocation->categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Expense</button>
            </form>
        </div>

        <div class="bg-white shadow rounded p-4 mb-6">
            <h2 class="text-lg font-semibold mb-3">Expenses</h2>
            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-200">
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
                            <td class="p-2">{{ number_format($expense->amount,2) }}€</td>
                            <td class="p-2">{{ $expense->date }}</td>
                            <td class="p-2">{{ $expense->payeur->name }}</td>
                            <td class="p-2">{{ $expense->category->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white shadow rounded p-4 mb-6">
            <h2 class="text-lg font-semibold mb-3">Balances</h2>
            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-200">
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
                            <td class="p-2">{{ number_format($b['paid'],2) }}€</td>
                            <td class="p-2">{{ number_format($b['share'],2) }}€</td>
                            <td class="p-2 {{ $b['balance'] < 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format($b['balance'],2) }}€
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
