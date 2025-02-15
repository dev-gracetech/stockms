@extends('layouts.layout')

@section('content')
    <h1>Edit Role: {{ $role->name }}</h1>
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
        </div>
        <div class="form-group">
            <label for="permissions">Permissions</label>
            <select name="permissions[]" class="form-control" multiple>
                @foreach($permissions as $permission)
                    <option value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name) ? 'selected' : '' }}>{{ $permission->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Role</button>
    </form>

    <h2>Add Permissions</h2>
    <form action="{{ route('roles.addPermissions', $role->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="permissions">Select Permissions</label>
            <select name="permissions[]" class="form-control" multiple>
                @foreach($permissions as $permission)
                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Add Permissions</button>
    </form>

    <h2>Remove Permissions</h2>
    <form action="{{ route('roles.removePermissions', $role->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="permissions">Select Permissions</label>
            <select name="permissions[]" class="form-control" multiple>
                @foreach($role->permissions as $permission)
                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-danger">Remove Permissions</button>
    </form>
@endsection