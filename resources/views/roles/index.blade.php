@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>List Of Roles</h3>
            <p class="text-subtitle text-muted">Manage roles here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6 mt-2">
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                        Add Role</button>
                </div>
            </div>
            <div class="card-body mt-3">
                <div class="table-responsive datatable-minimal">
                    <table class="table datatable" id="table-roles">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Permissions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        @foreach($role->permissions as $permission)
                                            <span class="badge bg-secondary">{{ $permission->name }}
                                                <i style='cursor:pointer' data-permission-name="{{ $permission->name }}" data-role-id="{{ $role->id }}" 
                                                    class='bi bi-trash remove-permission'></i>
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm edit-role" data-role-id="{{ $role->id }}" 
                                            data-bs-toggle="modal" data-bs-target="#editRoleModal" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" 
                                            data-bs-placement="top" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRoleModalLabel">Create Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="permissions">Permissions</label>
                        <select name="permissions[]" class="form-control" multiple>
                            @foreach($permissions as $permission)
                                <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRoleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_permissions">Permissions</label>
                        <select name="permissions[]" id="edit_permissions" class="form-control" multiple>
                            <!-- Permissions will be populated dynamically -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
    $(document).ready(function() {
        // Remove permission from role
        $('.remove-permission').on('click', function() {
            if (confirm('Are you sure you want to remove this permission?')) {
                var roleId = $(this).data('role-id');
                var permissionName = $(this).data('permission-name');
                var button = $(this);

                // Send AJAX request to remove the permission
                $.ajax({
                    url: `/roles/${roleId}/remove-permission`,
                    method: 'POST',
                    data: {
                        permission: permissionName,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Remove the permission badge from the UI
                            button.closest('.badge').remove();
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred while removing the permission.');
                    }
                });
            }
        });

        // Handle edit button click
        $('.edit-role').on('click', function() {
            var roleId = $(this).data('role-id');

            // Fetch role data
            $.ajax({
                url: `/roles/${roleId}/edit-data`,
                method: 'GET',
                success: function(response) {
                    // Populate the edit modal with role data
                    $('#edit_name').val(response.role.name);

                    // Clear and populate permissions
                    $('#edit_permissions').empty();
                    response.permissions.forEach(function(permission) {
                        var selected = response.role.permissions.some(p => p.name === permission.name);
                        $('#edit_permissions').append(
                            `<option value="${permission.name}" ${selected ? 'selected' : ''}>${permission.name}</option>`
                        );
                    });

                    // Update the form action URL
                    $('#editRoleForm').attr('action', `/roles/${roleId}`);
                },
                error: function(xhr) {
                    alert('An error occurred while fetching role data.');
                }
            });
        });
    });
</script>
@endsection