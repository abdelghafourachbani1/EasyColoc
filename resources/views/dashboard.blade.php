<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

@if(auth()->user()->activeMembership)
    <a href="{{ route('colocations.show') }}">Go to my colocation</a>
@else
    <form method="POST" action="{{ route('colocations.store') }}">
        @csrf
        <input type="text" name="title" placeholder="Colocation name" required>
        <button type="submit">Create Colocation</button>
    </form>
@endif

</x-app-layout>
