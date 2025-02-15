@extends('layouts.layout')

@section('content')
    <h1>User Details</h1>
    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Role:</strong> {{ $user->role }}</p>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
@endsection