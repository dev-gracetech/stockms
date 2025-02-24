@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>List Of Stocks</h3>
            <p class="text-subtitle text-muted">Manage your stocks here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <!-- Success/Error Message -->
        <div id="responseMessage"></div>
        <div class="card">
            <div class="card-header">
                <div class="col-md-6 m-2">
                    <button class="btn btn-primary" id="createStockBtn" data-bs-toggle="modal" data-bs-target="#createStockModal">
                        <i class="bi bi-plus-circle"></i> Create New Stock</button>
                </div>
            </div>
            <div class="card-body mt-3">
                <div class="table-responsive datatable-minimal">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Batch Number</th>
                                <th>Expiry Date</th>
                                <th>Price</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                                <tr>
                                    <td>{{ $stock->name }}</td>
                                    <td>{{ $stock->quantity }}</td>
                                    <td>{{ $stock->batch }}</td>
                                    <td>{{ $stock->expiry_date }}</td>
                                    <td>{{ $stock->price }}</td>
                                    <td>{{ $stock->location }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm edit-stock" data-id="{{ $stock->id }}" 
                                            data-bs-toggle="modal" data-bs-target="#editStockModal" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm delete-stock" data-id="{{ $stock->id }}" title="Delete">
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

<!-- Create Stock Modal -->
<div class="modal fade" id="createStockModal" tabindex="-1" aria-labelledby="createStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createStockModalLabel">Add Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createStockForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="batch">Batch Number</label>
                        <input type="text" class="form-control" id="batch" name="batch" required>
                    </div>
                    <div class="mb-3">
                        <label for="price">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="0.00">
                    </div>
                    <div class="mb-3">
                        <label for="expiry_date">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="location">Stock Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    <div class="mb-3">
                        <label for="warehouse_id">Warehouse</label>
                        <select class="form-control" id="warehouse_id" name="warehouse_id" required>
                            {{-- <option value="">Select Warehouse</option> --}}
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
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

<!-- Edit Stock Modal -->
<div class="modal fade" id="editStockModal" tabindex="-1" aria-labelledby="editStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStockModalLabel">Edit Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editStockForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_stock_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_quantity">Quantity</label>
                        <input type="number" class="form-control" id="edit_quantity" name="quantity" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="edit_batch">Batch Number</label>
                        <input type="text" class="form-control" id="edit_batch" name="batch" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_price">Price</label>
                        <input type="number" step="0.01" name="price" id="edit_price" class="form-control" value="0.00">
                    </div>
                    <div class="mb-3">
                        <label for="expiry_date">Expiry Date</label>
                        <input type="date" name="expiry_date" id="edit_expiry_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="edit_location">Stock Location</label>
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
<div class="modal fade" id="deleteStockModal" tabindex="-1" aria-labelledby="deleteStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteStockModalLabel">Delete Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this Stock?
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

        // Create stock
        $('#createStockForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('stocks.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#createStockModal').modal('hide');
                    location.reload(); // Reload the page to reflect changes
                },
                error: function(response) {
                    alert('Error: ' + response.responseJSON.message);
                }
            });
        });

        // Edit stock
        $('.edit-stock').on('click', function() {
            var stockId = $(this).data('id');
            $.ajax({
                url: `/stocks/${stockId}/edit-data`,
                method: 'GET',
                success: function(response) {
                    $('#edit_stock_id').val(stockId);
                    $('#edit_name').val(response.stock.name);
                    $('#edit_quantity').val(response.stock.quantity);
                    $('#edit_batch').val(response.stock.batch);
                    $('#edit_price').val(response.stock.price);
                    $('#edit_expiry_date').val(response.stock.expiry_date); 
                    $('#edit_location').val(response.stock.location);
                    $('#editStockModal').modal('show');
                }
            });
        });

        $('#editStockForm').on('submit', function(e) {
            e.preventDefault();
            var stockId = $('#edit_stock_id').val();
            $.ajax({
                url: "/stocks/" + stockId,
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#editStockModal').modal('hide');
                    location.reload(); // Reload the page to reflect changes
                },
                error: function(response) {
                    alert('Error: ' + response.responseJSON.message);
                }
            });
        });

        // Delete stock
        $('.delete-stock').on('click', function() {
            var stockId = $(this).data('id');
            $('#deleteStockModal').modal('show');
            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: "/stocks/" + stockId,
                    method: 'DELETE',
                    success: function(response) {
                        $('#deleteStockModal').modal('hide');
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