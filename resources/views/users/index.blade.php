@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>List Of Users</h3>
            <p class="text-subtitle text-muted">Manage your users here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6 mt-2">
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        Add User
                    </button>
                </div>
            </div>
            <div class="card-body mt-3">
                <div class="table-responsive datatable-minimal">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-secondary">{{ $role->name }}
                                                <i style='cursor:pointer' data-user-id="{{ $user->id }}" data-role-name="{{ $role->name }}"
                                                    class='bi bi-trash remove-role'></i>
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm assign-role" data-user-id="{{ $user->id }}" data-bs-toggle="modal" 
                                            data-bs-target="#assignRoleModal" title="Assign Role"><i class="bi bi-person-gear"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm edit-user" data-user-id="{{ $user->id }}" data-bs-toggle="modal" 
                                            data-bs-target="#editUserModal" title="Edit"><i class="bi bi-pencil-square"></i>
                                        </button>
                                        {{-- <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" 
                                            data-bs-placement="top" title="Edit"><i class="bi bi-pencil-square"></i></a> --}}
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
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

 <!-- Create User Modal -->
 <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Create User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
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

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_password">Password (Leave blank to keep current password)</label>
                        <input type="password" name="password" id="edit_password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit_password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="edit_password_confirmation" class="form-control">
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

<!-- Assign Role Modal -->
<div class="modal fade" id="assignRoleModal" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignRoleModalLabel">Assign Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignRoleForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
    $(document).ready(function() {
        // Handle create user form submission
        $('#createUserForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action') || "{{ route('users.store') }}";
            var data = form.serialize();

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                success: function(response) {
                    // Show success message
                    showAlert('success', response.message);
                    // Close the modal
                    $('#createUserModal').modal('hide');
                    // Reload the page to reflect changes
                    location.reload();
                },
                error: function(xhr) {
                    // Show validation errors
                    var errors = xhr.responseJSON.errors;
                    var errorMessages = [];
                    for (var key in errors) {
                        errorMessages.push(errors[key][0]);
                    }
                    showAlert('danger', errorMessages.join('<br>'));
                }
            });
        });
        // Handle assign role button click
        $('.assign-role').on('click', function() {
            var userId = $(this).data('user-id');

            // Update the form action URL
            $('#assignRoleForm').attr('action', `/users/${userId}/assign-role`);
        });

        // Handle form submission
        $('#assignRoleForm').on('submit', function(e) {
            e.preventDefault();

            if (confirm('Are you sure you want to assign this role?')) {
                var form = $(this);
                var url = form.attr('action');
                var data = form.serialize();

                // Send AJAX request to assign the role
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            // Reload the page to reflect the changes
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred while assigning the role.');
                    }
                });
            }
        });

        // Handle remove role button click
        $('.remove-role').on('click', function() {
            if (confirm('Are you sure you want to remove this role?')) {
                var userId = $(this).data('user-id');
                var roleName = $(this).data('role-name');
                var button = $(this);

                // Send AJAX request to remove the role
                $.ajax({
                    url: `/users/${userId}/remove-role`,
                    method: 'POST',
                    data: {
                        role: roleName,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Remove the role badge from the UI
                            button.closest('.badge').remove();
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred while removing the role.');
                    }
                });
            }
        });

        // Function to show Bootstrap alert
        function showAlert(type, message) {
                var alert = $('#alert');
                alert.removeClass('alert-success alert-danger').addClass(`alert-${type}`);
                alert.html(message).show();
                setTimeout(function() {
                    alert.hide();
                }, 5000); // Hide the alert after 5 seconds
            }
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Get all "Edit" buttons
        const editButtons = document.querySelectorAll('.edit-user');

        // Add click event listeners to each "Edit" button
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get user data from the button's data attributes
                const userId = button.getAttribute('data-user-id');

                $.ajax({
                    url: `/users/${userId}/edit`,
                    method: 'GET',
                    success: function(response) {
                        // Populate the edit modal with user data
                        $('#edit_name').val(response.name);
                        $('#edit_email').val(response.email);

                        // Update the form action URL
                        $('#editUserForm').attr('action', `/users/${userId}`);
                    },
                    error: function(xhr) {
                        alert('An error occurred while fetching user data.');
                    }
                });
            });
        });
    });
</script>
@endsection