<form method="POST" action="{{ route('colocations.store') }}">
    @csrf

    <input type="text" name="title" placeholder="Colocation name" required>

    <button type="submit">Create Colocation</button>
</form>
