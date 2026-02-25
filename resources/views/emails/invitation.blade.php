<h2>you are inted to join this {{ $invitation->colocation->name }} </h2>
<a href="{{ route('invitations.accept', $invitation->token) }}"></a>