@extends('layouts.layout')
@section('custom-styles')
<style>
    /* Base image size */
    .stock-image {
        width: 50px;
        height: 50px;
        transition: transform 0.3s ease; /* Smooth transition */
    }

    /* Enlarge image on hover */
    .stock-image:hover {
        transform: scale(3); /* Enlarge by 3x */
        position: relative;
        z-index: 1000; /* Ensure the enlarged image is on top */
    }
</style>
@endsection

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>List Of Products</h3>
            <p class="text-subtitle text-muted">Manage your product stocks here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <!-- Success/Error Message -->
        <div id="responseMessage"></div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 m-2">
                        <button class="btn btn-primary" id="createStockBtn" data-bs-toggle="modal" data-bs-target="#createStockModal">
                            <i class="bi bi-plus-circle"></i> Create New Product</button>
                    </div>
                </div>
                @can('stock_import')
                <div class="row">
                    <div class="col-md-4 mt-2">
                        <form action="{{ route('stocks.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="file" class="form-control" name="file" accept=".xlsx,.csv" required>
                                <button type="submit" class="btn btn-success">Import Stocks</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endcan
            </div>
            <div class="card-body mt-3">
                <div class="table-responsive datatable-minimal">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Product</th>
                                <th>Batch Number</th>
                                <th>Available Quantity</th>
                                <th>Expiry Date</th>
                                <th>Buying Price</th>
                                <th>Selling Price</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                                <tr>
                                    <td>
                                        {{-- <button class="btn btn-info btn-sm upload-image-btn" data-stock-id="{{ $stock->id }}">Upload Image</button> --}}
                                        <a href="#" class="upload-image-btn" data-stock-id="{{ $stock->id }}">
                                            <img src="{{ $stock->ImageUrl }}" alt="{{ $stock->name }}" width="50" height="50" class="stock-image">
                                        </a>
                                    </td>
                                    <td>{{ $stock->name }}</td>
                                    <td>{{ $stock->batch }}</td>
                                    <td>{{ $stock->quantity }}</td>
                                    <td>{{ $stock->expiry_date }}</td>
                                    <td>{{ $stock->price }}</td>
                                    <td>{{ $stock->selling_price }}</td>
                                    <td>{{ $stock->location }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm replenish-stock" data-id="{{ $stock->id }}" 
                                            data-bs-toggle="modal" data-bs-target="#replenishStockModal" title="Add Stock">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm dispose-stock" data-id="{{ $stock->id }}" 
                                            data-bs-toggle="modal" data-bs-target="#disposeStockModal" title="Dispose Stock">
                                            <i class="bi bi-trash2"></i>
                                        </button>
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
            <form id="createStockForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div> --}}
                    <div class="mb-3">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    {{-- <div class="mb-3">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required min="1">
                    </div> --}}
                    <div class="mb-3">
                        <label for="batch">Batch Number</label>
                        <input type="text" class="form-control" id="batch" name="batch" required>
                    </div>
                    <div class="mb-3">
                        <label for="price">Buying Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="0.00">
                    </div>
                    <div class="mb-3">
                        <label for="selling_price">Selling Price</label>
                        <input type="number" step="0.01" name="selling_price" class="form-control" value="0.00">
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
                        <label for="minimum_threshold" class="form-label">Minimum Threshold</label>
                        <input type="number" class="form-control" id="minimum_threshold" name="minimum_threshold" required>
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
            <form id="editStockForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_stock_id" name="id">
                    {{-- <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="edit_image" name="image">
                    </div> --}}
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_quantity">Quantity</label>
                        <input type="number" class="form-control" id="edit_quantity" name="quantity" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="edit_batch">Batch Number</label>
                        <input type="text" class="form-control" id="edit_batch" name="batch" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_price">Buying Price</label>
                        <input type="number" step="0.01" name="price" id="edit_price" class="form-control" value="0.00">
                    </div>
                    <div class="mb-3">
                        <label for="edit_selling_price">Selling Price</label>
                        <input type="number" step="0.01" name="selling_price" id="edit_selling_price" class="form-control" value="0.00">
                    </div>
                    <div class="mb-3">
                        <label for="expiry_date">Expiry Date</label>
                        <input type="date" name="expiry_date" id="edit_expiry_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="edit_location">Stock Location</label>
                        <input type="text" class="form-control" id="edit_location" name="location" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_minimum_threshold" class="form-label">Minimum Threshold</label>
                        <input type="number" class="form-control" id="edit_minimum_threshold" name="minimum_threshold" required>
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

<!-- Replenish Stock Modal -->
<div class="modal fade" id="replenishStockModal" tabindex="-1" aria-labelledby="replenishStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replenishStockModalLabel">Add Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="replenishStockForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="stock_id" id="stock_id">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="source" class="form-label">Source</label>
                        <input type="text" name="source" id="source" class="form-control">
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

<!-- Dispose Stock Modal -->
<div class="modal fade" id="disposeStockModal" tabindex="-1" aria-labelledby="disposeStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="disposeStockModalLabel">Dispose Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="disposeStockForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="stock_id" id="stock_id">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" required></textarea>
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

<!-- Image Upload Modal -->
<div class="modal fade" id="imageUploadModal" tabindex="-1" aria-labelledby="imageUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageUploadModalLabel">Upload Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="imageUploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="image" class="form-label">Choose Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
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
                    Swal.fire({
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    location.reload(); // Reload the page to reflect changes
                },
                error: function(response) {
                    //alert('Error: ' + response.responseJSON.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.responseJSON.message
                    });
                }
            });
        });

    });
    document.addEventListener('DOMContentLoaded', function () {
        //Set Image of Product
        const imageUploadModal = document.getElementById('imageUploadModal');
        const imageUploadForm = document.getElementById('imageUploadForm');
        let stockId;
        // Open the modal and set the stock ID
        document.querySelectorAll('.upload-image-btn').forEach(button => {
            button.addEventListener('click', function () {
                stockId = this.getAttribute('data-stock-id');
                imageUploadModal.style.display = 'block';
                new bootstrap.Modal(imageUploadModal).show();
            });
        });
        // Handle form submission
        imageUploadForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');

            fetch(`/stocks/${stockId}/upload-image`, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Image uploaded successfully.');
                    window.location.reload();
                } else {
                    alert('Failed to upload image.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        //Edit Stock
        document.querySelectorAll('.edit-stock').forEach(button => {
            button.addEventListener('click', function () {
                const stockId = this.getAttribute('data-id');
                fetch(`/stocks/${stockId}/edit-data`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_stock_id').value = stockId;
                    document.getElementById('edit_name').value = data.stock.name;
                    document.getElementById('edit_quantity').value = data.stock.quantity;
                    document.getElementById('edit_batch').value = data.stock.batch;
                    document.getElementById('edit_price').value = data.stock.price;
                    document.getElementById('edit_selling_price').value = data.stock.selling_price;
                    document.getElementById('edit_expiry_date').value = data.stock.expiry_date;
                    document.getElementById('edit_location').value = data.stock.location;
                    document.getElementById('edit_minimum_threshold').value = data.stock.minimum_threshold;
                    $('#editStockModal').modal('show');
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
        //Submit edit stock form
        document.getElementById('editStockForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const stockId = document.getElementById('edit_stock_id').value;
            const formData = new FormData(this);
            formData.append('_method', 'PUT');
            fetch(`/stocks/${stockId}`, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update stock.',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        //Delete stock
        // Get all "Delete" buttons
        const deleteButtons = document.querySelectorAll('.delete-stock');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
            stockId = button.getAttribute('data-id');
            $('#deleteStockModal').modal('show');
            });
        });
        // Handle the click event of the "Delete" button
        document.getElementById('confirmDelete').addEventListener('click', function () {
            fetch(`/stocks/${stockId}`, {
                method: 'DELETE',
                headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete stock.',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        //Replenish stock
        // Get all "Replenish" buttons
        const replenishButtons = document.querySelectorAll('.replenish-stock');
        
        replenishButtons.forEach(button => {
            button.addEventListener('click', function () {
            stockId = button.getAttribute('data-id');
            $('#stock_id').val(stockId);
            $('#replenishStockModal').modal('show');
            });
        });
        // Handle the form submission
        document.getElementById('replenishStockForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch("{{ route('stocks.replenish') }}", {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to replenish stock.',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        //Dispose stock
        // Get all "Dispose" buttons
        const disposeButtons = document.querySelectorAll('.dispose-stock');
        disposeButtons.forEach(button => {
            button.addEventListener('click', function () {
            stockId = button.getAttribute('data-id');
            $('#stock_id').val(stockId);
            $('#disposeStockModal').modal('show');
            });
        });
        // Handle the form submission
        document.getElementById('disposeStockForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(`/stocks/${stockId}/dispose`, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to dispose stock.',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
</script>
@endsection