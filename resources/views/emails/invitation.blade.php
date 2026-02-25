<h2>you are invited to join this {{ $invitation->colocation->name }} </h2>
<a href="{{ route('invitations.accept', $invitation->token) }}">accept invitation</a>