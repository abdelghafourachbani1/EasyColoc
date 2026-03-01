<x-app-layout>
<div class="max-w-5xl mx-auto py-8 space-y-6">

    <h1 class="text-2xl font-bold">Admin Dashboard</h1>

    {{-- Stats --}}
    <div class="grid grid-cols-4 gap-4">
        <div class="bg-white shadow rounded p-4">
            <h2 class="text-sm font-semibold">Users</h2>
            <p class="text-xl font-bold">{{ $stats['total_users'] }}</p>
        </div>
        <div class="bg-white shadow rounded p-4">
            <h2 class="text-sm font-semibold">Colocations</h2>
            <p class="text-xl font-bold">{{ $stats['total_colocations'] }}</p>
        </div>
        <div class="bg-white shadow rounded p-4">
            <h2 class="text-sm font-semibold">Expenses</h2>
            <p class="text-xl font-bold">{{ $stats['total_expenses'] }}</p>
        </div>
        <div class="bg-white shadow rounded p-4">
            <h2 class="text-sm font-semibold">Banned Users</h2>
            <p class="text-xl font-bold">{{ $stats['banned_users'] }}</p>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-white shadow rounded p-5 mt-6">
        <h2 class="text-lg font-semibold mb-4">Users</h2>

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Name</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Reputation</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b">
                    <td class="p-2">{{ $user->name }}</td>
                    <td class="p-2">{{ $user->email }}</td>
                    <td class="p-2">{{ $user->reputation }}</td>
                    <td class="p-2">{{ $user->banned ? 'Banned' : 'Active' }}</td>
                    <td class="p-2">
                        <form method="POST" action="{{ route('admin.toggleBan', $user->id) }}">
                            @csrf
                            <button class="bg-red-600 text-white px-2 py-1 rounded">
                                {{ $user->banned ? 'Unban' : 'Ban' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
</x-app-layout>
