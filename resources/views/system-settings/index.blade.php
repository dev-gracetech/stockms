@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h1>Stock Management Settings</h1>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="section">
        <div class="card row">
            <div class="d-flex align-items-start mt-4">
                <div class="nav flex-column nav-pills me-5" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" 
                        type="button" role="tab" aria-controls="v-pills-general" aria-selected="true">General Settings</button>
                    @can('warehouse_manage')
                    <button class="nav-link" id="v-pills-warehouses-tab" data-bs-toggle="pill" data-bs-target="#v-pills-warehouses" 
                        type="button" role="tab" aria-controls="v-pills-warehouses" aria-selected="false">Warehouses</button>
                    @endcan
                    @can('branch_manage')
                    <button class="nav-link" id="v-pills-branches-tab" data-bs-toggle="pill" data-bs-target="#v-pills-branches" 
                        type="button" role="tab" aria-controls="v-pills-branches" aria-selected="false">Branches</button>
                    @endcan
                </div>
                <div class="tab-content col-6" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel" aria-labelledby="v-pills-general-tab">
                        <h3>Base Settings</h3>
                        <div class="card">
                            <div class="card-body text-center">
                                <div id="company-logo-container">
                                    @if($settings->company_logo)
                                        <img src="{{ asset('storage/' . $settings->company_logo) }}" alt="Company Logo" class="img-fluid" style="width: 80px; height: 80px;">
                                    @else
                                        <img src="{{ asset('images/logo.png') }}" alt="Default Company Logo" class="img-fluid" style="width: 80px; height: 80px;">
                                    @endif
                                </div>
                                <form id="upload-logo-form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="company_logo" id="company_logo" class="d-none" accept="image/*">
                                    <button type="button" id="upload-logo-btn" class="btn btn-primary mt-2">Upload Logo</button>
                                </form>
                                {{-- @if($settings->company_logo)
                                    <form id="remove-logo-form">
                                        @csrf
                                        <button type="button" id="remove-logo-btn" class="btn btn-danger mt-2" data-id="1">Remove Logo</button>
                                    </form>
                                @endif --}}
                            </div>
                            <div class="card-body">
                                <form action="{{ route('system-settings.update-data') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="company_name">Company Name</label>
                                        <input type="text" name="company_name" id="company_name"
                                            class="form-control" value="{{ $settings->company_name }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="high_stock_threshold">High Stock Threshold</label>
                                        <input type="number" name="high_stock_threshold" id="high_stock_threshold"
                                            class="form-control" value="{{ $settings->high_stock_threshold }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="low_stock_threshold">Low Stock Threshold</label>
                                        <input type="number" name="low_stock_threshold" id="low_stock_threshold"
                                            class="form-control" value="{{ $settings->low_stock_threshold }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="expiry_alert_days">Expiry Alert Days</label>
                                        <input type="number" name="expiry_alert_days" id="expiry_alert_days"
                                            class="form-control" value="{{ $settings->expiry_alert_days }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="default_stock_location">Default Stock Location</label>
                                        <input type="text" name="default_stock_location" id="default_stock_location"
                                            class="form-control" value="{{ $settings->default_stock_location }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="notification_email">Notification Email</label>
                                        <input type="email" name="notification_email" id="notification_email"
                                            class="form-control" value="{{ $settings->notification_email }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="currency">Currency</label>
                                        <input type="text" name="currency" id="currency"
                                            class="form-control" value="{{ $settings->currency }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Settings</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @can('warehouse_manage')
                    <div class="tab-pane fade" id="v-pills-warehouses" role="tabpanel" aria-labelledby="v-pills-warehouses-tab">
                        <h3>List Of Warehouses</h3>
                        <p class="text-subtitle text-muted">Manage your warehouses here.</p>
                        @include('system-settings.warehouse')
                    </div>
                    @endcan
                    @can('branch_manage')
                    <div class="tab-pane fade" id="v-pills-branches" role="tabpanel" aria-labelledby="v-pills-branches-tab">
                        <h3>List Of Branches</h3>
                        <p class="text-subtitle text-muted">Manage your branches here.</p>
                        @include('system-settings.branch')
                    </div>
                    @endcan
                </div>
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

        // Create Branch
        $('#createBranchForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('branches.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#createBranchModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    location.reload(); // Reload the page to reflect changes
                },
                error: function(response) {
                    alert('Error: ' + response.responseJSON.message);
                }
            });
        });
    });


    document.addEventListener('DOMContentLoaded', function () {
        const uploadLogoBtn = document.getElementById('upload-logo-btn');
        const logoInput = document.getElementById('company_logo');
        const uploadLogoForm = document.getElementById('upload-logo-form');
        const removeLogoBtn = document.getElementById('remove-logo-btn');
        const removeLogoForm = document.getElementById('remove-logo-form');
        const companyLogoContainer = document.getElementById('company-logo-container');

        // Trigger file input when "Upload Photo" button is clicked
        uploadLogoBtn.addEventListener('click', function () {
            logoInput.click();
        });

        // Submit the form when a file is selected
        logoInput.addEventListener('change', function () {
            if (logoInput.files.length > 0) {
                const formData = new FormData(uploadLogoForm);
                fetch("{{ route('update-logo') }}", {
                    method: 'POST',
                    body: formData,
                    /* headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }, */
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the profile photo dynamically
                        companyLogoContainer.innerHTML = `<img src="${data.logo_url}" alt="Company Logo" class="img-fluid" style="width: 80px; height: 80px;">`;
                        // Show the "Remove Photo" button
                        if (!removeLogoBtn) {
                            location.reload(); // Reload the page to show the remove button
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });

        // Remove the profile photo
        if (removeLogoBtn) {
            removeLogoBtn.addEventListener('click', function () {
                fetch(`/system-settings/1/remove-logo`, {
                    method: 'POST',
                    /* headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }, */
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the photo dynamically
                        companyLogoContainer.innerHTML = `<img src="{{ asset('images/logo.png') }}" alt="Default Company Logo" class="img-fluid" style="width: 80px; height: 80px;">`;
                        // Hide the "Remove Photo" button
                        location.reload(); // Reload the page to hide the remove button
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        };

        // Get all "Edit" buttons
        const editButtons = document.querySelectorAll('.edit-branch');

        // Add click event listeners to each "Edit" button
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get branch data from the button's data attributes
                const branchId = button.getAttribute('data-id');
                //const name = button.getAttribute('data-name');
                //const location = button.getAttribute('data-location');
                //console.log(branchId);

                // Fetch the branch data
                fetch(`/branches/${branchId}/edit-data`)
                .then(response => response.json())
                .then(data => {
                    //console.log(data);
                    // Populate the edit modal with branch data
                    document.getElementById('edit_branch_id').value = branchId;
                    document.getElementById('edit_branch_name').value = data.branch.name;
                    document.getElementById('edit_branch_location').value = data.branch.location;
                    // Update the form action URL
                    //document.getElementById('editBranchForm').action = `/branches/${branchId}`;
                })
                .catch(error => console.error('Error:', error));
            });
        });
        
        document.getElementById('editBranchForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const branchId = document.getElementById('edit_branch_id').value;
            const formData = new FormData(this);
            fetch(`/branches/${branchId}`, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide the modal
                    $('#editBranchModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Reload the page to reflect changes
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // Get all "Delete" buttons
        const deleteButtons = document.querySelectorAll('.delete-branch');

        // Add click event listeners to each "Delete" button
        let branchId;
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
            branchId = button.getAttribute('data-id');
            $('#deleteBranchModal').modal('show');
            });
        });
        // Add click event listener to the "Confirm Delete" button for warehouse
        document.getElementById('confirmDeleteBranch').addEventListener('click', function () {
            fetch(`/branches/${branchId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            })
            .then(response => response.json())
            .then(data => {
            if (data.success) {
                // Hide the modal
                $('#deleteBranchModal').modal('hide');

                Swal.fire({
                icon: 'success',
                title: data.message,
                showConfirmButton: false,
                timer: 1500
                });
                // Reload the page to reflect changes
                location.reload();
            }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>
@endsection