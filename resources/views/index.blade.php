@extends('layouts.app')

@section('content')
<h1>All Contacts</h1>

<form method="GET" action="{{ route('contacts.index') }}">
    <input type="text" name="search" placeholder="Search by name or email" value="{{ request('search') }}">
    <button type="submit">Search</button>
</form>

<a href="{{ route('contacts.index', ['sort' => 'name']) }}">Sort by Name</a>
<a href="{{ route('contacts.index', ['sort' => 'created_at']) }}">Sort by Date</a>

<ul>
    @foreach ($contacts as $contact)
        <li>
            <a href="{{ route('contacts.show', $contact->id) }}">{{ $contact->name }}</a> - {{ $contact->email }}
            <a href="{{ route('contacts.edit', $contact->id) }}">Edit</a>
            <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </li>
    @endforeach
</ul>

{{ $contacts->links() }}
@endsection
