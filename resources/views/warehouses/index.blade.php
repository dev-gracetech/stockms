@extends('layouts.layout')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>List Of Warehouses</h3>
            <p class="text-subtitle text-muted">Manage your warehouses here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <!-- Success/Error Message -->
        <div id="responseMessage"></div>
        <div class="card">
            <div class="card-header">
                <div class="col-md-6 mt-2">
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createWarehouseModal">
                        Add Warehouse
                    </button>
                </div>
            </div>
            <div class="card-body mt-3">
                <div class="table-responsive">
                    <table class="table datatable" id="warehouseTable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($warehouses as $warehouse)
                                <tr>
                                    <td title="View Stocks"></td>
                                    <td>{{ $warehouse->name }}</td>
                                    <td>{{ $warehouse->location }}</td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-primary btn-sm edit-warehouse" data-id="{{ $warehouse->id }}"
                                            data-bs-toggle="modal" data-bs-target="#editWarehouseModal" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        
                                        <!-- Delete Button -->
                                        <button class="btn btn-danger btn-sm delete-warehouse" data-id="{{ $warehouse->id }}" title="Edit">
                                            <i class="bi bi-trash"></i>
                                        </button>
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

<!-- Create Warehouse Modal -->
<div class="modal fade" id="createWarehouseModal" tabindex="-1" aria-labelledby="createWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createWarehouseModalLabel">Add Warehouse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createWarehouseForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Warehouse Modal -->
<div class="modal fade" id="editWarehouseModal" tabindex="-1" aria-labelledby="editWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editWarehouseModalLabel">Edit Warehouse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editWarehouseForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_warehouse_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="edit_location" name="location" required>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteWarehouseModal" tabindex="-1" aria-labelledby="deleteWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteWarehouseModalLabel">Delete Warehouse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this warehouse?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom-scripts')
<script>
    // JavaScript for CRUD operations
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Create Warehouse
        $('#createWarehouseForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('warehouses.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#createWarehouseModal').modal('hide');
                    location.reload(); // Reload the page to reflect changes
                },
                error: function(response) {
                    alert('Error: ' + response.responseJSON.message);
                }
            });
        });

        // Edit Warehouse
        $('.edit-warehouse').on('click', function() {
            var warehouseId = $(this).data('id');
            $.ajax({
                url: `/warehouses/${warehouseId}/edit-data`,
                method: 'GET',
                success: function(response) {
                    $('#edit_warehouse_id').val(response.warehouse.id);
                    $('#edit_name').val(response.warehouse.name);
                    $('#edit_location').val(response.warehouse.location);
                    $('#editWarehouseModal').modal('show');
                }
            });
        });

        $('#editWarehouseForm').on('submit', function(e) {
            e.preventDefault();
            var warehouseId = $('#edit_warehouse_id').val();
            $.ajax({
                url: "/warehouses/" + warehouseId,
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#editWarehouseModal').modal('hide');
                    location.reload(); // Reload the page to reflect changes
                },
                error: function(response) {
                    alert('Error: ' + response.responseJSON.message);
                }
            });
        });

        // Delete Warehouse
        $('.delete-warehouse').on('click', function() {
            var warehouseId = $(this).data('id');
            $('#deleteWarehouseModal').modal('show');
            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: "/warehouses/" + warehouseId,
                    method: 'DELETE',
                    success: function(response) {
                        $('#deleteWarehouseModal').modal('hide');
                        location.reload(); // Reload the page to reflect changes
                    },
                    error: function(response) {
                        alert('Error: ' + response.responseJSON.message);
                    }
                });
            });
        });
    });
</script>
@endsection
