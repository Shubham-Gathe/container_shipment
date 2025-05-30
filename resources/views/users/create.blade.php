@extends('layouts.app')

@section('content')
<h2>Create New User</h2>

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <div>
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
    </div>

    <div>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required>
    </div>

    <div>
        <label for="password_confirmation">Confirm Password:</label><br>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
    </div>

    <div>
        <label for="type">User Type:</label><br>
        <select id="type" name="type" required>
            <option value="">-- Select Type --</option>
            <option value="admin" {{ old('type') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="caller" {{ old('type') == 'Driver' ? 'selected' : '' }}>Driver</option>
            <option value="executive" {{ old('type') == 'restraunt' ? 'selected' : '' }}>Restraunt manager</option>
        </select>
    </div>

    <br>
    <button type="submit">Create User</button>
    <a href="{{ route('users.index') }}">Cancel</a>
</form>
@endsection
