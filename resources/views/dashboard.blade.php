<x-app-layout>
@if(auth()->user()->activeMembership)
    @php
        $membership = auth()->user()->activeMembership;
        $colocation = $membership->colocation;
    @endphp

    <a href="{{ route('colocations.show', $colocation) }}" 
       class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
       Go to my colocation
    </a>
@else
    <form method="POST" action="{{ route('colocations.store') }}">
        @csrf
        <input type="text" name="title" placeholder="Colocation name" required>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Create Colocation
        </button>
    </form>
@endif
</x-app-layout>
