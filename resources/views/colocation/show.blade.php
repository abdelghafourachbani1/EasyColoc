<h1>{{ $colocation->title }}</h1>
<p>Owner {{ $colocation->owner->name }}</p>

<h2>Members</h2>
<ul> 
    @foreach ($colocation->memberships as $member) 
        <li>
            {{$member->user->name}} {{$member->role}}
        </li>
    @endforeach
</ul>