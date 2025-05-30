@extends('layouts.app')

@section('content')
<h2>Edit User</h2>

<form action="{{ route('users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Name:</label>
    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>

    <label>Email:</label>
    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>

    <label>Password (leave blank to keep existing):</label>
    <input type="password" name="password">

    <label>Confirm Password:</label>
    <input type="password" name="password_confirmation">

    <label>Type:</label>
    <select name="type" required>
        <option value="admin" {{ $user->type == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="caller" {{ $user->type == 'caller' ? 'selected' : '' }}>Caller</option>
        <option value="executive" {{ $user->type == 'executive' ? 'selected' : '' }}>Executive</option>
    </select>

    <button type="submit">Update</button>
</form>
@endsection
