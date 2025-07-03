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
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="bi bi-plus-circle"></i> Add User
                    </button>
                </div>
            </div>
            <div class="card-body mt-3">
                <div class="table-responsive datatable-minimal">
                    <table class="table datatable" id="table-users">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Branches/Warehouse</th>
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
                                        @foreach($user->branches as $branch)
                                            <span class="badge bg-secondary">{{ $branch->name }}
                                                <i style='cursor:pointer' data-user-id="{{ $user->id }}" data-branch-name="{{ $branch->name }}"
                                                    class='bi bi-trash remove-branch'></i>
                                            </span>
                                        @endforeach
                                        @foreach ($user->warehouses as $warehouse)
                                            <span class="badge bg-secondary">{{ $warehouse->name }}
                                                <i style='cursor:pointer' data-user-id="{{ $user->id }}" data-warehouse-name="{{ $warehouse->name }}"
                                                    class='bi bi-trash remove-warehouse'></i>
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm assign-role" data-user-id="{{ $user->id }}" data-bs-toggle="modal" 
                                            data-bs-target="#assignRoleModal" title="Assign Role"><i class="bi bi-person-gear"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm assign-branch" data-user-id="{{ $user->id }}" data-bs-toggle="modal" 
                                            data-bs-target="#assignBranchModal" title="Assign Branch"><i class="bi bi-person-workspace"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm assign-warehouse" data-user-id="{{ $user->id }}" data-bs-toggle="modal" 
                                            data-bs-target="#assignWarehouseModal" title="Assign Warehouse"><i class="bi bi-house-add"></i>
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
            <form id="createUserForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user_name">Name</label>
                        <input type="text" name="name" id="user_name" class="form-control" required autocomplete="true">
                    </div>
                    <div class="form-group">
                        <label for="user_email">Email</label>
                        <input type="email" name="email" id="user_email" class="form-control" required autocomplete="true">
                    </div>
                    <div class="form-group">
                        <label for="user_password">Password</label>
                        <input type="password" name="password" id="user_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
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
                        <input type="text" name="name" id="edit_name" class="form-control" required autocomplete="true">
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required autocomplete="true">
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
                    <input type="hidden" name="user_id" id="role_user_id">
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

<!-- Assign Branch Modal -->
<div class="modal fade" id="assignBranchModal" tabindex="-1" aria-labelledby="assignBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignBranchModalLabel">Assign Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignBranchForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="user_id" id="branch_user_id">
                        <label for="branch">Branch</label>
                        <select name="branch" id="branch" class="form-control" required>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
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

<!--Assigne Warehouse Modal -->
<div class="modal fade" id="assignWarehouseModal" tabindex="-1" aria-labelledby="assignWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignWarehouseModalLabel">Assign Warehouse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignWarehouseForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="user_id" id="warehouse_user_id">
                        <label for="warehouse">Warehouse</label>
                        <select name="warehouse" id="warehouse" class="form-control" required>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
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
        //var objTable = $('#table-users').DataTable();
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
                    //showAlert('success', response.message);
                    Swal.fire({
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
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
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: errorMessages.join('<br>'),
                    });
                        //showAlert('danger', errorMessages.join('<br>'));
                }
            });
        });
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

        //Get all assign role button click
        const assignRoleButtons = document.querySelectorAll('.assign-role');

        // Add click event listeners to each "assign role" button
        assignRoleButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get user data from the button's data attributes
                const userId = button.getAttribute('data-user-id');
                $('#role_user_id').val(userId);
                $('#assignRoleModal').modal('show');
            });
        });

        // Handle the form submission
        document.getElementById('assignRoleForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const userId = document.getElementById('role_user_id').value;
            const formData = new FormData(this);
            fetch(`users/${userId}/assign-role`, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message);
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to assign role.',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        //Get all remove role button click
        const removeRoleButtons = document.querySelectorAll('.remove-role');

        // Add click event listeners to each "remove role" button
        removeRoleButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get user data from the button's data attributes
                const userId = button.getAttribute('data-user-id');
                const roleName = button.getAttribute('data-role-name');
                //$('#user_id').val(userId);
                //$('#assignRoleModal').modal('show');
                if (confirm('Are you sure you want to remove this role?')) {

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
                                showAlert(response.message);
                            }
                        },
                        error: function(xhr) {
                            //alert('An error occurred while removing the role.');
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "An error occurred while removing the role.",
                            });
                        }
                    });
                }
            });
        });

        //Get all assign branch button click
        const assignBranchButtons = document.querySelectorAll('.assign-branch');

        // Add click event listeners to each "assign branch" button
        assignBranchButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get user data from the button's data attributes
                const userId = button.getAttribute('data-user-id');
                $('#branch_user_id').val(userId);
                $('#assignBranchModal').modal('show');
            });
        });

        // Handle the form submission
        document.getElementById('assignBranchForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const userId = document.getElementById('branch_user_id').value;
            const formData = new FormData(this);
            fetch(`users/${userId}/assign-branch`, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message);
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to assign branch.',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        //Get all remove branch button click
        const removeBranchButtons = document.querySelectorAll('.remove-branch');

        // Add click event listeners to each "remove branch" button
        removeBranchButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get user data from the button's data attributes
                const userId = button.getAttribute('data-user-id');
                const branchName = button.getAttribute('data-branch-name');
                
                if (confirm('Are you sure you want to remove this branch?')) {

                    // Send AJAX request to remove the branch
                    $.ajax({
                        url: `/users/${userId}/remove-branch`,
                        method: 'POST',
                        data: {
                            role: branchName,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                // Remove the role badge from the UI
                                button.closest('.badge').remove();
                                showAlert(response.message);
                            }
                        },
                        error: function(xhr) {
                            //alert('An error occurred while removing the role.');
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "An error occurred while removing the role.",
                            });
                        }
                    });
                }
            });
        });

        //Get all assign warehouse button click
        const assignWarehouseButtons = document.querySelectorAll('.assign-warehouse');

        // Add click event listeners to each "assign warehouse" button
        assignWarehouseButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get user data from the button's data attributes
                const userId = button.getAttribute('data-user-id');
                $('#warehouse_user_id').val(userId);
                $('#assignWarehouseModal').modal('show');
            });
        });

        //handle assign warehouse form submit
        document.getElementById('assignWarehouseForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const userId = document.getElementById('warehouse_user_id').value;
            const formData = new FormData(this);
            fetch(`users/${userId}/assign-warehouse`, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message);
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to assign warehouse.',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        //get all remove warehouse button click
        const removeWarehouseButtons = document.querySelectorAll('.remove-warehouse');

        // Add click event listeners to each "remove warehouse" button
        removeWarehouseButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get user data from the button's data attributes
                const userId = button.getAttribute('data-user-id');
                const warehouseName = button.getAttribute('data-warehouse-name');
                
                if (confirm('Are you sure you want to remove this warehouse?')) {

                    // Send AJAX request to remove the warehouse
                    $.ajax({
                        url: `/users/${userId}/remove-warehouse`,
                        method: 'POST',
                        data: {
                            role: warehouseName,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                // Remove the role badge from the UI
                                button.closest('.badge').remove();
                                showAlert(response.message);
                            }
                        },
                        error: function(xhr) {
                            //alert('An error occurred while removing the role.');
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "An error occurred while removing the role.",
                            });
                        }
                    });
                }
            });
        });

        function showAlert(message)
        {
            Swal.fire({
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
</script>
@endsection