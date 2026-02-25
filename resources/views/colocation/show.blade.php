<x-app-layout>

    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-2">{{ $colocation->title }}</h1>
        <p class="text-gray-600 mb-6">Owner: {{ $colocation->owner->name }}</p>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
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
                                <span class="text-sm text-yellow-600"> Owner</span>
                            @else
                                <span class="text-sm text-gray-500">(Member)</span>
                            @endif
                        </div>
                        @if(auth()->id() === $colocation->owner_id && $membership->role !== 'owner')
                            <form method="POST" action="{{ route('memberships.remove', $membership->id) }}">
                                @csrf
                                <button onclick=" return confirm('Remove this member?')" class="text-red-600 hover:underline">
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
                <form method="POST"
                      action="{{ route('invitations.store', $colocation->id) }}"
                      class="flex gap-2">
                    @csrf
                    <input type="email" name="email" placeholder="Enter email" required class="border p-2 rounded w-full">

                    <button type="submit" class="bg-blue text-blue px-4 py-2 rounded">
                        Send
                    </button>
                </form>
                @error('email')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        @endif


        @if(auth()->id() !== $colocation->owner_id)
            <div class="mt-6">
                <form method="POST"
                      action="{{ route('memberships.leave') }}">
                    @csrf
                    <button onclick="return confirm('Are you sure you want to leave?')"
                        class="bg-red-600 text-white px-4 py-2 rounded">
                        Leave colocation
                    </button>
                </form>
            </div>
        @endif
    </div>

</x-app-layout>
