<x-app-layout>
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

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

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Name</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Global Admin</th>
                    <th class="p-2">Banned</th>
                    <th class="p-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b">
                    <td class="p-2">{{ $user->name }}</td>
                    <td class="p-2">{{ $user->email }}</td>
                    <td class="p-2">{{ $user->is_global_admin ? 'Yes' : 'No' }}</td>
                    <td class="p-2">{{ $user->is_banned ? 'Yes' : 'No' }}</td>
                    <td class="p-2">
                        @if(!$user->is_global_admin)
                        <form action="{{ route('admin.toggleBan', $user->id) }}" method="POST">
                            @csrf
                            <button class="px-3 py-1 rounded {{ $user->is_banned ? 'bg-green-600' : 'bg-red-600' }} text-white">
                                {{ $user->is_banned ? 'Unban' : 'Ban' }}
                            </button>
                        </form>
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
