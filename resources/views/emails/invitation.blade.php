<!-- resources/views/emails/invitation.blade.php -->
<h1>Hello!</h1>
<p>the owner invited you to join the colocation: <strong>{{ $invitation->colocation->title }}</strong>.</p>
<p>
    Click the link below to accept:
</p>
<p>
    <a href="{{ route('invitations.accept', $invitation->token) }}"
       style="background:#4f46e5;color:white;padding:10px 15px;border-radius:5px;text-decoration:none;">
       Accept Invitation
    </a>
</p>
<p>If you didn t expect this invitation, just ignore this email.</p>
